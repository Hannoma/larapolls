<?php

namespace Hannoma\Larapolls\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class PermissionSetup extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larapolls:setup_roles {--A|admin} {--M|moderator} {--D|defaultMember}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup the permission roles for this package';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      if($this->option('admin')){
        if(Role::where('name', 'larapolls_admin')){$this->info('Admin role exist!');} else {
          $this->info('Creating the admin role');
          $adminRole = Role::create(['name' => config('larapolls.permissions.prefix') . 'admin']);
          $adminPermission = array();
          $p = Permission::where('name', config('larapolls.permissions.prefix') . config('larapolls.permissions.createPoll'))->first();
          if($p){
            array_push($adminPermission, $p);
          } else {
            array_push($adminPermission, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.createPoll')]));
          }
          $p = Permission::where('name', config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollSticky'))->first();
          if($p){
            array_push($adminPermission, $p);
          } else {
            array_push($adminPermission, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollMultiple')]));
          }
          $p = Permission::where('name', config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollMultiple'))->first();
          if($p){
            array_push($adminPermission, $p);
          } else {
            array_push($adminPermission, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollContra')]));
          }
          $p = Permission::where('name', config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollContra'))->first();
          if($p){
            array_push($adminPermission, $p);
          } else {
            array_push($adminPermission, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.showPollWithCategory') . $cat]));
          }
          array_push($adminPermission, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.allowPoll')]));
          array_push($adminPermission, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.deletePoll')]));
          array_push($adminPermission, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.createNewCategory')]));
          array_push($adminPermission, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.showAllCategories')]));
          $adminRole->syncPermissions($adminPermission);
        }

        if ($this->confirm('Do you want to add users to the role?')) {
          while(true){
            $id = $this->ask('Please enter the ID of the user you want to add to this role! Repeat for every user you want to add. To exit enter a invalid id!');
            $u = User::where('id', $id)->first();
            if($u){
              $u->assignRole('larapolls_admin');
            } else {
              $this->error('User with id ' . $id . " could not be found!");
              break;
            }
          }
        }
        $this->info('Everthing is set up!');
      }
      if($this->option('moderator')){
        $this->info('Creating the moderator role');
        $cat = $this->ask('For which category do you want to configure the moderator?');
        if($cat == ""){$this->error('The category should not be blank!');} else {

          $permissions = array();
          if ($this->confirm('Do you wish moderators to allow polls in this category?')) {
            array_push($permissions, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.allowPollWithCategory') . $cat]));
          }
          if ($this->confirm('Do you wish moderators to delte polls in this category?')) {
            array_push($permissions, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.deletePollWithCategory') . $cat]));
          }
          if ($this->confirm('Do you wish moderators to create polls in this category?')) {
            $p = Permission::where('name', config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollWithCategory') . $cat)->first();
            if($p){
              array_push($permissions, $p);
            } else {
              array_push($permissions, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollWithCategory') . $cat]));
            }
          }
          if ($this->confirm('Do you wish moderators to see polls in this category even when the category is protected? (Recommended)')) {
            $p = Permission::where('name', config('larapolls.permissions.prefix') . config('larapolls.permissions.showPollWithCategory') . $cat)->first();
            if($p){
              array_push($permissions, $p);
            } else {
              array_push($permissions, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.showPollWithCategory') . $cat]));
            }
          }
          if(count($permissions) > 0){
            $role = Role::create(['name' => config('larapolls.permissions.prefix') . 'moderator_' . $cat]);
            $role->syncPermissions($permissions);

            if ($this->confirm('Do you want to add users to the role?')) {
              while(true){
                $id = $this->ask('Please enter the ID of the user you want to add to this role! Repeat for every user you want to add. To exit enter a invalid id!');
                $u = User::where('id', $id)->first();
                if($u){
                  $u->assignRole('larapolls_moderator_'.$cat);
                } else {
                  $this->error('User with id ' . $id . " could not be found!");
                  break;
                }
              }
            }
            $this->info('Everthing is set up!');
          } else {
            $this->error('No Permissions would be added!');
          }
        }
      }
      if($this->option('defaultMember')){
        $this->info('Creating the member role');
        $cat = $this->ask('For which category do you want to configure the member?');
        if($cat == ""){$this->error('The category should not be blank!');} else {
          $permissions = array();
          if ($this->confirm('Do you wish members to create polls in this category?')) {
            $p = Permission::where('name', config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollWithCategory') . $cat)->first();
            if($p){
              array_push($permissions, $p);
            } else {
              array_push($permissions, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollWithCategory') . $cat]));
            }
          }
          if ($this->confirm('Do you wish members to see polls in this category even when the category is protected? (Recommended)')) {
            $p = Permission::where('name', config('larapolls.permissions.prefix') . config('larapolls.permissions.showPollWithCategory') . $cat)->first();
            if($p){
              array_push($permissions, $p);
            } else {
              array_push($permissions, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.showPollWithCategory') . $cat]));
            }
          }
          if(count($permissions) > 0){
            $role = Role::create(['name' => config('larapolls.permissions.prefix') . 'member_' . $cat]);
            $role->syncPermissions($permissions);

            if ($this->confirm('Do you want to add users to the role?')) {
              while(true){
                $id = $this->ask('Please enter the ID of the user you want to add to this role! Repeat for every user you want to add. To exit enter a invalid id!');
                $u = User::where('id', $id)->first();
                if($u){
                  $u->assignRole('larapolls_member_'.$cat);
                } else {
                  $this->error('User with id ' . $id . " could not be found!");
                  break;
                }
              }
            }
            $this->info('Everthing is set up!');
          } else {
            $this->error('No Permissions would be added!');
          }
        }
      }
    }
}
