<?php

namespace Database\Seeders;
Use DB;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Roles\Roles;
use App\Models\Roles\Permisos;
use Illuminate\Support\Facades\Hash;



class rolesPermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//TRUNCAR TABLAS PARA CREAR TODO DESDE CERO

        DB::statement("SET foreign_key_checks=0");
            DB::table('users_roles')->truncate();
            DB::table('users_permisos')->truncate();
            DB::table('roles_permisos')->truncate();
            Permisos::truncate();
            Roles::truncate();
            User::truncate();
       DB::statement("SET foreign_key_checks=1");

//CREAR USUARIO SYSADMIN

        $usuarioSysAdmin = User::create([
            'name'      => 'SysAdminUser',
            'email'     => 'sysadminuser@gmail.com',
            'password'  => Hash::make('123456789'),
            'empresa_id'=> '1'
        ]);

// CREAR ROL SYSADMIN

        $rolSysAdmin  = Roles::create(['name' => 'SysAdmin','slug' => 'sysadmin',]);

        $usuarioSysAdmin->roles()->sync([ $rolSysAdmin->id ]);

        //CREAR PERMISOS

        $permisos_all = [];

        $permisos = Permisos::create([
            'name' => 'Crear',
            'slug' => 'crear'
        ]);
        $permisos_all[] = $permisos ->id;

        $permisos = Permisos::create([
            'name' => 'Editar',
            'slug' => 'editar'
        ]);
        $permisos_all[] = $permisos ->id;

        $permisos = Permisos::create([
            'name' => 'Leer',
            'slug' => 'leer'
        ]);
        $permisos_all[] = $permisos ->id;

        $permisos = Permisos::create([
            'name' => 'Borrar',
            'slug' => 'borrar'
        ]);
        $permisos_all[] = $permisos ->id;

       foreach ($permisos_all as $permiso) {
          $usuarioSysAdmin->permisos()->attach($permiso);
          $usuarioSysAdmin->save();
          $rolSysAdmin->permisos()->attach($permiso);
          $rolSysAdmin->save();
        }

// **********************   USUARIO ADMIN  ********************************

//CREAR USUARIO ADMIN
        $usuarioAdmin = User::create([
            'name'      => 'AdminUser',
            'email'     => 'adminuser@gmail.com',
            'password'  => Hash::make('AdminTemp01'),
            'empresa_id'=> '1'
        ]);

//CREAR ROL ADMIN 

         $rolAdmin = Roles::create(['name' => 'Admin','slug' => 'admin',]);

         $usuarioAdmin->roles()->sync([ $rolAdmin->id ]);


//CREAR PERMISOS

        $permisos_Admin = [];

        $permisosadm = Permisos::create([
            'name' => 'Crear',
            'slug' => 'crear'
        ]);
        $permisos_Admin[] = $permisosadm ->id;

        $permisosadm = Permisos::create([
            'name' => 'Editar',
            'slug' => 'editar'
        ]);
        $permisos_Admin[] = $permisosadm ->id;

        $permisosadm = Permisos::create([
            'name' => 'Leer',
            'slug' => 'leer'
        ]);
        $permisos_Admin[] = $permisosadm ->id;

        $permisosadm = Permisos::create([
            'name' => 'Borrar',
            'slug' => 'borrar'
        ]);
        $permisos_Admin[] = $permisosadm ->id;

       foreach ($permisos_Admin as $permisoad) {
          $usuarioAdmin->permisos()->attach($permisoad);
          $usuarioAdmin->save();
          $rolAdmin->permisos()->attach($permisoad);
          $rolAdmin->save();
        }


// **********************   USUARIO EJECUTIVO  ********************************


//CREAR USUARIO EJECUTIVO

        $usuarioEjecutivo = User::create([
            'name'      => 'EjecutivoUser',
            'email'     => 'ejecutivouser@gmail.com',
            'password'  => Hash::make('EjecutivoTemp01'),
            'empresa_id'=> '1'
        ]);


// CREAR ROL EJECUTIVO

        $rolEjecutivo = Roles::create(['name' => 'Ejecutivo','slug' => 'ejecutivo',]);

        $usuarioEjecutivo->roles()->sync([ $rolEjecutivo->id ]);

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
          $usuarioEjecutivo->permisos()->attach($permisoej);
          $usuarioEjecutivo->save();
          $rolEjecutivo->permisos()->attach($permisoej);
          $rolEjecutivo->save();
        }


// **********************   USUARIO CLIENTE  ********************************

//CREAR USUARIO CLIENTE
        
        $usuarioCliente = User::create([
            'name'      => 'ClienteUser',
            'email'     => 'clienteuser@gmail.com',
            'password'  => Hash::make('ClienteTemp01'),
            'empresa_id'=> '1'
        ]);

// CREAR ROL CLIENTE

        $rolCliente = Roles::create(['name' => 'Cliente','slug' => 'cliente',]);

        $usuarioCliente->roles()->sync([ $rolCliente->id ]);

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
          $usuarioCliente->permisos()->attach($permisocl);
          $usuarioCliente->save();
          $rolCliente->permisos()->attach($permisocl);
          $rolCliente->save();
        }


// **********************   USUARIO USUARIO  ********************************

//CREAR USUARIO USUARIO
        
        $usuarioUsuario = User::create([
            'name'      => 'Usuario',
            'email'     => 'usuario@gmail.com',
            'password'  => Hash::make('UsuarioTemp01'),
            'empresa_id'=> '1'
        ]);

// CREAR ROL USUARIO
        $rolUsuario = Roles::create(['name' => 'Usuario','slug' => 'usuario',]);

        $usuarioUsuario->roles()->sync([ $rolUsuario->id ]);

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

       foreach ($permisos_Usuario as $permisous) {
          $usuarioUsuario->permisos()->attach($permisous);
          $usuarioUsuario->save();
          $rolUsuario->permisos()->attach($permisous);
          $rolUsuario->save();
        }

    }
}
