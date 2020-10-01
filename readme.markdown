# Register New User
This feature allows user to register. By default *manager* role is assigned to each registered user.

## Installation
1. merge feature/registration branch to master:
```
git checkout master
git merge feature/registration
```
2. run migrations
```
php artisan migrate
```
3. delete *readme.markdown* file

## Notes
1. Role needs to be assigned to the user. By default it is *manager* role, however you can change it **laravellte.php** config file
2. *owner_id* field is set to null
3. Email with verification link is sent
