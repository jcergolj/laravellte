# Boilerplate for Laravel admin panel

## Intro
<table>
<tr>
<td>
<img src="https://github.com/jcergolj/laravellte-docs/blob/master/login.png" width="250px">
</td>
<td>
<img src="https://github.com/jcergolj/laravellte-docs/blob/master/users.png" width="250px">
</td>
<td style="margin-left:20px">
<img src="https://github.com/jcergolj/laravellte-docs/blob/master/profile.png" width="250px">
</td>
</tr>
</table>

This is a boilerplate for opinionated Laravel admin panel build with Alpinejs, Livewire and Turbolinks mimicking SPA like apps. 

## Contents
- [Have total control of the code](#control)
- [Features](#features)
- [Installation](#installation)
- [Care for the code](#code)
- [Potentially useful code snippets](#snippets)
- [Licence](#licence)
- [Contributors](#contributors)

## Have total control of the code
Sometimes packages are to **too big or too cumbersome** to use. Other times package **doesn't have a critical feature** that you are looking for.

What you are missing is having control over the code, and now you have it!
Don't like how a new user is added. No problem. You can amend the code however you like. No more forking of packages and messing with their code.
**The idea is to create branches of common features and make them available for others to merge them into their master branch.**

## Features
* Laravel 7, Alpinejs, Livewire, Turbolinks, AdminLTE theme :heavy_check_mark:
* Login :heavy_check_mark:
* Forgot Password :heavy_check_mark:
* CRUD for Users :heavy_check_mark:
* Welcome email for a new user with a link for setting up a new password :heavy_check_mark:
* CRUD for roles (basic auth system) :heavy_check_mark:
* Option to assign route based permissions to role :heavy_check_mark:
* Profile with change password, email and user's image option :heavy_check_mark:
* Confirmation email to confirm a new user's email :heavy_check_mark:
* CI included (github actions) :heavy_check_mark:
* Over 200 tests included :heavy_check_mark:

## Installation

After installing <a href="https://laravel.com/docs/7.0/">Laravel</a> you should run those commands:
```
cp .env.example .env
npm install
npm run dev
composer cghooks update
```

## Care for the code
Let's face it. Sometimes we are sloppy, and we don't take the best care of the code. I added some useful packages (isn't it ironic) to take as much burden off developer as possible.

- Before you commit, the code is auto-styled according to Laravel standards using [matt-allan/laravel-code-style package](https://github.com/matt-allan/laravel-code-style).
- Next [nunomaduro/phpinsights](https://github.com/nunomaduro/phpinsights) package inspects the code and alerts if code is not in the best shape.
```
"php artisan insights --no-interaction --min-quality=90 --min-complexity=85 --min-architecture=90 --min-style=95"
```
- When testing [brianium/paratest](https://github.com/paratestphp/paratest) package runs tests in parallel.
```
./vendor/bin/paratest --processes 2 --runner=WrapperRunner
```
- Are tests still slow? [johnkary/phpunit-speedtrap](https://github.com/johnkary/phpunit-speedtrap) package finds the slow tests for you.
- Lastly [brainmaestro/composer-git-hooks](https://github.com/BrainMaestro/composer-git-hooks) package is utilized so everything is done automatically.
<a href="https://github.com/jcergolj/laravellte/blob/master/composer.json#L45">See how</a>

## Potentially useful code snippets (actively added)
Here are some ideas how you could tackle common problems:

## License
Licensed under the [MIT license](https://github.com/deployphp/deployer/blob/master/LICENSE)

## Contributors
<table>
<tr>
<td>
<a href="https://github.com/jcergolj">
<img src="https://avatars0.githubusercontent.com/u/6940394?s=460&amp;u=b4eaa035a3526a442d7d09dbf4d9d3ca63bfc1a5&amp;v=4" width="100px">
<br />
<sub>
<b>Janez Cergolj</b>
</sub>
</a>
</td>
<td>
<a href="https://github.comq/horaciod">
<img src="https://avatars3.githubusercontent.com/u/1373814?s=400&u=eee905c70aa654bd5ee2aba896e531ab6b7949d4&v=4" width="100px">
<br />
<sub>
<b>Horacio Degiorgi</b>
</sub>
</a>
</td>
<td>
<a href="https://github.com/ChrisThompsonTLDR">
<img src="https://avatars0.githubusercontent.com/u/348801?s=400&u=c87a0ad57588c43838f95899e6dcd1ef678e5793&v=4" width="100px">
<br />
<sub>
<b>Chris Thompson</b>
</sub>
</a>
</td>
</tr>
</table>
