<?php
namespace Hannoma\Larapolls\Helpers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use App\User;

class StandardPermissionsHelper{

    public static function giveStandardPermission(){
      if(!Auth::guest()){
        if(!Auth::user()->hasRole('larapolls_standard')){
          if(!Role::where('name', 'larapolls_standard')->first()){
            $permissions = array();
            foreach (config('larapolls.standard_permissions') as $standardPermission) {
              $p = Permission::where('name', config('larapolls.permissions.prefix') . $standardPermission)->first();
              if($p){
                array_push($permissions, $p);
              } else {
                array_push($permissions, Permission::create(['name' => config('larapolls.permissions.prefix') . $standardPermission]));
              }
            }
            $role = Role::create(['name' => 'larapolls_standard']);
            $role->syncPermissions($permissions);
          }
          Auth::user()->assignRole('larapolls_standard');
        }
      }
    }
}
