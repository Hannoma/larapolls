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
    protected $signature = 'larapolls:setup_admin {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup the admin permissions for this package';

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
      $adminRole = Role::create(['name' => config('larapolls.permissions.prefix') . 'admin']);
      $adminPermission = array();
      array_push($adminPermission, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.createPoll')]));
      array_push($adminPermission, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollSticky')]));
      array_push($adminPermission, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollMultiple')]));
      array_push($adminPermission, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.createPollContra')]));
      array_push($adminPermission, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.allowPoll')]));
      array_push($adminPermission, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.deletePoll')]));
      array_push($adminPermission, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.createNewCategory')]));
      array_push($adminPermission, Permission::create(['name' => config('larapolls.permissions.prefix') . config('larapolls.permissions.showAllCategories')]));
      $adminRole->syncPermissions($adminPermission);
      User::where('id', $this->argument('user_id'))->first()->assignRole(config('larapolls.permissions.prefix'). 'admin');
    }
}
