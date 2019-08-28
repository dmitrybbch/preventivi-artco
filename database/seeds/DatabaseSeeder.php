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
            'tags' => 'compensato, sedia, arredamento'
        ]);

        DB::table('foods')->insert([
            'nome' => 'WC usato',
            'prezzo' => '1.00',
            'descrizione' => 'Un WC usato, pulizie a carico dell acquirente.',
            'tags' => 'bagno, ceramica'
        ]);

        DB::table('foods')->insert([
            'nome' => 'Piantina',
            'prezzo' => '5.00',
            'descrizione' => 'Un vaso o una mappa?',
            'tags' => 'mistero, vaso, mappa'
        ]);

        DB::table('foods')->insert([
            'nome' => 'Letto matrimoniale per nani.',
            'prezzo' => '2.50',
            'descrizione' => 'Quindi un letto singolo.',
            'tags' => 'legno, compensato'
        ]);





    }
  }
