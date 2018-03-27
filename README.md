# Larapolls
Laravel package for displaying, creating and managing polls with permissions!

* [Installation](#installation)
* [Usage](#usage)

## Installation
You can install the package via composer:

``` bash
composer require hannoma/larapolls
```

In Laravel 5.5 the service provider will automatically get registered. In older versions of the framework just add the service provider in `config/app.php` file:

```php
'providers' => [
    // ...
    Hannoma\Larapolls\LarapollsServiceProvider::class,
];
```

You can publish the package files with:

```bash
php artisan vendor:publish --provider="Hannoma\Larapolls\LarapollsServiceProvider"
```

Because this package uses [spatie/laravel-permission](https://github.com/spatie/laravel-permission) for the permissions, you should publish the migration files of that package too:

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"
```

After the migration has been published you can create the poll and permission-tables by running the migrations:

```bash
php artisan migrate
```

If you want to change the permission prefixes or keys you should do this now in the configuration file located in config/larapolls.php (Not recommended):

```php
/*
  |--------------------------------------------------------------------------
  | Permissions
  |--------------------------------------------------------------------------
  |
  | Here you can specify the permission names
  | If you already have run "php artisan larapolls:setup_admin {user_id}", do not change this!
  |
  */
  'permissions' => [
    //Prefix for all permissions
    'prefix' => 'larapolls_',
    // The user is allowed to create a new poll
    'createPoll' => 'createPoll',
    // The user is allowed to create a sticky poll
    'createPollSticky' => 'createPollSticky',
    //The user is allowed to create a poll where multiple options can be checked
    'createPollMultiple' => 'createPollMultiple',
    // The user is allowed to create a poll where negative votes are allowed
    // REQUIRES multiple Poll !
    'createPollContra' => 'createPollContra',
    //The user is allowed to unlock/allow other polls
    'allowPoll' => 'allowPoll',
    //the user is allowed to delete any poll
    'deletePoll' => 'deletePoll',
    //The user is allowed to create a poll with any (existing or not) category
    'createNewCategory' => 'createNewCategory',
    //The user can view polls of all categories even protected ones
    'showAllCategories' => 'showAllCategories',
    //The user is allowed to create polls (only) in this category
    //This can be set for more than one category
    //Please give the permission: {PREFIX for Larapolls}_createPollWithCategory_{Your Category goes here}
    'createPollWithCategory' => 'createPollWithCategory_',
    //The user is allowed to view polls of the protected category
    //Please give the permission: {PREFIX for Larapolls}_showPollWithCategory_{Your Protected Category goes here}
    'showPollWithCategory' => 'showPollWithCategory_',
  ],
```


## Usage
First, add the `Spatie\Permission\Traits\HasRoles` trait to your `User` model(s).
Please use the user model `App\User`, because otherwise there will be errors using Larapolls:

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    // ...
}
```

If you want to setup your user as an Larapolls admin run this artisan command:
```bash
php artisan larapolls:setup_admin {Here goes the ID of your user model}
```
This command adds the role `larapolls_admin` which has all the relevant permissions.
As an admin you can create, allow and delete polls!


### Displaying polls
All polls are displayed on the `larapolls.home` route.

You can display polls of a category with the `larapolls.category` route which takes the parameter `category`.

You can also display a poll with a given id on any page with:
```
{!! Hannoma\Larapolls\PollDrawer::draw($poll_id) !!}
```
If you use this command, make sure that you import the Font Awesome version 5.x in your page

If you have categories that shall be only accessed by admins or users with permission, add your category in the `protectedCategories` array in the config file and give the users that you want to access this category the appertaining permission:
```php
$user->givePermissionTo('larapolls_showPollWithCategory_{Your category goes here}');
```
If you have not create this permission before, you may want to do this:
```php
use Spatie\Permission\Models\Permission;

$permission = Permission::create(['name' => 'larapolls_showPollWithCategory_{Your category goes here}']);
```
