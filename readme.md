# Opinionated Laravel based admin panel [![Build Status](https://travis-ci.com/jcergolj/laravellte.svg?branch=master)](https://travis-ci.com/jcergolj/laravellte)

This is installation guide for opinionated Laravel based admin panel build with Alpinejs, Livewire and Trubolinks mimicking SPA like
apps.

~~# Warning! This repo is still in a early stage. Code changes a lot with only a init commit. I am going to release a version 1.0 by mid April.~~

I deiced to push back the release date. New expected date for version 1.0 is the end of April. I became @calebporzio sponsor, and I have access now to his course https://laravel-livewire.com/screencasts/installation. I plan to incorporate some of his tips and clean up code a bit. Do check his work out and consider supporting him. I would like to use livewire for changing the user's image and refactor modal pop-up warning and delete item feature.
You can check the current version out here: 

https://laralte.herokuapp.com/
email: admin@lte.com
password: password


## Features
* Laravel 7.0
* Packages
  * laravel/ui
  * barryvdh/laravel-debugbar
  * brianium/paratest
  * brainmaestro/composer-git-hooks
  * johnkary/phpunit-speedtrap
  * matt-allan/laravel-code-style
  * nunomaduro/phpinsights
* AdminLTE theme
* very basic user - role authorization builds in
* Almost everything is tested: Login, logout, forgot password, CRUD for Users, CRUD for Roles & Permissions

## Installation

### Step 1 - Install Laravel
You can find instructions here https://laravel.com/docs/7.0/

### Step 2 - Copy and amend .env file
```
cp .env.example .env
```

### Step 3 - Install npm dependencies
```
npm install
```

### Step 4 - Run npm
```
npm run dev
```

### Step 5 - Update hooks
```
composer cghooks update
```

### Others
1. To identify slow tests johnkary/phpunit-speedtrap is used.

2. You can run tests in parallel:
```
./vendor/bin/paratest --processes 2 --runner=WrapperRunner
```

3. Before files are committed they are formatted by matt-allan/laravel-code-style thanks to brainmaestro/composer-git-hooks.

4. All routes with auth middleware must have unique route names. Route names are used for authorization.

Happy coding!!!

## License
Licensed under the [MIT license](https://github.com/deployphp/deployer/blob/master/LICENSE