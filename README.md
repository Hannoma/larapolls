# Larapolls
Laravel package for displaying, creating and managing polls with permissions!

* [Installation](#installation)
* [Configuration](#configuration)
    * [Localization](#localization)
    * [Views](#views)
* [Usage](#usage)
   *[Permission Setup](#permission-setup)
   *[Displaying Polls](#displaying-polls)
   

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

## Configuration
All important configuration options can be found in the `config/larapolls.php` file:
```php
<?php
return [
  //IMPORTANT: THIS PACKAGE USES USER MODEL (app/User.php)!!!

  /*
  |--------------------------------------------------------------------------
  | User name variable name
  |--------------------------------------------------------------------------
  |
  | Here you can specify how the username database field is set for the user
  |
  */
  'username_key' => 'name',

  /*
  |--------------------------------------------------------------------------
  | Basic settings
  |--------------------------------------------------------------------------
  |
  | maxOptionCount: This is the limit for options a poll can have if it is created
  | over the UI way.
  |
  | maxUnallowedPolls: If a user does not have the appertaining permission,
  | a poll created by this user is not allowed. This means that only this user
  | and admins can see the poll. This value sets the maximum amount of polls that
  | are not allowed for a user. If this maximum is reached, the user can not create
  | any polls until an admin allows some polls of the user.
  |
  */
  'maxOptionCount' => 10,
  'maxUnallowedPolls' => 2,

  /*
  |--------------------------------------------------------------------------
  | Bootstrap Version
  |--------------------------------------------------------------------------
  |
  | Based on the Bootstrap Version you are using the views change
  | true = Bootstrap Version 4.x
  | false = Bootstrap Version 3.x
  |
  */
  'bootstrap_v4' => true,

  /*
  |--------------------------------------------------------------------------
  | Do you use fontawesome Version 5?
  |--------------------------------------------------------------------------
  |
  | This package uses icons of fontawesome Version 5.X
  | If you are already using Font Awesome Version 5.X in your project, then set
  | this value to true.
  |
  */
  'fontawesome_v5' => false,

  /*
  |--------------------------------------------------------------------------
  | Date Format
  |--------------------------------------------------------------------------
  |
  | Please enter your date format Here
  | d = DAY
  | m = MONTH
  | Y = YEAR
  |
  */
  'date_format' => 'd.m.Y',

  /*
  |--------------------------------------------------------------------------
  | The master layout file for your site
  |--------------------------------------------------------------------------
  |
  | By default Laravel's master file is the layouts.app file, but if your
  | master layout file is somewhere else, you can specify it below
  |
  */
  'master_file_extend' => 'layouts.app',

  /*
  |--------------------------------------------------------------------------
  | Auth Middleware
  |--------------------------------------------------------------------------
  |
  | By default Laravel's auth middleware is 'auth', but if your
  | auth middleware is called anything else, you can specify it below
  |
  */
  'authMiddleware' => 'auth',

  /*
  |--------------------------------------------------------------------------
  | Poll Routes
  |--------------------------------------------------------------------------
  |
  | Here you can specify the specific routes for larapolls.
  | home: This is the prefix for larapolls
  | profile: enter the route name for your user profile
  | profile_argument: Surely you are using a route parameter for your profile route. Set this setting to your parameter name
  | profile_arg_value: Set this to the database field of your user model which you are using to retrieve the user on the profile page
  | login: Set this to your login route
  |
  */
  'routes' => [
      'home'       => 'polls',
      'profile'    => 'profile',
      'profile_argument' => 'username',
      'profile_arg_value' => 'name',
      'login'      => 'login',
  ],

  /*
  |--------------------------------------------------------------------------
  | Permissions
  |--------------------------------------------------------------------------
  |
  | Here you can specify the permission names
  | !! If you already have run "php artisan larapolls:setup_roles" or added some permissions, do not change this!
  |
  */
  'permissions' => [
    //Prefix for all permissions
    'prefix' => 'larapolls_',

    // "STANDARD" PERMISSIONS

    // The user is allowed to create a new poll
      // BUT the user must have the permission to post the poll in a category
      // If the user has the permission to create new categories, he can choose freely
      // If the user has only one (or more) permissions to create a poll in a specific category (See below 'createPollWithCategory'),
      // he can choose from these categories
      // Therefore if the user has not any of these permissions, he can see the "create a poll" form, but CAN'T create a poll!
    'createPoll' => 'createPoll',
    // The user is allowed to create a sticky poll
    'createPollSticky' => 'createPollSticky',
    //The user is allowed to create a poll where multiple options can be checked
    'createPollMultiple' => 'createPollMultiple',
    // The user is allowed to create a poll where negative votes are allowed
    // REQUIRES multiple Poll !
    'createPollContra' => 'createPollContra',

    // ADMIN PERMISSIONS

    //The user is allowed to unlock/allow other polls
    'allowPoll' => 'allowPoll',
    //the user is allowed to delete any poll
    'deletePoll' => 'deletePoll',
    //The user is allowed to create a poll with any (existing or not) category
    'createNewCategory' => 'createNewCategory',
    //The user can view and vote polls of all categories even protected ones
    'showAllCategories' => 'showAllCategories',

    // THE FOLLOWING PERMISSIONS ARE CATEGORY SPECIFIC
    // These can be set for more than one category!

      // Specific categories
    //The user is able to allow polls of this category
    //Please give the permission: {PREFIX for Larapolls}_allowPollWithCategory_{Your Category goes here}
    'allowPollWithCategory' => 'allowPollWithCategory_',
    //The user is able to delete polls of this category
    //Please give the permission: {PREFIX for Larapolls}_deletePollWithCategory_{Your Category goes here}
    'deletePollWithCategory' => 'deletePollWithCategory_',
    //The user is allowed to create polls in this category
    //Please give the permission: {PREFIX for Larapolls}_createPollWithCategory_{Your Category goes here}
    'createPollWithCategory' => 'createPollWithCategory_',
      // Protected categories
    //The user is allowed to view and vote polls of the protected category
    //Please give the permission: {PREFIX for Larapolls}_showPollWithCategory_{Your Protected Category goes here}
    'showPollWithCategory' => 'showPollWithCategory_',
  ],
  //!! CAUTION: Every user gets these permissions!
    // Maybe you also want to specify a "general" category where ALL User can create Polls
    // Do this be adding the permission 'createPollWithCategory_{Your "general" category name}'
  'standard_permissions' => [
    'createPoll', 'createPollSticky', 'createPollMultiple', 'createPollContra'
  ],
  // The user is allowed to delete his own polls!
  'delete_own_poll' => true,

  /*
  |--------------------------------------------------------------------------
  | Protected Categories
  |--------------------------------------------------------------------------
  |
  | If you have categories that shall be only accessed by admins or users with
  | permission, add your category in this array and give the users that you want to
  | access this category the appertaining permission
  |
  */
  'protectedCategories' => [

  ],
];
```
NOTE: The permissions defined in the `standard_permissions` array are added to the role `larapolls_standard` when you first access the home route of Larapolls. If you want to add or remove permissions from this role later on, do this:
```php
use Spatie\Permission\Models\Role;

$role = Role::where('name', 'larapolls_standard')->first();
//Add a permission
$role->givePermissionTo('your permission');
//Remove a permission
$role->revokePermissionTo('your permission');
```
Or you can remove the role for some users:
```php
use Spatie\Permission\Models\Role;

$user->removeRole('larapolls_standard');
```

### Localization
After publishing the vendor files of this package, you can find the localization files in `resources/lang/vendor/larapolls/`.
Supported languages by this package are:
* English
* German

If you want to add your locale, create the folder `resources/lang/vendor/larapolls/YOUR LOCALE/`. Then just copy the `larapolls.php` localization file of another locale and translate the strings. If you add your locale, I would be very thankful if you create a pull request with your locale ^^

### Views
This package was created and designed with Bootstrap version 4.x, but gives you the option to use Bootstrap version 3.x instead. Therefor you need to change the value `bootstrap_v4` to `false` in the `config/larapolls.php` file.
The templates for Bootstrap version 4.x are called `*_v4.blade.php`, the templates for version 3.x `*_v3.blade.php`.
NOTE: Because the package was created with version 4.x, the view files for version 3.x contain the same code as the view files for version 4! So you need to change some things in order that they work with version 3.x!

#### Personalizing
After publishing the package vendor files, you can find the blade templates in `resources/views/vendor/larapolls/`.
All your changes in these files will be displayed on your site.
**NOTE: DO NOT change any if conditions or other blade commands otherwise this package may not work anymore!**

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
### Permission Setup
This package comes with artisan commands to setup permissions for different roles and categories.
Just run `php artisan larapolls:setup_roles` and use these options:
* `--A` or `--admin` for a admin role
* `--M` or `--moderator` for a moderator (bound to a specific category)
* `--D` or `--defaultMember` for a member of a specific category



### Displaying Polls
All polls are displayed on the `larapolls.home` route.

You can display polls of a category with the `larapolls.category` route which takes the parameter `category`.

You can also display a poll with a given id on any page with:
```
{!! Hannoma\Larapolls\PollDrawer::draw($poll_id) !!}
```
If you use this command, make sure that you import the Font Awesome version 5.x in your page.

If you have categories that shall be only accessed by admins or users with permission, add your category in the `protectedCategories` array in the config file and give the users that you want to access this category the appertaining permission:
```php
$user->givePermissionTo('larapolls_showPollWithCategory_{Your category goes here}');
```
If you have not create this permission before, you may want to do this:
```php
use Spatie\Permission\Models\Permission;

$permission = Permission::create(['name' => 'larapolls_showPollWithCategory_{Your category goes here}']);
```
