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
            'categoria' => 'ABC - BBB',
        ]);

        DB::table('foods')->insert([
            'nome' => 'WC',
            'prezzo' => '1.00',
            'descrizione' => 'Heh!',
            'categoria' => 'BCD - BBB',

        ]);

        DB::table('foods')->insert([
            'nome' => 'Piantina',
            'prezzo' => '5.00',
            'unita' => 'kg',
            'descrizione' => 'Un vaso o una mappa?',
            'categoria' => 'BCD - AAA',
        ]);

        DB::table('foods')->insert([
            'nome' => 'Letto matrimoniale per nani.',
            'prezzo' => '2.50',
            'descrizione' => 'Quindi un letto singolo.',
            'categoria' => 'ABC - AAA',
        ]);
        DB::table('foods')->insert([
            'nome' => 'Bomba Nucleare',
            'prezzo' => '9999999.99',
            'descrizione' => 'Il sogno di Mohammad.',
            'categoria' => 'CORPI ILLUMINANTI - Demolizioni',
        ]);

        DB::table('sections')->insert([
            'name' => 'A) Camion Cino',
        ]);
        DB::table('sections')->insert([
            'name' => 'B) Alpa Cino',
        ]);
        DB::table('sections')->insert([
            'name' => 'C) Man Cino',
        ]);


        DB::table('categories')->insert([
            'name' => 'ABC',
            'section_id' => 1,
        ]);
        DB::table('categories')->insert([
            'name' => 'AAA',
            'section_id' => 1,
        ]);
        DB::table('categories')->insert([
            'name' => 'BBB',
            'section_id' => 1,
        ]);
        DB::table('categories')->insert([
            'name' => 'CCC',
            'section_id' => 1,
        ]);

    }
  }
