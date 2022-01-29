# Fully customizable and tests supported Laravel admin dashboard 2.0

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

This is a boilerplate for opinionated Laravel 8.0 admin panel build with AdminLTE 3.0 theme, Alpinejs 2.0, Livewire 2.0, supported with tests and optional feature branches.
<br/>
<br/>
**You can check this repo I am actively working on [Laravel Castra](https://github.com/jcergolj/castra). Same idea, different tools (Hotwire Turbo).**

## Contents
- [Have total control of the code](#have-total-control-of-the-code)
- [Summary](#summary)
- [Feature Branches](#feature-branches)
- [Installation](#installation)
- [Care for the code](#care-for-the-code)
- [Files scaffolding](#files-scaffolding)
- [Authorisation](#authorisation)
- [Licence](#licence)
- [Contributors](#contributors)

## Have total control of the code
Sometimes packages are to **too big or too cumbersome** to use. Other times package **doesn't have a critical feature** that you are looking for and you are doing some hacks to get around it.

What you are missing is having control over the code, and now you have it!
Don't like how a new user is added. No problem. You can amend the code however you like. No more forking packages and messing with their code.
**The idea is to create branches of standard features and make them available for others to merge them into their master branch.**

## Summary
* Laravel 8.0, Alpinejs, Livewire 2.0, AdminLTE theme 3.0 :heavy_check_mark:
* Login :heavy_check_mark:
* Forgot Password :heavy_check_mark:
* CRUD for Users :heavy_check_mark:
* Welcome email for a new user with a link for setting up a new password :heavy_check_mark:
* CRUD for roles (basic auth system) :heavy_check_mark:
* Option to assign route based permissions to role :heavy_check_mark:
* Profile with change password, email and user's image option :heavy_check_mark:
* Confirmation email to confirm a new user's email :heavy_check_mark:
* File scaffolding :heavy_check_mark:
* CI included (github actions) :heavy_check_mark:
* Over 200 tests included :heavy_check_mark:

## Feature Branches
Here is the list of supported feature branches. By merging them into master you unlock new features.
1. [User Registration](https://github.com/jcergolj/laravellte/tree/feature/registration)
2. [Simple Role Based Authorisation](https://github.com/jcergolj/laravellte/tree/feature/simple-role-based-auth)

## Installation
After installing <a href="https://laravel.com/docs/8.0/">Laravel</a> you should run those commands:
```
git clone https://github.com/jcergolj/laravellte.git
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run dev
composer cghooks update
php artisan migrate:fresh --seed
```

## Care for the code
Let's face it. Sometimes we are sloppy, and we don't take the best care of the code. I added some useful packages (isn't it ironic) to take as much burden off developer as possible.

- Before you commit, the code is auto-styled according to Laravel standards using [matt-allan/laravel-code-style package](https://github.com/matt-allan/laravel-code-style).
- Next [nunomaduro/phpinsights](https://github.com/nunomaduro/phpinsights) package inspects the code and alerts if code is not in the best shape.
```
"php artisan insights --no-interaction --min-quality=90 --min-complexity=85 --min-architecture=90 --min-style=95"
```
- Are tests still slow? [johnkary/phpunit-speedtrap](https://github.com/johnkary/phpunit-speedtrap) package finds the slow tests for you.
- Lastly [brainmaestro/composer-git-hooks](https://github.com/BrainMaestro/composer-git-hooks) package is utilized so everything is done automatically.
<a href="https://github.com/jcergolj/laravellte/blob/master/composer.json#L45">See how</a>

## Files Scaffolding
For CRUD actions you might consider using built-in files scaffolding command. It generates files for Index, Create, Show, Edit and Delete actions like this:
```
php artisan make:ltd-component bla --index --create --show --edit --delete
```
You can omit any of the options. If you wish you can update the `stubs` files as you like.
There are comments in scaffolded files acting as a reminder for you to amend the code. You can find then by typing: `index-review`, `create-review`, `show-review`, `edit-review`, `delete-review`. Factory and Model scaffolding aren't included in this command.

## Authorisation
Laravellte uses role - permissions based authorisation system. Only users with Admin role can add new roles and assign permissions to it.

### About permissions
For new resources permissions are added through [PermissionsTableSeeder](https://github.com/jcergolj/laravellte/blob/master/database/seeds/PermissionsTableSeeder.php). By convention the main permissions type are *index*, *create*, *edit*, *show*, and *delete* with resource in plural prefix. Example: *users.index*. Having said that, you are free to add your own. However you'll have to review/amend the code.
<br/>
Based on convention route names must be on of those types: *index*, *create*, *edit*, *show*, and *delete*.
[See example](https://github.com/jcergolj/laravellte/blob/master/routes/web.php#L49).
<br/>
For livewire components the convention for naming them is as follows: you have to use one those types follow by resource name and then Component. e.g. [IndexUserComponent](https://github.com/jcergolj/laravellte/blob/master/app/Http/Livewire/IndexUserComponent.php)
All Livewire components must use [HasLivewireAuth](https://github.com/jcergolj/laravellte/blob/master/app/Http/Livewire/IndexUserComponent.php#L11) trait. Here is [implementation](https://github.com/jcergolj/laravellte/blob/master/app/Http/Livewire/HasLivewireAuth.php).

### Owner restricted for index pages
When adding permission to the role, there is an extra filed called *owner_restricted*.
If *owner restricted* field is **true** for any index page user with that permission can only see its own resources. However, in order this to work, resource must have [owner_id filed](https://github.com/jcergolj/laravellte/blob/master/app/Providers/AppServiceProvider.php#L14) and *VisibleTo* global attached in [boot method](https://github.com/jcergolj/laravellte/blob/master/app/Models/User.php#L46).

### Owner restricted for show, edit, delete actions
If *owner restricted* field is **true** for *show*, *edit* and *delete* types, user can only amend resources that he owns.

### Owner restricted for create
For create types *owner restricted* is ignored.

### For Route Gate
In the core of it is [ForRouteGate](https://github.com/jcergolj/laravellte/blob/master/app/Services/ForRouteGate.php) that handles authorisation. The honourable mention goes to [Authorisation Middleware](https://github.com/jcergolj/laravellte/blob/master/app/Http/Middleware/Authorisation.php).

### Authorisation cookbook
1. Apply permissions to the role.
2. Make sure that **Authorisation** middleware is applied to resource's routes [Example](https://github.com/jcergolj/laravellte/blob/master/routes/web.php#L48)
3. Make sure routes and permissions are named **resources.index** (according to convention) [Example](https://github.com/jcergolj/laravellte/blob/master/routes/web.php#L49)
4. Make sure **VisibleTo** global scope is applied to models [Example](https://github.com/jcergolj/laravellte/blob/master/app/Models/User.php#L51)
5. Make sure **HasLivewireAuth** trait is applied to all Livewire Components [Example](https://github.com/jcergolj/laravellte/blob/master/app/Http/Livewire/CreateUserComponent.php#L16)

### Warning
**For Admin Role permissions restriction do not apply.**

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
<a href="https://github.com/horaciod">
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
