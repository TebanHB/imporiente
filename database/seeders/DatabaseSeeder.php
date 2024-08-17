<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // En la bdd insert into empresa values (1,	"Imporiente",	"Chile"	,"+56 9 6140 2532"	,"Santiago"	,"Santiago de Chile"	,"Emiliano Figueroa 754",	19.00, null,null);
        $this->call([
            RolesTableSeeder::class,
            UserSeeder::class,
        ]);
    }
}
