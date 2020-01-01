<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('users')->insert([
          'username' => 'admin',
          'password' => Hash::make('superadmin'),
          'type' => 'admin',
        ]);

        DB::table('foods')->insert([
            'nome' => 'Sedia di legno',
            'prezzo' => '1.20',
            'descrizione' => 'Una bella sedia di compensato.',
            'categoria' => 'Prodotti',
            'subcategoria' => 'Cose belle'
        ]);

        DB::table('foods')->insert([
            'nome' => 'WC',
            'prezzo' => '1.00',
            'descrizione' => 'Heh!',
            'categoria' => 'Prodotti',
            'subcategoria' => 'Cose belle'

        ]);

        DB::table('foods')->insert([
            'nome' => 'Piantina',
            'prezzo' => '5.00',
            'descrizione' => 'Un vaso o una mappa?',
            'categoria' => 'Prodotti',
            'subcategoria' => 'Cose belle'
        ]);

        DB::table('foods')->insert([
            'nome' => 'Letto matrimoniale per nani.',
            'prezzo' => '2.50',
            'descrizione' => 'Quindi un letto singolo.',
            'categoria' => 'Prodotti',
            'subcategoria' => 'Cose brutte'
        ]);


    }
  }
