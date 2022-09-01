<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Roles\Roles;
use App\Models\Roles\Permisos;
use Illuminate\Support\Facades\Hash;

class createRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    //ROL ADMIN 

         $rolAdmin = Roles::create(['name' => 'Admin','slug' => 'admin',]);

        //CREAR PERMISOS

        $permisos_Admin = [];

        $permisos = Permisos::create([
            'name' => 'Crear',
            'slug' => 'crear'
        ]);
        $permisos_Admin[] = $permisos ->id;

        $permisos = Permisos::create([
            'name' => 'Editar',
            'slug' => 'editar'
        ]);
        $permisos_Admin[] = $permisos ->id;

        $permisos = Permisos::create([
            'name' => 'Leer',
            'slug' => 'leer'
        ]);
        $permisos_Admin[] = $permisos ->id;

        $permisos = Permisos::create([
            'name' => 'Borrar',
            'slug' => 'borrar'
        ]);
        $permisos_Admin[] = $permisos ->id;

       foreach ($permisos_Admin as $permiso) {
          $rolAdmin->permisos()->attach($permiso);
          $rolAdmin->save();
        }

// ROL CLIENTE

        $rolCliente = Roles::create(['name' => 'Cliente','slug' => 'cliente',]);

        //CREAR PERMISOS

        $permisos_cliente = [];

        $permisos_c = Permisos::create([
            'name' => 'Crear',
            'slug' => 'crear'
        ]);
        $permisos_cliente[] = $permisos_c ->id;

        $permisos_c = Permisos::create([
            'name' => 'Editar',
            'slug' => 'editar'
        ]);
        $permisos_cliente[] = $permisos_c ->id;

        $permisos_c = Permisos::create([
            'name' => 'Leer',
            'slug' => 'leer'
        ]);
        $permisos_cliente[] = $permisos_c ->id;

        $permisos_c = Permisos::create([
            'name' => 'Borrar',
            'slug' => 'borrar'
        ]);
        $permisos_cliente[] = $permisos_c ->id;

       foreach ($permisos_cliente as $permisocl) {
          //$usuarioSysAdmin->permisos()->attach($permiso);
          //$usuarioSysAdmin->save();
          $rolCliente->permisos()->attach($permisocl);
          $rolCliente->save();
        }


// ROL EJECUTIVO

        $rolEjecutivo = Roles::create(['name' => 'Ejecutivo','slug' => 'ejecutivo',]);

        //CREAR PERMISOS

        $permisos_ejecutivo = [];

        $permisos_e = Permisos::create([
            'name' => 'Crear',
            'slug' => 'crear'
        ]);
        $permisos_ejecutivo[] = $permisos_e ->id;

        $permisos_e = Permisos::create([
            'name' => 'Editar',
            'slug' => 'editar'
        ]);
        $permisos_ejecutivo[] = $permisos_e ->id;

        $permisos_e = Permisos::create([
            'name' => 'Leer',
            'slug' => 'leer'
        ]);
        $permisos_ejecutivo[] = $permisos_e ->id;

        $permisos_e = Permisos::create([
            'name' => 'Borrar',
            'slug' => 'borrar'
        ]);
        $permisos_ejecutivo[] = $permisos_e ->id;

       foreach ($permisos_ejecutivo as $permisoej) {
          //$usuarioSysAdmin->permisos()->attach($permiso);
          //$usuarioSysAdmin->save();
          $rolEjecutivo->permisos()->attach($permisoej);
          $rolEjecutivo->save();
        }

// ROL USUARIO
        $rolUsuario = Roles::create(['name' => 'Usuario','slug' => 'usuario',]);

        //CREAR PERMISOS

        $permisos_Usuario = [];

        $permisos_u = Permisos::create([
            'name' => 'Crear',
            'slug' => 'crear'
        ]);
        $permisos_Usuario[] = $permisos_u ->id;

        $permisos_u = Permisos::create([
            'name' => 'Editar',
            'slug' => 'editar'
        ]);
        $permisos_Usuario[] = $permisos_u ->id;

        $permisos_u = Permisos::create([
            'name' => 'Leer',
            'slug' => 'leer'
        ]);
        $permisos_Usuario[] = $permisos_u ->id;

        $permisos_u = Permisos::create([
            'name' => 'Borrar',
            'slug' => 'borrar'
        ]);
        $permisos_Usuario[] = $permisos_u ->id;

       foreach ($permisos_Usuario as $permiso) {
          //$usuarioSysAdmin->permisos()->attach($permiso);
          //$usuarioSysAdmin->save();
          $rolUsuario->permisos()->attach($permiso);
          $rolUsuario->save();
        }

    }
}
