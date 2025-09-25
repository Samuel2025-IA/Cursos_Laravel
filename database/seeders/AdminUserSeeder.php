<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@cursos.test'],
            [
                'primer_nombre' => 'Admin',
                'segundo_nombre' => null,
                'primer_apellido' => 'Sistema',
                'segundo_apellido' => 'Usuario',
                'password' => Hash::make('admin123'),
                'rol' => 'admin',
                'entidad' => 'diocesis_apartado',
            ]
        );
    }
}
