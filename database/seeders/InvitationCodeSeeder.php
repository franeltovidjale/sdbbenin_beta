<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InvitationCode;
use Illuminate\Support\Str;

class InvitationCodeSeeder extends Seeder
{
    public function run()
    {
        // Créer un code d'invitation admin par défaut
        InvitationCode::create([
            'code' => 'ADMIN' . strtoupper(Str::random(8)),
            'role' => 'admin',
            'is_used' => false,
            'expires_at' => now()->addYear(),
        ]);
        
        // Afficher le code pour pouvoir le récupérer
        $this->command->info('Code d\'invitation admin: ' . InvitationCode::first()->code);
    }
}