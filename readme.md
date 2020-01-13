# Opinionated Laravel based admin panel

This is installation guide for opinionated Laravel based admin panel build with Alpinejs, Livewire and Trubolinks mimicking SPA like
apps with as much backend code as possible.

# Warning! This repo is still in a early stage. Code changes a lot with only a init commit. I am going to release a version 1.0.0 by mid April.

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
* very basic user - role authorization build in
* Almost everything is tested: Login, logout, forgot password, CRUD for Users, CRUD for ROLES...

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
./vendor/bin/paratest --processes 6
```

3. Before files are committed they are formatted by matt-allan/laravel-code-style thanks to brainmaestro/composer-git-hooks.

Happy coding!!!

## License
Licensed under the [MIT license](https://github.com/deployphp/deployer/blob/master/LICENSE