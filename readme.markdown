# Simplified Authorisation
Sometimes you don't need full permission-role based authorisation system. Simple role based is enough.
This feature allows you to use it.

## Installation
1. merge feature/registration branch to master:
```
git checkout master
git merge feature/simple-role-based-auth
```
2. run migrations
```
php artisan migrate
```
3. delete *readme.markdown* file
