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

        DB::table('provisions')->insert([
            'name' => 'Sedia di legno',
            'cost' => '1.20',
            'description' => 'Una bella sedia di compensato.',

            'chapter' => 'EEE',
            'category' => 'BBB',
            'chapter_category' => 'EEE_BBB'
        ]);

        DB::table('provisions')->insert([
            'name' => 'WC',
            'cost' => '1.00',
            'description' => 'Heh!',

            'chapter' => 'EEE',
            'category' => 'BBB',
            'chapter_category' => 'EEE_BBB'

        ]);

        DB::table('provisions')->insert([
            'name' => 'Piantina',
            'cost' => '5.00',
            'unit' => 'kg',
            'description' => 'Un vaso o una mappa?',
            'chapter' => 'EEE',
            'category' => 'AAA',
            'chapter_category' => 'EEE_AAA'
        ]);

        DB::table('provisions')->insert([
            'name' => 'Letto matrimoniale per nani.',
            'cost' => '2.50',
            'description' => 'Quindi un letto singolo.',

            'chapter' => 'EEE',
            'category' => 'AAA',
            'chapter_category' => 'EEE_AAA'
        ]);

        DB::table('provisions')->insert([
            'name' => 'Bomba Nucleare',
            'cost' => '9999999.99',
            'description' => 'Il sogno di Mohammad.',
            'chapter' => 'HEYH',
            'category' => 'Demolizioni',
            'chapter_category' => 'HEYH_Demolizioni'
        ]);

        DB::table('clients')->insert([
            'nome' => 'ClientoneComodo',
            'email' => 'como@comodo.com',
            'telefono' => '34234223',
            'indirizzo' => 'Via Coma 30',
            'capCittaProv' => "20202 Como (CO)"
        ]);


    }
}
