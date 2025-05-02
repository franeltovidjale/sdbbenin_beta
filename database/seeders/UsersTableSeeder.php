<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\User::create([
            
            'email' => 'admin@glsam.com',
            'phone_number' => '97942096',
            'password' => Hash::make('Glpass77')
        ]);
    
        \App\Models\User::create([
            
            'email' => 'second@admin.com',
            'phone_number' => '97942097',
            'password' => Hash::make('Krmot666')
        ]);
    }
}
