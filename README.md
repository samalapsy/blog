
## Simple Blogging Platform

This is a simple blogging platform built on Laravel based on the briefing [here](https://www.notion.so/Web-Developer-0cdf0bb1015d4e5c94b62b3fe61ee621).
## Env Variables
Add or update the following env varaibles.

- BLOG_IMPORT_URL -> https://sq1-api-test.herokuapp.com/posts
- LOG_CHANNEL -> daily
- APP_TIMEZONE -> (optional), you'll need this if you want the app to run in your time zone.
- CACHE_DRIVER -> redis
- QUEUE_CONNECTION -> redis
- DB_ENGINE -> (optional) innodb
- APP_DEBUG -> false

## Installation

Ensure you have the following prerequisite on your machine

- PHP >=7.3 AND <= 8.0
- MySQL
- Redis
- comoposer update // we'll need the dev dependencies for the testing
- Run `php artisan migrate --seed` // this would seed over 30k data and 1k users
- Run `php artisan view:cache`
- Run `php artisan route:cache`
- Run `php artisan config:cache`
- Run `php artisan optimize`

- Run `npm install`
- Run `npm run prod`


### For Testing
- Run `php artisan test`

### To execute the Background jobs, run the following command
- Run `php artisan schedule:work`
- Run `php artisan queue:work --tries=3 --timeout=60`
- Run `php artisan queue:retry all`

