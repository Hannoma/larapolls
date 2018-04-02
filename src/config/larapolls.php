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
  | If you already have run "php artisan larapolls:setup_roles" or added some permissions, do not change this!
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
    //Please give the permission: {PREFIX for Larapolls}_allowPollWithCategory_{Your Category goes here}
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
