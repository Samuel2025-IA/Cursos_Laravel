<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User; // Added this import for the User model

class CheckAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:admin-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar si existe el usuario admin en la base de datos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando usuario admin...');
        
        $user = User::where('email', 'admin@cursos.test')->first();
        
        if ($user) {
            $this->info('✅ Usuario admin encontrado:');
            $this->line('ID: ' . $user->id);
            $this->line('Email: ' . $user->email);
            $this->line('Nombre: ' . $user->primer_nombre . ' ' . $user->primer_apellido);
            $this->line('Rol: ' . $user->rol);
            $this->line('Entidad: ' . $user->entidad);
        } else {
            $this->error('❌ Usuario admin NO encontrado');
        }
        
        $this->info('Total de usuarios en la BD: ' . User::count());
        
        // Mostrar todos los usuarios
        $this->info('Lista de usuarios existentes:');
        $users = User::all(['id', 'email', 'primer_nombre', 'primer_apellido', 'rol']);
        foreach ($users as $user) {
            $this->line('- ID: ' . $user->id . ' | Email: ' . $user->email . ' | Nombre: ' . $user->primer_nombre . ' ' . $user->primer_apellido . ' | Rol: ' . $user->rol);
        }
        
        return 0;
    }
}
