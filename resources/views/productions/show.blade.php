<!-- resources/views/productions/show.blade.php -->
@extends('layouts.app')
@section('title', 'Détails de la production')
@section('breadcrumb')
<i class="fas fa-chevron-right mx-2 text-gray-400"></i>
<a href="{{ route('production.index') }}" class="hover:text-blue-700 transition-colors">Productions</a>
<i class="fas fa-chevron-right mx-2 text-gray-400"></i>
<span class="text-gray-700">Détails</span>
@endsection
@section('page-title', 'Détails de la production')
@section('page-subtitle', 'Gestion des articles, de la main d\'œuvre et de la production finale')
@section('content')
<div class="space-y-6">
   <!-- Informations générales de la production -->
   <div class="bg-white rounded-lg shadow-sm p-6">
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
         <div>
            <h2 class="text-xl font-bold text-gray-800">{{ $production->production_name }}</h2>
            <div class="flex items-center mt-2">
               <span class="text-gray-600 mr-4">
               <i class="fas fa-calendar-alt mr-1 text-blue-500"></i> {{ date('d/m/Y', strtotime($production->production_date)) }}
               </span>
               <span class="text-gray-600 mr-4">
               <i class="fas fa-tag mr-1 text-blue-500"></i> {{ $production->type->name ?? 'Aucun type' }}
               </span>
               <span class="text-gray-600">
               <i class="fas fa-cubes mr-1 text-blue-500"></i> Quantité initiale: {{ number_format($production->qte_production, 2, ',', ' ') }}
               </span>
            </div>
         </div>
         <div class="mt-4 md:mt-0">
            @if($production->status == 'en cours')
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
            <span class="mr-1.5 h-2 w-2 bg-blue-500 rounded-full"></span>
            En cours
            </span>
            @else
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
            <span class="mr-1.5 h-2 w-2 bg-green-500 rounded-full"></span>
            Terminé
            </span>
            @endif
         </div>
      </div>
   </div>
   <!-- Section 1: Articles utilisés -->
   <div class="bg-white rounded-lg shadow-sm p-6">
      <h3 class="text-lg font-bold text-gray-800 mb-4">1. Articles utilisés dans la production</h3>
      @if($production->status == 'en cours')
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
         <!-- Formulaire d'ajout d'article corrigé -->
         <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="font-medium text-gray-700 mb-3">Ajouter un article</h4>
            <form id="addArticleForm" class="space-y-4">
               @csrf
               <input type="hidden" name="production_id" value="{{ $production->id }}">
               <div>
                  <label for="article_id" class="block text-sm font-medium text-gray-700 mb-1">Article <span class="text-red-500">*</span></label>
                  <select id="article_id" name="article_id" class="form-select-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                     <option value="">-- Sélectionner un article --</option>
                     @foreach($articles as $article)
                     <option value="{{ $article->id }}" 
                        data-stock="{{ $article->stock_quantity }}"
                        data-price="{{ $article->unit_price }}">
                        {{ $article->name }} (Stock: {{ $article->stock_quantity }}, Prix: {{ number_format($article->unit_price, 2, ',', ' ') }})
                     </option>
                     @endforeach
                  </select>
                  <div id="article_error" class="text-red-500 text-xs mt-1 hidden"></div>
               </div>
               <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                     <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantité <span class="text-red-500">*</span></label>
                     <input type="number" id="quantity" name="quantity" min="0.01" step="0.01" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                     <div id="quantity_error" class="text-red-500 text-xs mt-1 hidden"></div>
                  </div>
                  {{-- 
                  <div>
                     <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire</label>
                     <input type="number" id="unit_price" name="unit_price" min="0.01" step="0.01" class="form-input-focus w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-100" readonly>
                     <div class="text-xs text-gray-500 mt-1">Prix automatiquement récupéré de l'article sélectionné</div>
                     <div id="unit_price_error" class="text-red-500 text-xs mt-1 hidden"></div>
                  </div>
                  --}}
                  <div>
                     <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire</label>
                     <input style="cursor: not-allowed;" type="number" id="unit_price" min="0.01" step="0.01" class="form-input-focus w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-100" readonly>
                     <div class="text-xs text-gray-500 mt-1">Prix automatiquement récupéré de l'article sélectionné</div>
                     <div id="unit_price_error" class="text-red-500 text-xs mt-1 hidden"></div>
                  </div>
               </div>
               <div>
                  <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center">
                  <i class="fas fa-plus-circle mr-2"></i> Ajouter l'article
                  </button>
               </div>
            </form>
         </div>
         <!-- Liste des articles ajoutés -->
         <div>
            <h4 class="font-medium text-gray-700 mb-3">Articles déclarés</h4>
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
               <div class="overflow-x-auto">
                  <table class="min-w-full divide-y divide-gray-200" id="articlesTable">
                     <thead class="bg-gray-50">
                        <tr>
                           <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Article</th>
                           <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                           <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix Unit.</th>
                           <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                           <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                     </thead>
                     <tbody class="bg-white divide-y divide-gray-200" id="articlesTableBody">
                        @forelse($usedArticles as $usedArticle)
                        <tr data-id="{{ $usedArticle->id }}">
                           <td class="px-4 py-3 whitespace-nowrap">
                              <div class="text-sm font-medium text-gray-900">{{ $usedArticle->article->name ?? 'Article inconnu' }}</div>
                           </td>
                           <td class="px-4 py-3 whitespace-nowrap">
                              <div class="text-sm text-gray-900">{{ number_format($usedArticle->quantity, 2, ',', ' ') }}</div>
                           </td>
                           <td class="px-4 py-3 whitespace-nowrap">
                              <div class="text-sm text-gray-900">{{ number_format($usedArticle->unit_price, 2, ',', ' ') }}</div>
                           </td>
                           <td class="px-4 py-3 whitespace-nowrap">
                              <div class="text-sm font-medium text-gray-900">{{ number_format($usedArticle->quantity * $usedArticle->unit_price, 2, ',', ' ') }}</div>
                           </td>
                           <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                              <button class="edit-article text-blue-600 hover:text-blue-900 mx-1" 
                                 data-id="{{ $usedArticle->id }}"
                                 data-article-id="{{ $usedArticle->article_id }}"
                                 data-quantity="{{ $usedArticle->quantity }}"
                                 data-unit-price="{{ $usedArticle->unit_price }}">
                              <i class="fas fa-edit"></i>
                              </button>
                              <button class="delete-article text-red-600 hover:text-red-900 mx-1" 
                                 data-id="{{ $usedArticle->id }}">
                              <i class="fas fa-trash"></i>
                              </button>
                           </td>
                        </tr>
                        @empty
                        <tr id="noArticlesRow">
                           <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                              Aucun article déclaré pour cette production
                           </td>
                        </tr>
                        @endforelse
                     </tbody>
                     <tfoot class="bg-gray-50">
                        <tr>
                           <td colspan="3" class="px-4 py-3 text-right font-medium text-gray-700">Total :</td>
                           <td class="px-4 py-3 whitespace-nowrap">
                              <div class="text-sm font-bold text-gray-900" id="articlesTotal">
                                 {{ number_format($usedArticles->sum(function($a) { return $a->quantity * $a->unit_price; }), 2, ',', ' ') }}
                              </div>
                           </td>
                           <td></td>
                        </tr>
                     </tfoot>
                  </table>
               </div>
               <!-- Ajouter la pagination -->
               <div class="px-4 py-3 bg-white border-t border-gray-200">
                  {{ $usedArticles->appends(['labor_page' => request()->labor_page, 'output_page' => request()->output_page])->links() }}
               </div>
            </div>
         </div>
      </div>
      @else
      <!-- Affichage des articles pour une production terminée -->
      <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
         <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
               <thead class="bg-gray-50">
                  <tr>
                     <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Article</th>
                     <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                     <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix Unit.</th>
                     <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                  </tr>
               </thead>
               <tbody class="bg-white divide-y divide-gray-200">
                  @forelse($usedArticles as $usedArticle)
                  <tr>
                     <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $usedArticle->article->name ?? 'Article inconnu' }}</div>
                     </td>
                     <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ number_format($usedArticle->quantity, 2, ',', ' ') }}</div>
                     </td>
                     <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ number_format($usedArticle->unit_price, 2, ',', ' ') }}</div>
                     </td>
                     <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ number_format($usedArticle->quantity * $usedArticle->unit_price, 2, ',', ' ') }}</div>
                     </td>
                  </tr>
                  @empty
                  <tr>
                     <td colspan="4" class="px-4 py-3 text-center text-gray-500">
                        Aucun article déclaré pour cette production
                     </td>
                  </tr>
                  @endforelse
               </tbody>
               <tfoot class="bg-gray-50">
                  <tr>
                     <td colspan="3" class="px-4 py-3 text-right font-medium text-gray-700">Total :</td>
                     <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900">
                           {{ number_format($usedArticles->sum(function($a) { return $a->quantity * $a->unit_price; }), 2, ',', ' ') }}
                        </div>
                     </td>
                  </tr>
               </tfoot>
            </table>
         </div>
      </div>
      @endif
   </div>
   <!-- Section 2: Main d'œuvre MISE À JOUR -->
<div class="bg-white rounded-lg shadow-sm p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">2. Main d'œuvre</h3>
    @if($production->status == 'en cours')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
       <!-- Formulaire d'ajout de main d'œuvre SIMPLIFIÉ -->
       <div class="bg-gray-50 rounded-lg p-4">
          <h4 class="font-medium text-gray-700 mb-3">Déclarer la main d'œuvre</h4>
          <form id="addLaborForm" class="space-y-4">
             @csrf
             <input type="hidden" name="production_id" value="{{ $production->id }}">
             
             <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                   <label for="workers_count" class="block text-sm font-medium text-gray-700 mb-1">
                      Nombre d'ouvriers <span class="text-red-500">*</span>
                   </label>
                   <input type="number" id="workers_count" name="workers_count" min="1" step="1" 
                          class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                   <div id="workers_count_error" class="text-red-500 text-xs mt-1 hidden"></div>
                </div>
                
                <div>
                   <label for="worker_price" class="block text-sm font-medium text-gray-700 mb-1">
                      Prix par ouvrier <span class="text-red-500">*</span>
                   </label>
                   <input type="number" id="worker_price" name="worker_price" min="0.01" step="0.01" 
                          class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                   <div id="worker_price_error" class="text-red-500 text-xs mt-1 hidden"></div>
                </div>
             </div>
             
             <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                   Description
                </label>
                <textarea id="description" name="description" rows="3" 
                          class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                          placeholder="Précisez le type de travail, les tâches effectuées, etc. (facultatif)"></textarea>
                <div class="text-xs text-gray-500 mt-1">Description du travail effectué (facultatif)</div>
                <div id="description_error" class="text-red-500 text-xs mt-1 hidden"></div>
             </div>
             
             <div>
                <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center">
                   <i class="fas fa-plus-circle mr-2"></i> Ajouter la main d'œuvre
                </button>
             </div>
          </form>
       </div>
       
       <!-- Tableau de la main d'œuvre SIMPLIFIÉ -->
       <div>
          <h4 class="font-medium text-gray-700 mb-3">Main d'œuvre déclarée</h4>
          <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
             <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="laborTable">
                   <thead class="bg-gray-50">
                      <tr>
                         <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ouvriers</th>
                         <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix/Ouvrier</th>
                         <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                         <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                         <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                      </tr>
                   </thead>
                   <tbody class="bg-white divide-y divide-gray-200" id="laborTableBody">
                      @forelse($laborEntries as $labor)
                      <tr data-id="{{ $labor->id }}">
                         <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $labor->workers_count }}</div>
                         </td>
                         <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ number_format($labor->worker_price, 2, ',', ' ') }}</div>
                         </td>
                         <td class="px-4 py-3 max-w-xs">
                            <div class="text-sm text-gray-900 truncate" title="{{ $labor->description }}">
                               {{ $labor->description ?: '-' }}
                            </div>
                         </td>
                         <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                               {{ number_format($labor->workers_count * $labor->worker_price, 2, ',', ' ') }}
                            </div>
                         </td>
                         <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                            <button class="edit-labor text-blue-600 hover:text-blue-900 mx-1" 
                               data-id="{{ $labor->id }}"
                               data-workers-count="{{ $labor->workers_count }}"
                               data-worker-price="{{ $labor->worker_price }}"
                               data-description="{{ $labor->description }}">
                               <i class="fas fa-edit"></i>
                            </button>
                            <button class="delete-labor text-red-600 hover:text-red-900 mx-1" 
                               data-id="{{ $labor->id }}">
                               <i class="fas fa-trash"></i>
                            </button>
                         </td>
                      </tr>
                      @empty
                      <tr id="noLaborRow">
                         <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                            Aucune main d'œuvre déclarée pour cette production
                         </td>
                      </tr>
                      @endforelse
                   </tbody>
                   <tfoot class="bg-gray-50">
                      <tr>
                         <td colspan="3" class="px-4 py-3 text-right font-medium text-gray-700">Total :</td>
                         <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900" id="laborTotal">
                               {{ number_format($laborEntries->sum(function($l) { 
                               return $l->workers_count * $l->worker_price; 
                               }), 2, ',', ' ') }}
                            </div>
                         </td>
                         <td></td>
                      </tr>
                   </tfoot>
                </table>
             </div>
             <!-- Pagination -->
             <div class="px-4 py-3 bg-white border-t border-gray-200">
                {{ $laborEntries->appends(['articles_page' => request()->articles_page, 'output_page' => request()->output_page])->links() }}
             </div>
          </div>
       </div>
    </div>
    @else
    <!-- Affichage pour production terminée SIMPLIFIÉ -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
       <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
             <thead class="bg-gray-50">
                <tr>
                   <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ouvriers</th>
                   <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix/Ouvrier</th>
                   <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                   <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                </tr>
             </thead>
             <tbody class="bg-white divide-y divide-gray-200">
                @forelse($laborEntries as $labor)
                <tr>
                   <td class="px-4 py-3 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ $labor->workers_count }}</div>
                   </td>
                   <td class="px-4 py-3 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ number_format($labor->worker_price, 2, ',', ' ') }}</div>
                   </td>
                   <td class="px-4 py-3 max-w-xs">
                      <div class="text-sm text-gray-900 truncate" title="{{ $labor->description }}">
                         {{ $labor->description ?: '-' }}
                      </div>
                   </td>
                   <td class="px-4 py-3 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">
                         {{ number_format($labor->workers_count * $labor->worker_price, 2, ',', ' ') }}
                      </div>
                   </td>
                </tr>
                @empty
                <tr>
                   <td colspan="4" class="px-4 py-3 text-center text-gray-500">
                      Aucune main d'œuvre déclarée pour cette production
                   </td>
                </tr>
                @endforelse
             </tbody>
             <tfoot class="bg-gray-50">
                <tr>
                   <td colspan="3" class="px-4 py-3 text-right font-medium text-gray-700">Total :</td>
                   <td class="px-4 py-3 whitespace-nowrap">
                      <div class="text-sm font-bold text-gray-900">
                         {{ number_format($laborEntries->sum(function($l) { 
                         return $l->workers_count * $l->worker_price; 
                         }), 2, ',', ' ') }}
                      </div>
                   </td>
                </tr>
             </tfoot>
          </table>
       </div>
    </div>
    @endif
 </div>
 
 <!-- Modal d'édition SIMPLIFIÉE -->
 <div id="editLaborModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
       <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
       <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
       <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
             <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Modifier la main d'œuvre</h3>
             <form id="editLaborForm" class="space-y-4">
                @csrf
                <input type="hidden" id="edit_labor_id" name="id">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                   <div>
                      <label for="edit_workers_count" class="block text-sm font-medium text-gray-700 mb-1">
                         Nombre d'ouvriers <span class="text-red-500">*</span>
                      </label>
                      <input type="number" id="edit_workers_count" name="workers_count" min="1" step="1" 
                             class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                      <div id="edit_workers_count_error" class="text-red-500 text-xs mt-1 hidden"></div>
                   </div>
                   
                   <div>
                      <label for="edit_worker_price" class="block text-sm font-medium text-gray-700 mb-1">
                         Prix par ouvrier <span class="text-red-500">*</span>
                      </label>
                      <input type="number" id="edit_worker_price" name="worker_price" min="0.01" step="0.01" 
                             class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                      <div id="edit_worker_price_error" class="text-red-500 text-xs mt-1 hidden"></div>
                   </div>
                </div>
                
                <div>
                   <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-1">
                      Description
                   </label>
                   <textarea id="edit_description" name="description" rows="3" 
                             class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                             placeholder="Précisez le type de travail, les tâches effectuées, etc."></textarea>
                   <div class="text-xs text-gray-500 mt-1">Description du travail effectué (facultatif)</div>
                   <div id="edit_description_error" class="text-red-500 text-xs mt-1 hidden"></div>
                </div>
             </form>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
             <button type="button" id="saveLaborButton" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                Enregistrer
             </button>
             <button type="button" id="cancelLaborEditButton" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                Annuler
             </button>
          </div>
       </div>
    </div>
 </div>
   <!-- Section 3: Quantité obtenue après production -->
   <div class="bg-white rounded-lg shadow-sm p-6">
      <h3 class="text-lg font-bold text-gray-800 mb-4">3. Quantité obtenue après production</h3>
      @if($production->status == 'en cours')
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
         <!-- Formulaire d'ajout de cartons produits -->
         <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="font-medium text-gray-700 mb-3">Déclarer la production obtenue</h4>
            <form id="addOutputForm" class="space-y-4">
               @csrf
               <input type="hidden" name="production_id" value="{{ $production->id }}">
               <div>
                  <label for="carton_type" class="block text-sm font-medium text-gray-700 mb-1">Type de carton <span class="text-red-500">*</span></label>
                  <select id="carton_type" name="carton_type" class="form-select-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                     <option value="">-- Sélectionner un type --</option>
                     <option value="petit">Petit</option>
                     <option value="grand">Grand</option>
                  </select>
                  <div id="carton_type_error" class="text-red-500 text-xs mt-1 hidden"></div>
               </div>
               <div>
                  <label for="carton_count" class="block text-sm font-medium text-gray-700 mb-1">Nombre de cartons <span class="text-red-500">*</span></label>
                  <input type="number" id="carton_count" name="carton_count" min="1" step="1" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                  <div id="carton_count_error" class="text-red-500 text-xs mt-1 hidden"></div>
               </div>
               <div>
                  <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center">
                  <i class="fas fa-plus-circle mr-2"></i> Ajouter à la production
                  </button>
               </div>
            </form>
         </div>
         <!-- Liste des cartons produits -->
         <div>
            <h4 class="font-medium text-gray-700 mb-3">Production obtenue</h4>
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
               <div class="overflow-x-auto">
                  <table class="min-w-full divide-y divide-gray-200" id="outputTable">
                     <thead class="bg-gray-50">
                        <tr>
                           <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type de carton</th>
                           <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                           <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                     </thead>
                     <tbody class="bg-white divide-y divide-gray-200" id="outputTableBody">
                        @forelse($outputEntries as $output)
                        <tr data-id="{{ $output->id }}">
                           <td class="px-4 py-3 whitespace-nowrap">
                              <div class="text-sm font-medium text-gray-900">{{ ucfirst($output->carton_type) }}</div>
                           </td>
                           <td class="px-4 py-3 whitespace-nowrap">
                              <div class="text-sm text-gray-900">{{ $output->carton_count }}</div>
                           </td>
                           <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                              <button class="edit-output text-blue-600 hover:text-blue-900 mx-1" 
                                 data-id="{{ $output->id }}"
                                 data-carton-type="{{ $output->carton_type }}"
                                 data-carton-count="{{ $output->carton_count }}">
                              <i class="fas fa-edit"></i>
                              </button>
                              <button class="delete-output text-red-600 hover:text-red-900 mx-1" 
                                 data-id="{{ $output->id }}">
                              <i class="fas fa-trash"></i>
                              </button>
                           </td>
                        </tr>
                        @empty
                        <tr id="noOutputRow">
                           <td colspan="3" class="px-4 py-3 text-center text-gray-500">
                              Aucune production obtenue déclarée
                           </td>
                        </tr>
                        @endforelse
                     </tbody>
                     <tfoot class="bg-gray-50">
                        <tr>
                           <td class="px-4 py-3 text-right font-medium text-gray-700">Total :</td>
                           <td class="px-4 py-3 whitespace-nowrap">
                              <div class="text-sm font-bold text-gray-900" id="outputTotal">
                                 {{ $outputEntries->sum('carton_count') }}
                              </div>
                           </td>
                           <td></td>
                        </tr>
                     </tfoot>
                  </table>
               </div>
               <!-- Ajouter la pagination -->
               <div class="px-4 py-3 bg-white border-t border-gray-200">
                  {{ $outputEntries->appends(['articles_page' => request()->articles_page, 'labor_page' => request()->labor_page])->links() }}
               </div>
            </div>
         </div>
      </div>
      @else
      <!-- Affichage des cartons produits pour une production terminée -->
      <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
         <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
               <thead class="bg-gray-50">
                  <tr>
                     <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type de carton</th>
                     <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                  </tr>
               </thead>
               <tbody class="bg-white divide-y divide-gray-200">
                  @forelse($outputEntries as $output)
                  <tr>
                     <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ ucfirst($output->carton_type) }}</div>
                     </td>
                     <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $output->carton_count }}</div>
                     </td>
                  </tr>
                  @empty
                  <tr>
                     <td colspan="2" class="px-4 py-3 text-center text-gray-500">
                        Aucune production obtenue déclarée
                     </td>
                  </tr>
                  @endforelse
               </tbody>
               <tfoot class="bg-gray-50">
                  <tr>
                     <td class="px-4 py-3 text-right font-medium text-gray-700">Total :</td>
                     <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900">
                           {{ $outputEntries->sum('carton_count') }}
                        </div>
                     </td>
                  </tr>
               </tfoot>
            </table>
         </div>
      </div>
      @endif
   </div>
   <!-- Bouton de finalisation -->
   @if($production->status == 'en cours')
   <div class="bg-white rounded-lg shadow-sm p-6 text-center">
      <div class="text-gray-600 mb-6">
         <p class="font-medium">La validation de la production entraînera :</p>
         <ul class="mt-2 text-sm list-disc list-inside">
            <li>La déduction des articles utilisés du stock</li>
            <li>Le changement du statut de la production à "Terminé"</li>
            <li>La validation définitive de toutes les informations déclarées</li>
         </ul>
      </div>
      <button id="verifyButton" class="px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center mx-auto">
      <i class="fas fa-check-circle mr-2"></i> Vérifier et finaliser la production
      </button>
   </div>
   @endif
</div>
<!-- Modals pour l'édition -->
<div id="editArticleModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
   <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
         <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Modifier l'article utilisé</h3>
            <form id="editArticleForm" class="space-y-4">
               @csrf
               <input type="hidden" id="edit_article_id" name="id">
               <div>
                  <label for="edit_article_select" class="block text-sm font-medium text-gray-700 mb-1">Article <span class="text-red-500">*</span></label>
                  <select id="edit_article_select" name="article_id" class="form-select-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                     <option value="">-- Sélectionner un article --</option>
                     @foreach($articles as $article)
                     <option value="{{ $article->id }}" data-stock="{{ $article->stock_quantity }}">
                        {{ $article->name }} (Stock: {{ $article->stock_quantity }})
                     </option>
                     @endforeach
                  </select>
                  <div id="edit_article_error" class="text-red-500 text-xs mt-1 hidden"></div>
               </div>
               <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                     <label for="edit_quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantité <span class="text-red-500">*</span></label>
                     <input type="number" id="edit_quantity" name="quantity" min="0.01" step="0.01" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                     <div id="edit_quantity_error" class="text-red-500 text-xs mt-1 hidden"></div>
                  </div>
                  <div>
                     <label for="edit_unit_price" class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire <span class="text-red-500">*</span></label>
                     <input type="number" id="edit_unit_price" name="unit_price" min="0.01" step="0.01" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                     <div id="edit_unit_price_error" class="text-red-500 text-xs mt-1 hidden"></div>
                  </div>
               </div>
            </form>
         </div>
         <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="button" id="saveArticleButton" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
            Enregistrer
            </button>
            <button type="button" id="cancelArticleEditButton" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
            Annuler
            </button>
         </div>
      </div>
   </div>
</div>
{{-- 
<div id="editLaborModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
   <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
         <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Modifier la main d'œuvre</h3>
            <form id="editLaborForm" class="space-y-4">
               @csrf
               <input type="hidden" id="edit_labor_id" name="id">
               <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                     <label for="edit_workers_count" class="block text-sm font-medium text-gray-700 mb-1">Nombre d'ouvriers <span class="text-red-500">*</span></label>
                     <input type="number" id="edit_workers_count" name="workers_count" min="1" step="1" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                     <div id="edit_workers_count_error" class="text-red-500 text-xs mt-1 hidden"></div>
                  </div>
                  <div>
                     <label for="edit_worker_price" class="block text-sm font-medium text-gray-700 mb-1">Prix par ouvrier <span class="text-red-500">*</span></label>
                     <input type="number" id="edit_worker_price" name="worker_price" min="0.01" step="0.01" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                     <div id="edit_worker_price_error" class="text-red-500 text-xs mt-1 hidden"></div>
                  </div>
               </div>
            </form>
         </div>
         <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="button" id="saveLaborButton" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
            Enregistrer
            </button>
            <button type="button" id="cancelLaborEditButton" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
            Annuler
            </button>
         </div>
      </div>
   </div>
</div>
--}}
<div id="editLaborModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
   <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
         <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Modifier la main d'œuvre</h3>
            <form id="editLaborForm" class="space-y-4">
               @csrf
               <input type="hidden" id="edit_labor_id" name="id">
               <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                     <label for="edit_workers_count" class="block text-sm font-medium text-gray-700 mb-1">Nombre d'ouvriers <span class="text-red-500">*</span></label>
                     <input type="number" id="edit_workers_count" name="workers_count" min="1" step="1" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                     <div id="edit_workers_count_error" class="text-red-500 text-xs mt-1 hidden"></div>
                  </div>
                  <div>
                     <label for="edit_worker_price" class="block text-sm font-medium text-gray-700 mb-1">Prix par ouvrier <span class="text-red-500">*</span></label>
                     <input type="number" id="edit_worker_price" name="worker_price" min="0.01" step="0.01" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                     <div id="edit_worker_price_error" class="text-red-500 text-xs mt-1 hidden"></div>
                  </div>
               </div>
               <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                     <label for="edit_additional_labor" class="block text-sm font-medium text-gray-700 mb-1">Main d'œuvre supplémentaire</label>
                     <textarea id="edit_additional_labor" name="additional_labor" rows="2" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="Précisez ici tout travail supplémentaire effectué"></textarea>
                     <div class="text-xs text-gray-500 mt-1">Description du travail supplémentaire (facultatif)</div>
                     <div id="edit_additional_labor_error" class="text-red-500 text-xs mt-1 hidden"></div>
                  </div>
                  <div>
                     <label for="edit_additional_cost" class="block text-sm font-medium text-gray-700 mb-1">Montant supplémentaire</label>
                     <input type="number" id="edit_additional_cost" name="additional_cost" min="0" step="0.01" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="0.00">
                     <div class="text-xs text-gray-500 mt-1">Coût supplémentaire (facultatif)</div>
                     <div id="edit_additional_cost_error" class="text-red-500 text-xs mt-1 hidden"></div>
                  </div>
               </div>
            </form>
         </div>
         <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="button" id="saveLaborButton" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
            Enregistrer
            </button>
            <button type="button" id="cancelLaborEditButton" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
            Annuler
            </button>
         </div>
      </div>
   </div>
</div>
<div id="editOutputModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
   <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
         <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Modifier la production obtenue</h3>
            <form id="editOutputForm" class="space-y-4">
               @csrf
               <input type="hidden" id="edit_output_id" name="id">
               <div>
                  <label for="edit_carton_type" class="block text-sm font-medium text-gray-700 mb-1">Type de carton <span class="text-red-500">*</span></label>
                  <select id="edit_carton_type" name="carton_type" class="form-select-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                     <option value="">-- Sélectionner un type --</option>
                     <option value="petit">Petit</option>
                     <option value="grand">Grand</option>
                  </select>
                  <div id="edit_carton_type_error" class="text-red-500 text-xs mt-1 hidden"></div>
               </div>
               <div>
                  <label for="edit_carton_count" class="block text-sm font-medium text-gray-700 mb-1">Nombre de cartons <span class="text-red-500">*</span></label>
                  <input type="number" id="edit_carton_count" name="carton_count" min="1" step="1" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                  <div id="edit_carton_count_error" class="text-red-500 text-xs mt-1 hidden"></div>
               </div>
            </form>
         </div>
         <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="button" id="saveOutputButton" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
            Enregistrer
            </button>
            <button type="button" id="cancelOutputEditButton" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
            Annuler
            </button>
         </div>
      </div>
   </div>
</div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
   document.addEventListener('DOMContentLoaded', function() {
       // ==================== GESTION DES ARTICLES ====================
   
       updateArticleSelector();
   
       // Après une opération réussie (ajout, modification, suppression), recharger la page pour actualiser la pagination
       function reloadCurrentPage() {
           window.location.reload();
       }
       
       // Formulaire d'ajout d'article
       const addArticleForm = document.getElementById('addArticleForm');
            if (addArticleForm) {
           
   
           // Fonction d'ajout d'article
           addArticleForm.addEventListener('submit', function(e) {
               e.preventDefault();
               
               // Réinitialiser les erreurs
               resetFormErrors('article');
               
               // Vérification de la disponibilité du stock
               const articleSelect = document.getElementById('article_id');
               const quantity = parseFloat(document.getElementById('quantity').value);
               
               if (articleSelect.selectedIndex > 0) {
                   const selectedOption = articleSelect.options[articleSelect.selectedIndex];
                   const availableStock = parseFloat(selectedOption.getAttribute('data-stock'));
                   
                   if (quantity > availableStock) {
                       showError('quantity_error', `Stock insuffisant. Disponible: ${availableStock}`);
                       return;
                   }
               }
               
               // Envoi de la requête AJAX
               const formData = new FormData(addArticleForm);
               
               // Déboggage - Afficher les données qui seront envoyées
               console.log("Données du formulaire:", Object.fromEntries(formData));
               
               fetch('/production-articles', {
                   method: 'POST',
                   body: formData,
                   headers: {
                       'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                       'X-Requested-With': 'XMLHttpRequest'
                   }
               })
               .then(response => {
                   // Déboggage - Afficher le statut de la réponse
                   console.log("Statut de la réponse:", response.status);
                   return response.json();
               })
               .then(data => {
                   // Déboggage - Afficher les données reçues
                   console.log("Données reçues:", data);
                   
                   // Si le serveur indique un succès
                   if (data.success === true) {
                       reloadCurrentPage();
                       // Ajouter la ligne au tableau
                       updateArticlesTable(data.article);
                       addArticleForm.reset();
                       
                       // Afficher un message de succès
                       Swal.fire({
                           icon: 'success',
                           title: 'Succès',
                           text: data.message || 'Article ajouté avec succès',
                           toast: true,
                           position: 'top-end',
                           showConfirmButton: false,
                           timer: 3000
                       });
                   } else {
                       // Si le serveur indique un échec
                       if (data.errors) {
                           // Afficher les erreurs de validation
                           Object.keys(data.errors).forEach(key => {
                               showError(`${key}_error`, data.errors[key][0]);
                           });
                       } else {
                           // Afficher un message d'erreur générique
                           Swal.fire({
                               icon: 'error',
                               title: 'Erreur',
                               text: data.message || 'Une erreur est survenue',
                               toast: true,
                               position: 'top-end',
                               showConfirmButton: false,
                               timer: 3000
                           });
                       }
                   }
               })
               .catch(error => {
                   // Déboggage - Afficher l'erreur complète
                   console.error("Erreur complète:", error);
                   
                   // Afficher un message d'erreur
                   Swal.fire({
                       icon: 'error',
                       title: 'Erreur',
                       text: 'Une erreur est survenue lors de la communication avec le serveur',
                       toast: true,
                       position: 'top-end',
                       showConfirmButton: false,
                       timer: 3000
                   });
               });
           });
       }
   
       
   // Fonction pour mettre à jour le sélecteur d'articles
   function updateArticleSelector() {
       const articleSelect = document.getElementById('article_id');
       const usedArticleIds = Array.from(document.querySelectorAll('#articlesTableBody tr:not(#noArticlesRow)'))
           .map(row => row.getAttribute('data-id') ? 
               document.querySelector(`#articlesTableBody tr[data-id="${row.getAttribute('data-id')}"] button.edit-article`).getAttribute('data-article-id') : 
               null)
           .filter(id => id !== null);
       
       // Parcourir toutes les options et désactiver celles déjà utilisées
       Array.from(articleSelect.options).forEach(option => {
           if (option.value && usedArticleIds.includes(option.value)) {
               option.disabled = true;
               const originalText = option.textContent.split(' (Déjà ajouté)')[0];
               option.textContent = `${originalText} (Déjà ajouté)`;
           } else if (option.value) {
               option.disabled = false;
               option.textContent = option.textContent.split(' (Déjà ajouté)')[0];
           }
       });
       
       // Réinitialiser la sélection
       articleSelect.selectedIndex = 0;
   }
    
   
       
       // Mise à jour du tableau des articles
       function updateArticlesTable(article) {
           const tableBody = document.getElementById('articlesTableBody');
           const noArticlesRow = document.getElementById('noArticlesRow');
           
           if (noArticlesRow) {
               noArticlesRow.remove();
           }
           
           // Vérifier si l'article existe déjà (mise à jour)
           const existingRow = document.querySelector(`#articlesTableBody tr[data-id="${article.id}"]`);
           
           if (existingRow) {
               // Mettre à jour la ligne existante
               existingRow.innerHTML = createArticleRowHtml(article);
           } else {
               // Créer une nouvelle ligne
               const newRow = document.createElement('tr');
               newRow.setAttribute('data-id', article.id);
               newRow.innerHTML = createArticleRowHtml(article);
               tableBody.appendChild(newRow);
           }
           
           // Mettre à jour le total
           updateArticlesTotal();
   
           // Mettre à jour le sélecteur d'articles
           updateArticleSelector();
       }
       
       
       
   
       function createArticleRowHtml(article) {
       // Convertir les valeurs en nombres
       const quantity = parseFloat(article.quantity);
       const unitPrice = parseFloat(article.unit_price);
       const total = (quantity * unitPrice).toFixed(2).replace('.', ',');
       
       return `
           <td class="px-4 py-3 whitespace-nowrap">
               <div class="text-sm font-medium text-gray-900">${article.article_name}</div>
           </td>
           <td class="px-4 py-3 whitespace-nowrap">
               <div class="text-sm text-gray-900">${quantity.toFixed(2).replace('.', ',')}</div>
           </td>
           <td class="px-4 py-3 whitespace-nowrap">
               <div class="text-sm text-gray-900">${unitPrice.toFixed(2).replace('.', ',')}</div>
           </td>
           <td class="px-4 py-3 whitespace-nowrap">
               <div class="text-sm font-medium text-gray-900">${total}</div>
           </td>
           <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
               <button class="edit-article text-blue-600 hover:text-blue-900 mx-1" 
                       data-id="${article.id}"
                       data-article-id="${article.article_id}"
                       data-quantity="${article.quantity}"
                       data-unit-price="${article.unit_price}">
                   <i class="fas fa-edit"></i>
               </button>
               <button class="delete-article text-red-600 hover:text-red-900 mx-1" 
                       data-id="${article.id}">
                   <i class="fas fa-trash"></i>
               </button>
           </td>
       `;
   }
       
       // Mise à jour du total des articles
       function updateArticlesTotal() {
           const total = Array.from(document.querySelectorAll('#articlesTableBody tr:not(#noArticlesRow)'))
               .reduce((sum, row) => {
                   const quantityText = row.querySelector('td:nth-child(2) div').textContent.replace(',', '.');
                   const priceText = row.querySelector('td:nth-child(3) div').textContent.replace(',', '.');
                   const quantity = parseFloat(quantityText);
                   const price = parseFloat(priceText);
                   return sum + (quantity * price);
               }, 0);
               
           const totalElement = document.getElementById('articlesTotal');
           if (totalElement) {
               totalElement.textContent = total.toFixed(2).replace('.', ',');
           }
       }
       
       // Édition d'un article
       document.addEventListener('click', function(e) {
           if (e.target.closest('.edit-article')) {
               const button = e.target.closest('.edit-article');
               const id = button.getAttribute('data-id');
               const articleId = button.getAttribute('data-article-id');
               const quantity = button.getAttribute('data-quantity');
               const unitPrice = button.getAttribute('data-unit-price');
               
               // Remplir le formulaire d'édition
               document.getElementById('edit_article_id').value = id;
               const articleSelect = document.getElementById('edit_article_select');
               articleSelect.value = articleId;
               document.getElementById('edit_quantity').value = quantity;
               document.getElementById('edit_unit_price').value = unitPrice;
               
               // Afficher la modal
               document.getElementById('editArticleModal').classList.remove('hidden');
           }
       });
       
       // Fermer la modal d'édition d'article
       document.getElementById('cancelArticleEditButton').addEventListener('click', function() {
           document.getElementById('editArticleModal').classList.add('hidden');
       });
       
       // Enregistrer les modifications d'un article
       document.getElementById('saveArticleButton').addEventListener('click', function() {
           // Réinitialiser les erreurs
           resetFormErrors('edit_article');
           
           // Vérification de la disponibilité du stock
           const articleSelect = document.getElementById('edit_article_select');
           const quantity = parseFloat(document.getElementById('edit_quantity').value);
           
           if (articleSelect.selectedIndex > 0) {
               const selectedOption = articleSelect.options[articleSelect.selectedIndex];
               const availableStock = parseFloat(selectedOption.getAttribute('data-stock'));
               
               if (quantity > availableStock) {
                   showError('edit_quantity_error', `Stock insuffisant. Disponible: ${availableStock}`);
                   return;
               }
           }
           
           // Récupérer les données du formulaire
           const formData = new FormData(document.getElementById('editArticleForm'));
           const id = document.getElementById('edit_article_id').value;
           
           // Envoi de la requête AJAX
           fetch(`/production-articles/${id}`, {
               method: 'POST',
               body: formData,
               headers: {
                   'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                   'X-Requested-With': 'XMLHttpRequest',
                   'X-HTTP-Method-Override': 'PUT'
               }
           })
           .then(response => {
               if (!response.ok) {
                   throw response;
               }
               return response.json();
           })
           .then(data => {
               if (data.success) {
                   reloadCurrentPage();
                   // Mettre à jour la ligne dans le tableau
                   updateArticlesTable(data.article);
                   
                   // Fermer la modal
                   document.getElementById('editArticleModal').classList.add('hidden');
                   
                   // Afficher un message de succès
                   Swal.fire({
                       icon: 'success',
                       title: 'Succès',
                       text: 'Article modifié avec succès',
                       toast: true,
                       position: 'top-end',
                       showConfirmButton: false,
                       timer: 3000
                   });
               }
           })
           .catch(error => {
               if (error.status === 422) {
                   error.json().then(errData => {
                       // Afficher les erreurs de validation
                       if (errData.errors) {
                           Object.keys(errData.errors).forEach(key => {
                               showError(`edit_${key}_error`, errData.errors[key][0]);
                           });
                       }
                   });
               } else {
                   // Afficher une erreur générique
                   Swal.fire({
                       icon: 'error',
                       title: 'Erreur',
                       text: 'Une erreur est survenue. Veuillez réessayer.',
                       toast: true,
                       position: 'top-end',
                       showConfirmButton: false,
                       timer: 3000
                   });
               }
           });
       });
       
       // Suppression d'un article
       document.addEventListener('click', function(e) {
           if (e.target.closest('.delete-article')) {
               const button = e.target.closest('.delete-article');
               const id = button.getAttribute('data-id');
               
               Swal.fire({
                   title: 'Confirmation',
                   text: 'Êtes-vous sûr de vouloir supprimer cet article ?',
                   icon: 'warning',
                   showCancelButton: true,
                   confirmButtonText: 'Supprimer',
                   cancelButtonText: 'Annuler',
                   confirmButtonColor: '#ef4444',
                   cancelButtonColor: '#6b7280'
               }).then((result) => {
                   if (result.isConfirmed) {
                       // Envoi de la requête AJAX
                       fetch(`/production-articles/${id}`, {
                           method: 'DELETE',
                           headers: {
                               'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                               'X-Requested-With': 'XMLHttpRequest'
                           }
                       })
                       .then(response => response.json())
                       .then(data => {
                           if (data.success) {
                               // Supprimer la ligne du tableau
                               const row = document.querySelector(`#articlesTableBody tr[data-id="${id}"]`);
                               if (row) {
                                   row.remove();
                               }
                               
                               // Vérifier si le tableau est vide
                               if (document.querySelectorAll('#articlesTableBody tr').length === 0) {
                                   const tableBody = document.getElementById('articlesTableBody');
                                   const emptyRow = document.createElement('tr');
                                   emptyRow.id = 'noArticlesRow';
                                   emptyRow.innerHTML = `
                                       <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                           Aucun article déclaré pour cette production
                                       </td>
                                   `;
                                   tableBody.appendChild(emptyRow);
                               }
   
                               reloadCurrentPage();
                               
                               // Mettre à jour le total
                               updateArticlesTotal();
   
                                // Mettre à jour le sélecteur d'articles
                                updateArticleSelector();
                               
                               // Afficher un message de succès
                               Swal.fire({
                                   icon: 'success',
                                   title: 'Succès',
                                   text: 'Article supprimé avec succès',
                                   toast: true,
                                   position: 'top-end',
                                   showConfirmButton: false,
                                   timer: 3000
                               });
                           } else {
                               Swal.fire({
                                   icon: 'error',
                                   title: 'Erreur',
                                   text: data.message || 'Une erreur est survenue',
                                   toast: true,
                                   position: 'top-end',
                                   showConfirmButton: false,
                                   timer: 3000
                               });
                           }
                       })
                       .catch(error => {
                           console.error('Erreur:', error);
                           Swal.fire({
                               icon: 'error',
                               title: 'Erreur',
                               text: 'Une erreur est survenue lors de la communication avec le serveur',
                               toast: true,
                               position: 'top-end',
                               showConfirmButton: false,
                               timer: 3000
                           });
                       });
                   }
               });
           }
       });
       
       // ==================== GESTION DE LA MAIN D'ŒUVRE ====================
       
//        // Formulaire d'ajout de main d'œuvre
//        const addLaborForm = document.getElementById('addLaborForm');
//        if (addLaborForm) {
//            addLaborForm.addEventListener('submit', function(e) {
//                e.preventDefault();
               
//                // Réinitialiser les erreurs
//                resetFormErrors('labor');
               
//                // Envoi de la requête AJAX
//                const formData = new FormData(addLaborForm);
               
//                fetch('/production-labor', {
//                    method: 'POST',
//                    body: formData,
//                    headers: {
//                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
//                        'X-Requested-With': 'XMLHttpRequest'
//                    }
//                })
//                .then(response => {
//                    if (!response.ok) {
//                        throw response;
//                    }
//                    return response.json();
//                })
//                .then(data => {
//                    if (data.success) {
//                        reloadCurrentPage();
//                        // Ajouter la ligne au tableau ou recharger la page
//                        updateLaborTable(data.labor);
//                        addLaborForm.reset();
                       
//                        // Afficher un message de succès
//                        Swal.fire({
//                            icon: 'success',
//                            title: 'Succès',
//                            text: 'Main d\'œuvre ajoutée avec succès',
//                            toast: true,
//                            position: 'top-end',
//                            showConfirmButton: false,
//                            timer: 3000
//                        });
//                    }
//                })
//                .catch(error => {
//                    if (error.status === 422) {
//                        error.json().then(errData => {
//                            // Afficher les erreurs de validation
//                            if (errData.errors) {
//                                Object.keys(errData.errors).forEach(key => {
//                                    showError(`${key}_error`, errData.errors[key][0]);
//                                });
//                            }
//                        });
//                    } else {
//                        // Afficher une erreur générique
//                        Swal.fire({
//                            icon: 'error',
//                            title: 'Erreur',
//                            text: 'Une erreur est survenue. Veuillez réessayer.',
//                            toast: true,
//                            position: 'top-end',
//                            showConfirmButton: false,
//                            timer: 3000
//                        });
//                    }
//                });
//            });
//        }
       
//        // Mise à jour du tableau de la main d'œuvre
//        function updateLaborTable(labor) {
//            const tableBody = document.getElementById('laborTableBody');
//            const noLaborRow = document.getElementById('noLaborRow');
           
//            if (noLaborRow) {
//                noLaborRow.remove();
//            }
           
//            // Vérifier si la ligne existe déjà (mise à jour)
//            const existingRow = document.querySelector(`#laborTableBody tr[data-id="${labor.id}"]`);
           
//            if (existingRow) {
//                // Mettre à jour la ligne existante
//                existingRow.innerHTML = createLaborRowHtml(labor);
//            } else {
//                // Créer une nouvelle ligne
//                const newRow = document.createElement('tr');
//                newRow.setAttribute('data-id', labor.id);
//                newRow.innerHTML = createLaborRowHtml(labor);
//                tableBody.appendChild(newRow);
//            }
           
//            // Mettre à jour le total
//            updateLaborTotal();
//        }
       
       
   
//        // Mise à jour de la fonction HTML pour le tableau de main d'œuvre
//    function createLaborRowHtml(labor) {
//        // Convertir les valeurs en nombres
//        const workersCount = parseInt(labor.workers_count);
//        const workerPrice = parseFloat(labor.worker_price);
//        const additionalCost = labor.additional_cost ? parseFloat(labor.additional_cost) : 0;
       
//        // Calculer le total
//        const total = (workersCount * workerPrice + additionalCost).toFixed(2).replace('.', ',');
       
//        // Formater les valeurs pour l'affichage
//        const formattedWorkerPrice = workerPrice.toFixed(2).replace('.', ',');
//        const formattedAdditionalCost = labor.additional_cost ? parseFloat(labor.additional_cost).toFixed(2).replace('.', ',') : '-';
//        const additionalLabor = labor.additional_labor || '-';
       
//        return `
//            <td class="px-4 py-3 whitespace-nowrap">
//                <div class="text-sm text-gray-900">${workersCount}</div>
//            </td>
//            <td class="px-4 py-3 whitespace-nowrap">
//                <div class="text-sm text-gray-900">${formattedWorkerPrice}</div>
//            </td>
//            <td class="px-4 py-3 whitespace-nowrap">
//                <div class="text-sm text-gray-900">${formattedAdditionalCost}</div>
//            </td>
//            <td class="px-4 py-3">
//                <div class="text-sm text-gray-900 line-clamp-2" title="${additionalLabor}">
//                    ${additionalLabor}
//                </div>
//            </td>
//            <td class="px-4 py-3 whitespace-nowrap">
//                <div class="text-sm font-medium text-gray-900">${total}</div>
//            </td>
//            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
//                <button class="edit-labor text-blue-600 hover:text-blue-900 mx-1" 
//                        data-id="${labor.id}"
//                        data-workers-count="${labor.workers_count}"
//                        data-worker-price="${labor.worker_price}"
//                        data-additional-cost="${labor.additional_cost || ''}"
//                        data-additional-labor="${labor.additional_labor || ''}">
//                    <i class="fas fa-edit"></i>
//                </button>
//                <button class="delete-labor text-red-600 hover:text-red-900 mx-1" 
//                        data-id="${labor.id}">
//                    <i class="fas fa-trash"></i>
//                </button>
//            </td>
//        `;
//    }
   
//    // Mise à jour du total de la main d'œuvre
//    function updateLaborTotal() {
//        const total = Array.from(document.querySelectorAll('#laborTableBody tr:not(#noLaborRow)'))
//            .reduce((sum, row) => {
//                const countText = row.querySelector('td:nth-child(1) div').textContent;
//                const priceText = row.querySelector('td:nth-child(2) div').textContent.replace(',', '.');
//                const additionalCostText = row.querySelector('td:nth-child(3) div').textContent;
               
//                const count = parseInt(countText);
//                const price = parseFloat(priceText);
//                const additionalCost = additionalCostText !== '-' ? parseFloat(additionalCostText.replace(',', '.')) : 0;
               
//                return sum + (count * price) + additionalCost;
//            }, 0);
           
//        const totalElement = document.getElementById('laborTotal');
//        if (totalElement) {
//            totalElement.textContent = total.toFixed(2).replace('.', ',');
//        }
//    }
   
//    // Mise à jour de l'événement de clic pour l'édition de main d'œuvre
//    document.addEventListener('click', function(e) {
//        if (e.target.closest('.edit-labor')) {
//            const button = e.target.closest('.edit-labor');
//            const id = button.getAttribute('data-id');
//            const workersCount = button.getAttribute('data-workers-count');
//            const workerPrice = button.getAttribute('data-worker-price');
//            const additionalCost = button.getAttribute('data-additional-cost');
//            const additionalLabor = button.getAttribute('data-additional-labor');
           
//            // Remplir le formulaire d'édition
//            document.getElementById('edit_labor_id').value = id;
//            document.getElementById('edit_workers_count').value = workersCount;
//            document.getElementById('edit_worker_price').value = workerPrice;
//            document.getElementById('edit_additional_cost').value = additionalCost || '';
//            document.getElementById('edit_additional_labor').value = additionalLabor || '';
           
//            // Afficher la modal
//            document.getElementById('editLaborModal').classList.remove('hidden');
//        }
//    });
       
//        // Fermer la modal d'édition de main d'œuvre
//        document.getElementById('cancelLaborEditButton').addEventListener('click', function() {
//            document.getElementById('editLaborModal').classList.add('hidden');
//        });
       
       
   
//    // Enregistrer les modifications de main d'œuvre
//    document.getElementById('saveLaborButton').addEventListener('click', function() {
//        // Réinitialiser les erreurs
//        resetFormErrors('edit_labor');
       
//        // Récupérer les données du formulaire
//        const formData = new FormData(document.getElementById('editLaborForm'));
//        const id = document.getElementById('edit_labor_id').value;
       
//        console.log('Données envoyées:', Object.fromEntries(formData));
       
//        // Envoi de la requête AJAX
//        fetch(`/production-labor/${id}`, {
//            method: 'POST',
//            body: formData,
//            headers: {
//                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
//                'X-Requested-With': 'XMLHttpRequest',
//                'X-HTTP-Method-Override': 'PUT'
//            }
//        })
//        .then(response => {
//            console.log('Statut de la réponse:', response.status);
//            return response.json();
//        })
//        .then(data => {
//            console.log('Données reçues:', data);
           
//            // Vérifier que data.success est bien true
//            if (data.success === true) {
//                // Si l'objet labor existe dans la réponse
//                if (data.labor) {
//                    reloadCurrentPage();
//                    // Mettre à jour la ligne dans le tableau
//                    updateLaborTable(data.labor);
                   
//                    // Fermer la modal
//                    document.getElementById('editLaborModal').classList.add('hidden');
                   
//                    // Afficher un message de succès
//                    Swal.fire({
//                        icon: 'success',
//                        title: 'Succès',
//                        text: data.message || 'Main d\'œuvre modifiée avec succès',
//                        toast: true,
//                        position: 'top-end',
//                        showConfirmButton: false,
//                        timer: 3000
//                    });
//                } else {
//                    console.warn('Données labor manquantes dans la réponse:', data);
//                    // Recharger la page pour actualiser les données
//                    window.location.reload();
//                }
//            } else {
//                // Afficher un message d'erreur explicite
//                Swal.fire({
//                    icon: 'error',
//                    title: 'Erreur',
//                    text: data.message || 'Une erreur est survenue lors de la modification',
//                    toast: true,
//                    position: 'top-end',
//                    showConfirmButton: false,
//                    timer: 3000
//                });
//            }
//        })
//        .catch(error => {
//            console.error('Erreur AJAX:', error);
           
//            // Afficher une erreur générique
//            Swal.fire({
//                icon: 'error',
//                title: 'Erreur',
//                text: 'Une erreur de communication est survenue',
//                toast: true,
//                position: 'top-end',
//                showConfirmButton: false,
//                timer: 3000
//            });
//        });
//    });
       
//        // Suppression de main d'œuvre
//        document.addEventListener('click', function(e) {
//            if (e.target.closest('.delete-labor')) {
//                const button = e.target.closest('.delete-labor');
//                const id = button.getAttribute('data-id');
               
//                Swal.fire({
//                    title: 'Confirmation',
//                    text: 'Êtes-vous sûr de vouloir supprimer cette main d\'œuvre ?',
//                    icon: 'warning',
//                    showCancelButton: true,
//                    confirmButtonText: 'Supprimer',
//                    cancelButtonText: 'Annuler',
//                    confirmButtonColor: '#ef4444',
//                    cancelButtonColor: '#6b7280'
//                }).then((result) => {
//                    if (result.isConfirmed) {
//                        // Envoi de la requête AJAX
//                        fetch(`/production-labor/${id}`, {
//                            method: 'DELETE',
//                            headers: {
//                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
//                                'X-Requested-With': 'XMLHttpRequest'
//                            }
//                        })
//                        .then(response => response.json())
//                        .then(data => {
//                            if (data.success) {
//                                // Supprimer la ligne du tableau
//                                const row = document.querySelector(`#laborTableBody tr[data-id="${id}"]`);
//                                if (row) {
//                                    row.remove();
//                                }
                               
//                                // Vérifier si le tableau est vide
//                                if (document.querySelectorAll('#laborTableBody tr').length === 0) {
//                                    const tableBody = document.getElementById('laborTableBody');
//                                    const emptyRow = document.createElement('tr');
//                                    emptyRow.id = 'noLaborRow';
//                                    emptyRow.innerHTML = `
//                                        <td colspan="4" class="px-4 py-3 text-center text-gray-500">
//                                            Aucune main d'œuvre déclarée pour cette production
//                                        </td>
//                                    `;
//                                    tableBody.appendChild(emptyRow);
//                                }
//                                reloadCurrentPage();
//                                // Mettre à jour le total
//                                updateLaborTotal();
                               
//                                // Afficher un message de succès
//                                Swal.fire({
//                                    icon: 'success',
//                                    title: 'Succès',
//                                    text: 'Main d\'œuvre supprimée avec succès',
//                                    toast: true,
//                                    position: 'top-end',
//                                    showConfirmButton: false,
//                                    timer: 3000
//                                });
//                            } else {
//                                Swal.fire({
//                                    icon: 'error',
//                                    title: 'Erreur',
//                                    text: data.message || 'Une erreur est survenue',
//                                    toast: true,
//                                    position: 'top-end',
//                                    showConfirmButton: false,
//                                    timer: 3000
//                                });
//                            }
//                        })
//                        .catch(error => {
//                            console.error('Erreur:', error);
//                            Swal.fire({
//                                icon: 'error',
//                                title: 'Erreur',
//                                text: 'Une erreur est survenue lors de la communication avec le serveur',
//                                toast: true,
//                                position: 'top-end',
//                                showConfirmButton: false,
//                                timer: 3000
//                            });
//                        });
//                    }
//                });
//            }
//        });


// ==================== GESTION DE LA MAIN D'ŒUVRE (VERSION SIMPLIFIÉE) ====================

// Formulaire d'ajout de main d'œuvre
const addLaborForm = document.getElementById('addLaborForm');
if (addLaborForm) {
    addLaborForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Réinitialiser les erreurs
        resetFormErrors('labor');
        
        // Envoi de la requête AJAX
        const formData = new FormData(addLaborForm);
        
        fetch('/production-labor', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw response;
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                reloadCurrentPage();
                // Ajouter la ligne au tableau
                updateLaborTable(data.labor);
                addLaborForm.reset();
                
                // Afficher un message de succès
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: 'Main d\'œuvre ajoutée avec succès',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        })
        .catch(error => {
            if (error.status === 422) {
                error.json().then(errData => {
                    // Afficher les erreurs de validation
                    if (errData.errors) {
                        Object.keys(errData.errors).forEach(key => {
                            showError(`${key}_error`, errData.errors[key][0]);
                        });
                    }
                });
            } else {
                // Afficher une erreur générique
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue. Veuillez réessayer.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });
    });
}

// Mise à jour du tableau de la main d'œuvre
function updateLaborTable(labor) {
    const tableBody = document.getElementById('laborTableBody');
    const noLaborRow = document.getElementById('noLaborRow');
    
    if (noLaborRow) {
        noLaborRow.remove();
    }
    
    // Vérifier si la ligne existe déjà (mise à jour)
    const existingRow = document.querySelector(`#laborTableBody tr[data-id="${labor.id}"]`);
    
    if (existingRow) {
        // Mettre à jour la ligne existante
        existingRow.innerHTML = createLaborRowHtml(labor);
    } else {
        // Créer une nouvelle ligne
        const newRow = document.createElement('tr');
        newRow.setAttribute('data-id', labor.id);
        newRow.innerHTML = createLaborRowHtml(labor);
        tableBody.appendChild(newRow);
    }
    
    // Mettre à jour le total
    updateLaborTotal();
}

// Création du HTML pour une ligne de main d'œuvre (VERSION SIMPLIFIÉE)
function createLaborRowHtml(labor) {
    // Convertir les valeurs en nombres
    const workersCount = parseInt(labor.workers_count);
    const workerPrice = parseFloat(labor.worker_price);
    
    // Calculer le total (seulement workers_count * worker_price)
    const total = (workersCount * workerPrice).toFixed(2).replace('.', ',');
    
    // Formater les valeurs pour l'affichage
    const formattedWorkerPrice = workerPrice.toFixed(2).replace('.', ',');
    const description = labor.description || '-';
    
    return `
        <td class="px-4 py-3 whitespace-nowrap">
            <div class="text-sm text-gray-900">${workersCount}</div>
        </td>
        <td class="px-4 py-3 whitespace-nowrap">
            <div class="text-sm text-gray-900">${formattedWorkerPrice}</div>
        </td>
        <td class="px-4 py-3 max-w-xs">
            <div class="text-sm text-gray-900 truncate" title="${description}">
                ${description}
            </div>
        </td>
        <td class="px-4 py-3 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900">${total}</div>
        </td>
        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
            <button class="edit-labor text-blue-600 hover:text-blue-900 mx-1" 
                    data-id="${labor.id}"
                    data-workers-count="${labor.workers_count}"
                    data-worker-price="${labor.worker_price}"
                    data-description="${labor.description || ''}">
                <i class="fas fa-edit"></i>
            </button>
            <button class="delete-labor text-red-600 hover:text-red-900 mx-1" 
                    data-id="${labor.id}">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
}

// Mise à jour du total de la main d'œuvre (VERSION SIMPLIFIÉE)
function updateLaborTotal() {
    const total = Array.from(document.querySelectorAll('#laborTableBody tr:not(#noLaborRow)'))
        .reduce((sum, row) => {
            const countText = row.querySelector('td:nth-child(1) div').textContent;
            const priceText = row.querySelector('td:nth-child(2) div').textContent.replace(',', '.');
            
            const count = parseInt(countText);
            const price = parseFloat(priceText);
            
            return sum + (count * price);
        }, 0);
        
    const totalElement = document.getElementById('laborTotal');
    if (totalElement) {
        totalElement.textContent = total.toFixed(2).replace('.', ',');
    }
}

// Édition de main d'œuvre (VERSION SIMPLIFIÉE)
document.addEventListener('click', function(e) {
    if (e.target.closest('.edit-labor')) {
        const button = e.target.closest('.edit-labor');
        const id = button.getAttribute('data-id');
        const workersCount = button.getAttribute('data-workers-count');
        const workerPrice = button.getAttribute('data-worker-price');
        const description = button.getAttribute('data-description');
        
        // Remplir le formulaire d'édition
        document.getElementById('edit_labor_id').value = id;
        document.getElementById('edit_workers_count').value = workersCount;
        document.getElementById('edit_worker_price').value = workerPrice;
        document.getElementById('edit_description').value = description || '';
        
        // Afficher la modal
        document.getElementById('editLaborModal').classList.remove('hidden');
    }
});

// Fermer la modal d'édition de main d'œuvre
document.getElementById('cancelLaborEditButton').addEventListener('click', function() {
    document.getElementById('editLaborModal').classList.add('hidden');
});

// Enregistrer les modifications de main d'œuvre (VERSION SIMPLIFIÉE)
document.getElementById('saveLaborButton').addEventListener('click', function() {
    // Réinitialiser les erreurs
    resetFormErrors('edit_labor');
    
    // Récupérer les données du formulaire
    const formData = new FormData(document.getElementById('editLaborForm'));
    const id = document.getElementById('edit_labor_id').value;
    
    console.log('Données envoyées:', Object.fromEntries(formData));
    
    // Envoi de la requête AJAX
    fetch(`/production-labor/${id}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
            'X-HTTP-Method-Override': 'PUT'
        }
    })
    .then(response => {
        console.log('Statut de la réponse:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Données reçues:', data);
        
        // Vérifier que data.success est bien true
        if (data.success === true) {
            // Si l'objet labor existe dans la réponse
            if (data.labor) {
                reloadCurrentPage();
                // Mettre à jour la ligne dans le tableau
                updateLaborTable(data.labor);
                
                // Fermer la modal
                document.getElementById('editLaborModal').classList.add('hidden');
                
                // Afficher un message de succès
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: data.message || 'Main d\'œuvre modifiée avec succès',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            } else {
                console.warn('Données labor manquantes dans la réponse:', data);
                // Recharger la page pour actualiser les données
                window.location.reload();
            }
        } else {
            // Afficher un message d'erreur explicite
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: data.message || 'Une erreur est survenue lors de la modification',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        }
    })
    .catch(error => {
        console.error('Erreur AJAX:', error);
        
        // Afficher une erreur générique
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Une erreur de communication est survenue',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    });
});

// Suppression de main d'œuvre (IDENTIQUE)
document.addEventListener('click', function(e) {
    if (e.target.closest('.delete-labor')) {
        const button = e.target.closest('.delete-labor');
        const id = button.getAttribute('data-id');
        
        Swal.fire({
            title: 'Confirmation',
            text: 'Êtes-vous sûr de vouloir supprimer cette main d\'œuvre ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Supprimer',
            cancelButtonText: 'Annuler',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280'
        }).then((result) => {
            if (result.isConfirmed) {
                // Envoi de la requête AJAX
                fetch(`/production-labor/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Supprimer la ligne du tableau
                        const row = document.querySelector(`#laborTableBody tr[data-id="${id}"]`);
                        if (row) {
                            row.remove();
                        }
                        
                        // Vérifier si le tableau est vide
                        if (document.querySelectorAll('#laborTableBody tr').length === 0) {
                            const tableBody = document.getElementById('laborTableBody');
                            const emptyRow = document.createElement('tr');
                            emptyRow.id = 'noLaborRow';
                            emptyRow.innerHTML = `
                                <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                    Aucune main d'œuvre déclarée pour cette production
                                </td>
                            `;
                            tableBody.appendChild(emptyRow);
                        }
                        reloadCurrentPage();
                        // Mettre à jour le total
                        updateLaborTotal();
                        
                        // Afficher un message de succès
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: 'Main d\'œuvre supprimée avec succès',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: data.message || 'Une erreur est survenue',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Une erreur est survenue lors de la communication avec le serveur',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
            }
        });
    }
});
       
       // ==================== GESTION DE LA PRODUCTION OBTENUE ====================
       
       // Formulaire d'ajout de production obtenue
       const addOutputForm = document.getElementById('addOutputForm');
       if (addOutputForm) {
           addOutputForm.addEventListener('submit', function(e) {
               e.preventDefault();
               
               // Réinitialiser les erreurs
               resetFormErrors('output');
               
               // Envoi de la requête AJAX
               const formData = new FormData(addOutputForm);
               
               fetch('/production-output', {
                   method: 'POST',
                   body: formData,
                   headers: {
                       'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                       'X-Requested-With': 'XMLHttpRequest'
                   }
               })
               .then(response => {
                   if (!response.ok) {
                       throw response;
                   }
                   return response.json();
               })
               .then(data => {
                   if (data.success) {
                       reloadCurrentPage();
                       // Ajouter la ligne au tableau ou recharger la page
                       updateOutputTable(data.output);
                       addOutputForm.reset();
                       
                       // Afficher un message de succès
                       Swal.fire({
                           icon: 'success',
                           title: 'Succès',
                           text: 'Production obtenue ajoutée avec succès',
                           toast: true,
                           position: 'top-end',
                           showConfirmButton: false,
                           timer: 3000
                       });
                   }
               })
               .catch(error => {
                   if (error.status === 422) {
                       error.json().then(errData => {
                           // Afficher les erreurs de validation
                           if (errData.errors) {
                               Object.keys(errData.errors).forEach(key => {
                                   showError(`${key}_error`, errData.errors[key][0]);
                               });
                           }
                       });
                   } else {
                       // Afficher une erreur générique
                       Swal.fire({
                           icon: 'error',
                           title: 'Erreur',
                           text: 'Une erreur est survenue. Veuillez réessayer.',
                           toast: true,
                           position: 'top-end',
                           showConfirmButton: false,
                           timer: 3000
                       });
                   }
               });
           });
       }
       // Mise à jour du tableau de production obtenue
       function updateOutputTable(output) {
           const tableBody = document.getElementById('outputTableBody');
           const noOutputRow = document.getElementById('noOutputRow');
           
           if (noOutputRow) {
               noOutputRow.remove();
           }
           
           // Vérifier si la ligne existe déjà (mise à jour)
           const existingRow = document.querySelector(`#outputTableBody tr[data-id="${output.id}"]`);
           
           if (existingRow) {
               // Mettre à jour la ligne existante
               existingRow.innerHTML = createOutputRowHtml(output);
           } else {
               // Créer une nouvelle ligne
               const newRow = document.createElement('tr');
               newRow.setAttribute('data-id', output.id);
               newRow.innerHTML = createOutputRowHtml(output);
               tableBody.appendChild(newRow);
           }
           
           // Mettre à jour le total
           updateOutputTotal();
       }
       
       function createOutputRowHtml(output) {
           return `
               <td class="px-4 py-3 whitespace-nowrap">
                   <div class="text-sm font-medium text-gray-900">${output.carton_type.charAt(0).toUpperCase() + output.carton_type.slice(1)}</div>
               </td>
               <td class="px-4 py-3 whitespace-nowrap">
                   <div class="text-sm text-gray-900">${output.carton_count}</div>
               </td>
               <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                   <button class="edit-output text-blue-600 hover:text-blue-900 mx-1" 
                           data-id="${output.id}"
                           data-carton-type="${output.carton_type}"
                           data-carton-count="${output.carton_count}">
                       <i class="fas fa-edit"></i>
                   </button>
                   <button class="delete-output text-red-600 hover:text-red-900 mx-1" 
                           data-id="${output.id}">
                       <i class="fas fa-trash"></i>
                   </button>
               </td>
           `;
       }
       
       // Mise à jour du total de production obtenue
       function updateOutputTotal() {
           const total = Array.from(document.querySelectorAll('#outputTableBody tr:not(#noOutputRow)'))
               .reduce((sum, row) => {
                   const countText = row.querySelector('td:nth-child(2) div').textContent;
                   const count = parseInt(countText);
                   return sum + count;
               }, 0);
               
           const totalElement = document.getElementById('outputTotal');
           if (totalElement) {
               totalElement.textContent = total;
           }
       }
       
       // Édition de production obtenue
       document.addEventListener('click', function(e) {
           if (e.target.closest('.edit-output')) {
               const button = e.target.closest('.edit-output');
               const id = button.getAttribute('data-id');
               const cartonType = button.getAttribute('data-carton-type');
               const cartonCount = button.getAttribute('data-carton-count');
               
               // Remplir le formulaire d'édition
               document.getElementById('edit_output_id').value = id;
               document.getElementById('edit_carton_type').value = cartonType;
               document.getElementById('edit_carton_count').value = cartonCount;
               
               // Afficher la modal
               document.getElementById('editOutputModal').classList.remove('hidden');
           }
       });
       
       // Fermer la modal d'édition de production obtenue
       document.getElementById('cancelOutputEditButton').addEventListener('click', function() {
           document.getElementById('editOutputModal').classList.add('hidden');
       });
       
       // Enregistrer les modifications de production obtenue
       document.getElementById('saveOutputButton').addEventListener('click', function() {
           // Réinitialiser les erreurs
           resetFormErrors('edit_output');
           
           // Récupérer les données du formulaire
           const formData = new FormData(document.getElementById('editOutputForm'));
           const id = document.getElementById('edit_output_id').value;
           
           // Envoi de la requête AJAX
           fetch(`/production-output/${id}`, {
               method: 'POST',
               body: formData,
               headers: {
                   'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                   'X-Requested-With': 'XMLHttpRequest',
                   'X-HTTP-Method-Override': 'PUT'
               }
           })
           .then(response => {
               if (!response.ok) {
                   throw response;
               }
               return response.json();
           })
           .then(data => {
               if (data.success) {
                   reloadCurrentPage();
                   // Mettre à jour la ligne dans le tableau
                   updateOutputTable(data.output);
                   
                   // Fermer la modal
                   document.getElementById('editOutputModal').classList.add('hidden');
                   
                   // Afficher un message de succès
                   Swal.fire({
                       icon: 'success',
                       title: 'Succès',
                       text: 'Production obtenue modifiée avec succès',
                       toast: true,
                       position: 'top-end',
                       showConfirmButton: false,
                       timer: 3000
                   });
               }
           })
           .catch(error => {
               if (error.status === 422) {
                   error.json().then(errData => {
                       // Afficher les erreurs de validation
                       if (errData.errors) {
                           Object.keys(errData.errors).forEach(key => {
                               showError(`edit_${key}_error`, errData.errors[key][0]);
                           });
                       }
                   });
               } else {
                   // Afficher une erreur générique
                   Swal.fire({
                       icon: 'error',
                       title: 'Erreur',
                       text: 'Une erreur est survenue. Veuillez réessayer.',
                       toast: true,
                       position: 'top-end',
                       showConfirmButton: false,
                       timer: 3000
                   });
               }
           });
       });
       
       // Suppression de production obtenue
       document.addEventListener('click', function(e) {
           if (e.target.closest('.delete-output')) {
               const button = e.target.closest('.delete-output');
               const id = button.getAttribute('data-id');
               
               Swal.fire({
                   title: 'Confirmation',
                   text: 'Êtes-vous sûr de vouloir supprimer cette production obtenue ?',
                   icon: 'warning',
                   showCancelButton: true,
                   confirmButtonText: 'Supprimer',
                   cancelButtonText: 'Annuler',
                   confirmButtonColor: '#ef4444',
                   cancelButtonColor: '#6b7280'
               }).then((result) => {
                   if (result.isConfirmed) {
                       // Envoi de la requête AJAX
                       fetch(`/production-output/${id}`, {
                           method: 'DELETE',
                           headers: {
                               'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                               'X-Requested-With': 'XMLHttpRequest'
                           }
                       })
                       .then(response => response.json())
                       .then(data => {
                           if (data.success) {
                               // Supprimer la ligne du tableau
                               const row = document.querySelector(`#outputTableBody tr[data-id="${id}"]`);
                               if (row) {
                                   row.remove();
                               }
                               
                               // Vérifier si le tableau est vide
                               if (document.querySelectorAll('#outputTableBody tr').length === 0) {
                                   const tableBody = document.getElementById('outputTableBody');
                                   const emptyRow = document.createElement('tr');
                                   emptyRow.id = 'noOutputRow';
                                   emptyRow.innerHTML = `
                                       <td colspan="3" class="px-4 py-3 text-center text-gray-500">
                                           Aucune production obtenue déclarée
                                       </td>
                                   `;
                                   tableBody.appendChild(emptyRow);
                               }
                               reloadCurrentPage();
                               // Mettre à jour le total
                               updateOutputTotal();
                               
                               // Afficher un message de succès
                               Swal.fire({
                                   icon: 'success',
                                   title: 'Succès',
                                   text: 'Production obtenue supprimée avec succès',
                                   toast: true,
                                   position: 'top-end',
                                   showConfirmButton: false,
                                   timer: 3000
                               });
                           } else {
                               Swal.fire({
                                   icon: 'error',
                                   title: 'Erreur',
                                   text: data.message || 'Une erreur est survenue',
                                   toast: true,
                                   position: 'top-end',
                                   showConfirmButton: false,
                                   timer: 3000
                               });
                           }
                       })
                       .catch(error => {
                           console.error('Erreur:', error);
                           Swal.fire({
                               icon: 'error',
                               title: 'Erreur',
                               text: 'Une erreur est survenue lors de la communication avec le serveur',
                               toast: true,
                               position: 'top-end',
                               showConfirmButton: false,
                               timer: 3000
                           });
                       });
                   }
               });
           }
       });
       
       // ==================== VÉRIFICATION ET FINALISATION DE LA PRODUCTION ====================
       
       // Bouton de vérification et finalisation
       const verifyButton = document.getElementById('verifyButton');
       if (verifyButton) {
           verifyButton.addEventListener('click', function() {
               // Vérifier si tous les articles ont été déclarés
               const hasArticles = !document.getElementById('noArticlesRow');
               
               if (!hasArticles) {
                   Swal.fire({
                       icon: 'warning',
                       title: 'Attention',
                       text: 'Vous devez déclarer au moins un article utilisé avant de finaliser la production',
                       confirmButtonColor: '#3085d6'
                   });
                   return;
               }
               
               // Afficher la confirmation
               Swal.fire({
                   title: 'Finaliser la production ?',
                   html: `
                       <p>Êtes-vous sûr de vouloir finaliser cette production ?</p>
                       <p class="mt-2 text-sm text-gray-600">Cette action entraînera :</p>
                       <ul class="mt-1 text-sm text-left text-gray-600 list-disc list-inside">
                           <li>La déduction des articles utilisés du stock</li>
                           <li>Le changement du statut de la production à "Terminé"</li>
                           <li>La validation définitive de toutes les informations déclarées</li>
                       </ul>
                       <p class="mt-2 text-sm text-red-600">Cette action est irréversible !</p>
                   `,
                   icon: 'warning',
                   showCancelButton: true,
                   confirmButtonText: 'Oui, finaliser',
                   cancelButtonText: 'Annuler',
                   confirmButtonColor: '#10b981',
                   cancelButtonColor: '#6b7280'
               }).then((result) => {
                   if (result.isConfirmed) {
                       // Envoi de la requête AJAX pour finaliser la production
                       fetch(`/productions/{{ $production->id }}/verify`, {
                           method: 'POST',
                           headers: {
                               'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                               'X-Requested-With': 'XMLHttpRequest'
                           }
                       })
                       .then(response => {
                           if (!response.ok) {
                               throw response;
                           }
                           return response.json();
                       })
                       .then(data => {
                           if (data.success) {
                              // Afficher un message de succès
                              Swal.fire({
                                   icon: 'success',
                                   title: 'Production finalisée avec succès!',
                                   text: 'Tous les articles ont été défalqués du stock et la production est maintenant terminée.',
                                   confirmButtonColor: '#10b981'
                               }).then(() => {
                                   // Recharger la page pour afficher les changements
                                   window.location.reload();
                               });
                           } else {
                               throw new Error(data.message || 'Une erreur est survenue');
                           }
                       })
                       .catch(error => {
                           if (error.status === 422) {
                               error.json().then(errData => {
                                   Swal.fire({
                                       icon: 'error',
                                       title: 'Erreur',
                                       html: errData.message || 'Une erreur est survenue lors de la finalisation.',
                                       confirmButtonColor: '#3085d6'
                                   });
                               });
                           } else if (error.message) {
                               Swal.fire({
                                   icon: 'error',
                                   title: 'Erreur',
                                   text: error.message,
                                   confirmButtonColor: '#3085d6'
                               });
                           } else {
                               console.error('Erreur:', error);
                               Swal.fire({
                                   icon: 'error',
                                   title: 'Erreur',
                                   text: 'Une erreur est survenue lors de la communication avec le serveur',
                                   confirmButtonColor: '#3085d6'
                               });
                           }
                       });
                   }
               });
           });
       }
       
       // ==================== FONCTIONS UTILITAIRES ====================
       
       // Réinitialiser les erreurs de formulaire
       function resetFormErrors(prefix = '') {
           const errorElements = document.querySelectorAll(`[id$="_error"]`);
           errorElements.forEach(el => {
               if (!prefix || el.id.startsWith(prefix)) {
                   el.textContent = '';
                   el.classList.add('hidden');
               }
           });
       }
       
       // Afficher un message d'erreur
       function showError(elementId, message) {
           const errorElement = document.getElementById(elementId);
           if (errorElement) {
               errorElement.textContent = message;
               errorElement.classList.remove('hidden');
           }
       }
   });
</script>
<!-- JavaScript pour remplir automatiquement le prix unitaire -->
<script>
   document.addEventListener('DOMContentLoaded', function() {
     const articleSelect = document.getElementById('article_id');
     const unitPriceInput = document.getElementById('unit_price');
     
     if (articleSelect && unitPriceInput) {
         articleSelect.addEventListener('change', function() {
             if (this.selectedIndex > 0) {
                 const selectedOption = this.options[this.selectedIndex];
                 const price = selectedOption.getAttribute('data-price');
                 unitPriceInput.value = price;
             } else {
                 unitPriceInput.value = '';
             }
         });
     }
   });
</script>