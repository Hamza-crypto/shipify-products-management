<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        Create Admin
        User::create([
            'name' => 'Hamza Siddique',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'role' => 'admin'
        ]);

        $stores = [
            ['name' => 'Ursula Miles', 'slug' => 'ursula-miles', 'token' => 'shpua_da2ffe15517ba8cf9d6d8b46849a369e'],
            ['name' => 'Mr Surveillance', 'slug' => 'yoursamour', 'token' => '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'],
            ['name' => 'Home Sweet Joy', 'slug' => 'home-sweet-joy', 'token' => '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'],
        ];

        foreach ($stores as $store) {
            \App\Models\Store::create($store);
        }


    }
}
