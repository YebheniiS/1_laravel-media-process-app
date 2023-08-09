Installation
============
Update `.env` to your liking.

```
composer install
php artisan migrate
```

Before Seeding change the user details in the `UsersTableSeeder` to your liking for 1st user .
then seed and revert changes so you don't commit them later .

``` php artisan db:seed ```

# Database Evaluation

To be deleted (Model / Controllers / table etc)
- Chapters
- jvzoo payments ?
- languages ?
- payments?
- player versions (update publish  code when deleted)
- render_queue
- stream_conversion_logs

Not in GraphQL
- Password Reset
