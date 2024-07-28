<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Crear el rol de admin si no existe
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        // Crear el rol de vendedor si no existe
        $roleVendedor = Role::firstOrCreate(['name' => 'vendedor']);
        // Crear el rol de cliente si no existe
        $roleCliente = Role::firstOrCreate(['name' => 'cliente']);

        // Crear un usuario administrador
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => bcrypt('1234'), // Usa una contraseña segura en producción
        ]);
        $admin->assignRole($roleAdmin);

        // Crear dos usuarios vendedores
        for ($i = 1; $i <= 2; $i++) {
            $vendedor = User::create([
                'name' => "Vendedor $i",
                'email' => "vendedor$i@vendedor.com",
                'password' => bcrypt('1234'), // Usa una contraseña segura en producción
            ]);
            $vendedor->assignRole($roleVendedor);
        }

        // Crear diez usuarios clientes
        for ($i = 1; $i <= 10; $i++) {
            $cliente = User::create([
                'name' => "Jeferson $i",
                'email' => "jeffer$i@yerko.com",
                'password' => bcrypt('1234'), // Usa una contraseña segura en producción
            ]);
            $cliente->assignRole($roleCliente);
        }
    }
}
