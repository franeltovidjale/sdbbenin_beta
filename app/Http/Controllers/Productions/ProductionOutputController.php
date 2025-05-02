<?php

 namespace App\Http\Controllers\Productions;

 use App\Http\Controllers\Controller;
 use App\Models\Production;
 use App\Models\ProductionOutput;
 use Illuminate\Http\Request;
 
 class ProductionOutputController extends Controller
 {
     /**
      * Ajouter une production obtenue à une production
      */
    //  public function store(Request $request)
    //  {
    //      $validated = $request->validate([
    //          'production_id' => 'required|exists:productions,id',
    //          'carton_type' => 'required|in:petit,grand',
    //          'carton_count' => 'required|integer|min:1',
    //      ]);
         
    //      // Vérifier que la production est toujours en cours
    //      $production = Production::findOrFail($validated['production_id']);
    //      if ($production->status !== 'en cours') {
    //          return response()->json([
    //              'success' => false,
    //              'message' => 'Impossible de modifier une production terminée'
    //          ], 422);
    //      }
         
    //      $output = ProductionOutput::create($validated);
         
    //      return response()->json([
    //          'success' => true,
    //          'message' => 'Production obtenue ajoutée avec succès',
    //          'output' => $output
    //      ]);
    //  }

    public function store(Request $request)
{
    $validated = $request->validate([
        'production_id' => 'required|exists:productions,id',
        'carton_type' => 'required|in:petit,grand',
        'carton_count' => 'required|integer|min:1',
    ]);
    
    // Vérifier que la production est toujours en cours
    $production = Production::findOrFail($validated['production_id']);
    if ($production->status !== 'en cours') {
        return response()->json([
            'success' => false,
            'message' => 'Impossible de modifier une production terminée'
        ], 422);
    }

    // Vérifier qu'il n'existe pas déjà un enregistrement avec ce type de carton pour cette production
    $existingOutput = ProductionOutput::where('production_id', $validated['production_id'])
                                      ->where('carton_type', $validated['carton_type'])
                                      ->first();
    if ($existingOutput) {
        return response()->json([
            'success' => false,
            'message' => 'Ce type de carton existe déjà pour cette production. Veuillez modifier la quantité !'
        ], 400);
    }

    // Si ce n'est pas le cas, créer un nouvel enregistrement
    $output = ProductionOutput::create($validated);

    return response()->json([
        'success' => true,
        'message' => 'Production obtenue ajoutée avec succès',
        'output' => $output
    ]);
}

     
     /**
      * Mettre à jour une production obtenue dans une production
      */
     public function update(Request $request, $id)
     {
         $validated = $request->validate([
             'carton_type' => 'required|in:petit,grand',
             'carton_count' => 'required|integer|min:1',
         ]);
         
         $output = ProductionOutput::findOrFail($id);
         
         // Vérifier que la production est toujours en cours
         $production = Production::findOrFail($output->production_id);
         if ($production->status !== 'en cours') {
             return response()->json([
                 'success' => false,
                 'message' => 'Impossible de modifier une production terminée'
             ], 422);
         }
         
         $output->update($validated);
         
         return response()->json([
             'success' => true,
             'message' => 'Production obtenue modifiée avec succès',
             'output' => $output
         ]);
     }
     
     /**
      * Supprimer une production obtenue d'une production
      */
     public function destroy($id)
     {
         $output = ProductionOutput::findOrFail($id);
         
         // Vérifier que la production est toujours en cours
         $production = Production::findOrFail($output->production_id);
         if ($production->status !== 'en cours') {
             return response()->json([
                 'success' => false,
                 'message' => 'Impossible de modifier une production terminée'
             ], 422);
         }
         
         $output->delete();
         
         return response()->json([
             'success' => true,
             'message' => 'Production obtenue supprimée avec succès'
         ]);
     }
 }
 