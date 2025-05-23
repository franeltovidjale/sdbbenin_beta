<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductionSale;
use App\Models\TypeProduction;
use Carbon\Carbon;

class ClientController extends Controller
{
    /**
     * Afficher la liste des clients avec métriques
     */
    public function index(Request $request)
    {
        // Récupérer les filtres de la requête
        $sortBy = $request->get('sort_by', 'total_amount'); // tri par défaut
        $sortDirection = $request->get('sort_direction', 'desc');
        $preferredCarton = $request->get('preferred_carton', '');
        $minAmount = $request->get('min_amount', '');
        $maxAmount = $request->get('max_amount', '');
        $searchTerm = $request->get('search', '');

        // Récupérer les clients avec métriques
        $clients = $this->getClientsWithMetrics($request);
        
        // Statistiques globales
        $globalStats = $this->getGlobalStats();
        
        // Top clients pour les widgets
        $topClients = $this->getTopClients(3);
        
        return view('clients.index', compact(
            'clients', 
            'globalStats', 
            'topClients',
            'sortBy',
            'sortDirection',
            'preferredCarton',
            'minAmount',
            'maxAmount',
            'searchTerm'
        ));
    }

    /**
     * Récupérer les clients avec toutes leurs métriques
     */
    private function getClientsWithMetrics(Request $request)
    {
        // Construction de la requête de base
        $query = DB::table('production_sales')
            ->select([
                'client_name',
                'client_firstname', 
                'client_phone',
                'client_email',
                
                // Métriques globales
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(quantity * unit_price) as total_amount'),
                DB::raw('AVG(quantity * unit_price) as average_order'),
                
                // Détail petits cartons
                DB::raw('SUM(CASE WHEN carton_type = "petit" THEN 1 ELSE 0 END) as petit_orders'),
                DB::raw('SUM(CASE WHEN carton_type = "petit" THEN quantity ELSE 0 END) as petit_quantity'),
                DB::raw('SUM(CASE WHEN carton_type = "petit" THEN quantity * unit_price ELSE 0 END) as petit_amount'),
                
                // Détail grands cartons
                DB::raw('SUM(CASE WHEN carton_type = "grand" THEN 1 ELSE 0 END) as grand_orders'),
                DB::raw('SUM(CASE WHEN carton_type = "grand" THEN quantity ELSE 0 END) as grand_quantity'),
                DB::raw('SUM(CASE WHEN carton_type = "grand" THEN quantity * unit_price ELSE 0 END) as grand_amount'),
                
                // Dates
                DB::raw('MIN(created_at) as first_order_date'),
                DB::raw('MAX(created_at) as last_order_date'),
                DB::raw('DATEDIFF(NOW(), MAX(created_at)) as days_since_last')
            ])
            ->where('status', 'validé')
            ->groupBy('client_name', 'client_firstname', 'client_phone', 'client_email');

        // Appliquer les filtres
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('client_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('client_firstname', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('client_phone', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('client_email', 'LIKE', "%{$searchTerm}%");
            });
        }

        if ($request->filled('min_amount')) {
            $query->havingRaw('SUM(quantity * unit_price) >= ?', [$request->get('min_amount')]);
        }

        if ($request->filled('max_amount')) {
            $query->havingRaw('SUM(quantity * unit_price) <= ?', [$request->get('max_amount')]);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'total_amount');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        $validSortColumns = [
            'total_amount', 'total_orders', 'total_quantity', 
            'last_order_date', 'first_order_date', 'client_name'
        ];
        
        if (in_array($sortBy, $validSortColumns)) {
            $query->orderBy($sortBy, $sortDirection);
        }

        // Pagination
        $results = $query->paginate(15);
        
        // Enrichir les données avec les métriques calculées
        $results->getCollection()->transform(function ($client) {
            return $this->enrichClientData($client);
        });

        return $results;
    }

/**
 * Enrichir les données client avec les métriques calculées
 * Version défensive qui vérifie l'existence de toutes les propriétés requises
 */
private function enrichClientData($client)
{
    // Convertir en objet pour faciliter la manipulation
    $client = (object) $client;
    
    // ÉTAPE 0: Vérifier et définir les propriétés manquantes si nécessaire
    if (!property_exists($client, 'last_order_date') && property_exists($client, 'first_order_date')) {
        $client->last_order_date = $client->first_order_date;
    }
    
    if (!property_exists($client, 'days_since_last') && property_exists($client, 'last_order_date')) {
        $client->days_since_last = Carbon::parse($client->last_order_date)->diffInDays(now());
    }
    
    if (!property_exists($client, 'average_order') && property_exists($client, 'total_amount') && property_exists($client, 'total_orders')) {
        $client->average_order = $client->total_orders > 0 ? $client->total_amount / $client->total_orders : 0;
    }
    
    // S'assurer que les champs petit_amount et grand_amount existent
    if (!property_exists($client, 'petit_amount')) {
        $client->petit_amount = 0;
    }
    
    if (!property_exists($client, 'grand_amount')) {
        $client->grand_amount = 0;
    }
    
    // ÉTAPE 1: Calculer l'ancienneté en jours (nécessaire pour le score de fidélité)
    if ($client->first_order_date) {
        $client->customer_age_days = Carbon::parse($client->first_order_date)->diffInDays(now());
    } else {
        $client->customer_age_days = 0;
    }
    
    // ÉTAPE 2: Calculer les pourcentages par type de carton
    if ($client->total_amount > 0) {
        $client->petit_percentage = round(($client->petit_amount / $client->total_amount) * 100, 1);
        $client->grand_percentage = round(($client->grand_amount / $client->total_amount) * 100, 1);
    } else {
        $client->petit_percentage = 0;
        $client->grand_percentage = 0;
    }

    // ÉTAPE 3: Déterminer la préférence de carton
    if ($client->petit_percentage >= 70) {
        $client->preferred_carton_type = 'petit';
        $client->preference_label = 'Petit (' . $client->petit_percentage . '%)';
        $client->preference_color = 'blue';
    } elseif ($client->grand_percentage >= 70) {
        $client->preferred_carton_type = 'grand';
        $client->preference_label = 'Grand (' . $client->grand_percentage . '%)';
        $client->preference_color = 'green';
    } else {
        $client->preferred_carton_type = 'mixte';
        $client->preference_label = 'Mixte';
        $client->preference_color = 'purple';
    }

    // ÉTAPE 4: Calculer le score de diversification (0-1)
    if ($client->total_amount > 0) {
        $petit_ratio = $client->petit_percentage / 100;
        $client->diversification_score = round(1 - abs(0.5 - $petit_ratio), 2);
    } else {
        $client->diversification_score = 0;
    }

    // ÉTAPE 5: Calculer le score de fidélité composite (maintenant que customer_age_days existe)
    $client->loyalty_score = $this->calculateLoyaltyScore($client);

    // ÉTAPE 6: Déterminer le statut du client
    $client->status = $this->getClientStatus($client);

    // ÉTAPE 7: Formater les montants
    $client->total_amount_formatted = number_format($client->total_amount, 0, ',', ' ') . ' FCFA';
    $client->petit_amount_formatted = number_format($client->petit_amount, 0, ',', ' ') . ' FCFA';
    $client->grand_amount_formatted = number_format($client->grand_amount, 0, ',', ' ') . ' FCFA';
    $client->average_order_formatted = number_format($client->average_order, 0, ',', ' ') . ' FCFA';

    return $client;
}

    /**
     * Calculer le score de fidélité composite
     */
    private function calculateLoyaltyScore($client)
    {
        // Normalisation des valeurs (sur une base de 100)
        $maxAmount = 5000000; // 5M FCFA comme référence max
        $maxOrders = 50; // 50 commandes comme référence max
        $maxAge = 365; // 1 an comme référence max

        // Scores normalisés (0-100)
        $amountScore = min(($client->total_amount / $maxAmount) * 100, 100);
        $ordersScore = min(($client->total_orders / $maxOrders) * 100, 100);
        $ageScore = min(($client->customer_age_days / $maxAge) * 100, 100);
        $diversificationScore = $client->diversification_score * 100;

        // Score composite avec pondération
        $loyaltyScore = (
            $amountScore * 0.4 +       // 40% montant total
            $ordersScore * 0.3 +       // 30% nombre commandes  
            $ageScore * 0.2 +          // 20% ancienneté
            $diversificationScore * 0.1 // 10% diversification
        );

        return round($loyaltyScore, 1);
    }

    /**
     * Déterminer le statut du client
     */
    private function getClientStatus($client)
    {
        if ($client->days_since_last <= 7) {
            return ['label' => 'Très actif', 'color' => 'green'];
        } elseif ($client->days_since_last <= 30) {
            return ['label' => 'Actif', 'color' => 'blue'];
        } elseif ($client->days_since_last <= 90) {
            return ['label' => 'Modéré', 'color' => 'yellow'];
        } else {
            return ['label' => 'Inactif', 'color' => 'red'];
        }
    }

    /**
     * Obtenir les statistiques globales
     */
    private function getGlobalStats()
    {
        $stats = DB::table('production_sales')
            ->where('status', 'validé')
            ->select([
                DB::raw('COUNT(DISTINCT CONCAT(client_name, "-", client_firstname)) as total_clients'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(quantity) as total_cartons'),
                DB::raw('SUM(quantity * unit_price) as total_revenue'),
                DB::raw('AVG(quantity * unit_price) as average_order_value'),
                
                // Par type de carton
                DB::raw('SUM(CASE WHEN carton_type = "petit" THEN quantity ELSE 0 END) as total_petit'),
                DB::raw('SUM(CASE WHEN carton_type = "grand" THEN quantity ELSE 0 END) as total_grand'),
                DB::raw('SUM(CASE WHEN carton_type = "petit" THEN quantity * unit_price ELSE 0 END) as revenue_petit'),
                DB::raw('SUM(CASE WHEN carton_type = "grand" THEN quantity * unit_price ELSE 0 END) as revenue_grand')
            ])
            ->first();

        // Calculer les pourcentages
        if ($stats->total_cartons > 0) {
            $stats->petit_percentage = round(($stats->total_petit / $stats->total_cartons) * 100, 1);
            $stats->grand_percentage = round(($stats->total_grand / $stats->total_cartons) * 100, 1);
        } else {
            $stats->petit_percentage = 0;
            $stats->grand_percentage = 0;
        }

        return $stats;
    }

    

    /**
     * Obtenir le top des clients
     */
    public function getTopClients($limit = 10)
    {
        return DB::table('production_sales')
            ->select([
                'client_name',
                'client_firstname',
                DB::raw('SUM(quantity * unit_price) as total_amount'),
                DB::raw('COUNT(*) as total_orders')
            ])
            ->where('status', 'validé')
            ->groupBy('client_name', 'client_firstname')
            ->orderByDesc('total_amount')
            ->limit($limit)
            ->get();
    }

/**
 * Afficher les détails d'un client
 */
public function show($clientName, $clientFirstname)
{
    // Récupérer l'historique complet du client
    $orders = ProductionSale::where('client_name', $clientName)
        ->where('client_firstname', $clientFirstname)
        ->where('status', 'validé')
        ->with('type')
        ->orderBy('created_at', 'desc')
        ->paginate(20);

    // Récupérer les métriques du client
    $clientMetrics = DB::table('production_sales')
        ->select([
            'client_name',
            'client_firstname',
            'client_phone',
            'client_email',
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('SUM(quantity * unit_price) as total_amount'),
            DB::raw('AVG(quantity * unit_price) as average_order'), // Ajout de cette ligne
            DB::raw('MIN(created_at) as first_order_date'),
            DB::raw('MAX(created_at) as last_order_date'),
            DB::raw('DATEDIFF(NOW(), MAX(created_at)) as days_since_last'),
            
            // Par type de carton
            DB::raw('SUM(CASE WHEN carton_type = "petit" THEN quantity ELSE 0 END) as petit_quantity'),
            DB::raw('SUM(CASE WHEN carton_type = "grand" THEN quantity ELSE 0 END) as grand_quantity'),
            DB::raw('SUM(CASE WHEN carton_type = "petit" THEN quantity * unit_price ELSE 0 END) as petit_amount'),
            DB::raw('SUM(CASE WHEN carton_type = "grand" THEN quantity * unit_price ELSE 0 END) as grand_amount')
        ])
        ->where('client_name', $clientName)
        ->where('client_firstname', $clientFirstname)
        ->where('status', 'validé')
        ->groupBy('client_name', 'client_firstname', 'client_phone', 'client_email')
        ->first();

    if (!$clientMetrics) {
        abort(404, 'Client non trouvé');
    }

    // Enrichir les données
    $clientMetrics = $this->enrichClientData($clientMetrics);

    return view('clients.show', compact('orders', 'clientMetrics'));
}

   
}