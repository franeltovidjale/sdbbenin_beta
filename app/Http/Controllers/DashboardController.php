<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Production;
use App\Models\ProductionArticle;
use App\Models\ProductionOutput;
use App\Models\TypeProduction;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Définir un seuil de stock bas (puisque min_stock n'existe pas dans la table)
        $lowStockThreshold = 5; // Vous pouvez ajuster cette valeur selon vos besoins
        
        // Statistiques des productions
        $totalProductions = Production::count();
        
        // Calculer le pourcentage de croissance des productions
        $currentMonthProductions = Production::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        $lastMonthProductions = Production::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        
        $productionPercentage = $lastMonthProductions > 0 
            ? round((($currentMonthProductions - $lastMonthProductions) / $lastMonthProductions) * 100, 1)
            : 100;
        
        // Statistiques des articles
        $totalArticles = Article::sum('stock_quantity');
        $articlesCount = Article::count();
        
        // Statut des articles (utiliser stock_quantity et in_stock)
        $articlesStatus = [
            'color' => 'green',
            'icon' => 'check',
            'message' => 'Stocks disponibles'
        ];
        
        // Articles avec stock bas (moins que le seuil défini)
        $lowStockCount = Article::where('stock_quantity', '<=', $lowStockThreshold)
            ->where('in_stock', true) // On compte seulement les articles qui devraient être en stock
            ->count();
        
        // Calculer le pourcentage d'articles en alerte de stock
        if ($articlesCount > 0) {
            $lowStockPercentage = ($lowStockCount / $articlesCount) * 100;
            
            if ($lowStockPercentage > 20) {
                $articlesStatus = [
                    'color' => 'red',
                    'icon' => 'arrow-down',
                    'message' => 'Niveau critique'
                ];
            } elseif ($lowStockCount > 0) {
                $articlesStatus = [
                    'color' => 'yellow',
                    'icon' => 'exclamation',
                    'message' => $lowStockCount . ' articles à réapprovisionner'
                ];
            }
        }
        
        // Statistiques des cartons produits
        $totalCartons = ProductionOutput::sum('carton_count') ?? 0;
        
        // Calculer le pourcentage de croissance des cartons
        $currentWeekCartons = ProductionOutput::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('carton_count') ?? 0;
        $lastWeekCartons = ProductionOutput::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->sum('carton_count') ?? 0;
        
        $cartonsPercentage = $lastWeekCartons > 0
            ? round((($currentWeekCartons - $lastWeekCartons) / $lastWeekCartons) * 100, 1)
            : 100;
        
        // Tendance des articles en alerte
        $previousDate = now()->subWeek();
        $previousLowStockQuery = Article::where('stock_quantity', '<=', $lowStockThreshold)
            ->where('in_stock', true);
            
        // Vérifier si la colonne updated_at existe et est utilisable
        $previousLowStockCount = 0;
        try {
            $previousLowStockCount = $previousLowStockQuery->where('updated_at', '<', $previousDate)->count();
        } catch (\Exception $e) {
            // Si updated_at n'est pas disponible ou cause des erreurs, utilisez une valeur par défaut
            $previousLowStockCount = $lowStockCount > 0 ? $lowStockCount - 1 : 0;
        }
        
        $lowStockTrend = [
            'direction' => $lowStockCount >= $previousLowStockCount ? 'up' : 'down',
            'message' => $lowStockCount >= $previousLowStockCount ? 'En augmentation' : 'En diminution'
        ];
        
        // Articles en alerte de stock
        $lowStockArticles = Article::where('stock_quantity', '<=', $lowStockThreshold)
            ->where('in_stock', true)
            ->orderBy('stock_quantity', 'asc')
            ->take(5)
            ->get();
        
        // Ajouter un attribut min_stock pour la compatibilité avec la vue
        $lowStockArticles->each(function ($article) use ($lowStockThreshold) {
            $article->min_stock = $lowStockThreshold;
        });
        
        // Productions récentes
        $recentProductions = Production::with('type')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Articles les plus utilisés dans les productions
        $topArticles = [];
        try {
            $topArticles = ProductionArticle::select('article_id', DB::raw('COUNT(*) as usage_count'))
                ->with('article:id,name,stock_quantity')
                ->groupBy('article_id')
                ->orderBy('usage_count', 'desc')
                ->take(5)
                ->get()
                ->map(function ($item) {
                    if ($item->article) {
                        $item->name = $item->article->name;
                    } else {
                        $item->name = 'Article inconnu';
                    }
                    return $item;
                });
        } catch (\Exception $e) {
            // Si la table ou la relation n'existe pas encore
        }
        
        // Nombre de productions par type
        $productionsByType = [];
        try {
            $productionsByType = TypeProduction::withCount('productions')
                ->having('productions_count', '>', 0)
                ->orderBy('productions_count', 'desc')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            // Si la table ou la relation n'existe pas encore
        }
        
        // Productions terminées récemment
        $recentCompletedProductions = [];
        try {
            $recentCompletedProductions = Production::with('type')
                ->where('status', 'terminé')
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();
                
            // Calculer le nombre total de cartons produits
            foreach ($recentCompletedProductions as $production) {
                try {
                    $production->total_cartons = ProductionOutput::where('production_id', $production->id)
                        ->sum('carton_count');
                } catch (\Exception $e) {
                    $production->total_cartons = 0;
                }
            }
        } catch (\Exception $e) {
            // Si la table n'existe pas encore ou si le champ status n'existe pas
        }
        
        return view('dashboard.index', compact(
            'totalProductions',
            'productionPercentage',
            'totalArticles',
            'articlesStatus',
            'totalCartons',
            'cartonsPercentage',
            'lowStockCount',
            'lowStockTrend',
            'lowStockArticles',
            'recentProductions',
            'topArticles',
            'productionsByType',
            'recentCompletedProductions',
            'lowStockThreshold'
        ));
    }
}