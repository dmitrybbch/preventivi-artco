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
          'nome' => 'caffè',  
          'prezzo' => '1.20',
          'descrizione' => '',
        ]);

        DB::table('foods')->insert([
          'nome' => 'acqua 1/2 litro',  
          'prezzo' => '1.00',
          'descrizione' => '',
        ]);

        DB::table('foods')->insert([
          'nome' => 'pizza margherita',  
          'prezzo' => '5.00',
          'descrizione' => 'pomodoro e mozzarella',
        ]);

        DB::table('foods')->insert([
          'nome' => 'coca cola lattina',  
          'prezzo' => '2.50',
          'descrizione' => 'lattina 33 cl.',
        ]);
    }
  }
