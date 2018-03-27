<?php
return [
  //IMPORTANT: THIS PACKAGE USES USER MODEL (app/User.php)!!!

  /*
  |--------------------------------------------------------------------------
  | User name variable name
  |--------------------------------------------------------------------------
  |
  | Here you can specify how the database field for the name of the user is set
  |
  */
  'username_key' => 'name',

  /*
  |--------------------------------------------------------------------------
  | Permissions
  |--------------------------------------------------------------------------
  |
  | Here you can specify how the permissions are set
  |
  */
  'permissions' => [
    'prefix' => 'larapolls_',
    'createPoll' => 'createPoll',
    'createPollSticky' => 'createPollSticky',
    'createPollMultiple' => 'createcreatePollMultiple',
    'createPollContra' => 'createPollContra',
    'allowPoll' => 'allowPoll',
    'createNewCategory' => 'createNewCategory', //The user can create a NEW Category
    'createPollWithCategory' => 'createPollWithCategory_', //Please give the permission: {PREFIX for Larapolls}_createPollWithCategory_{Your Category goes here}
    'showPollWithCategory' => 'showPollWithCategory_', //Please give the permission: {PREFIX for Larapolls}_showPollWithCategory_{Your Category goes here}
    'showAllCategories' => 'showAllCategories',
  ],

  'protectedCategories' => [
    'Category1',
    'Category2'
  ],

  'authMiddleware' => 'auth',
  'maxOptionCount' => 10,
  'maxUnallowedPolls' => 2,

  /*
  |--------------------------------------------------------------------------
  | Bootstrap Version
  |--------------------------------------------------------------------------
  |
  | Based on the Bootstrap Version you are using for Laravel the views must change
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
  |
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
  | Poll Routes
  |--------------------------------------------------------------------------
  |
  | Here you can specify the specific routes for the different sections of
  | your forum.
  |
  */
  'routes' => [
      'home'       => 'polls',
      'user_profile'=> 'profile',
      'register'   => 'register',
      'login'      => 'login',
  ],
];
