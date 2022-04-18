
## Simple Blogging Platform

This is a simple blogging platform built on Laravel as it exhibit the following features/techonologies

- Caching
- Queues
- Jobs
- Commands
- PHPUnit Testing
- Remote HTTP Call via HTTP Clieints


## Env Variables
Add or update the following env varaibles.

- BLOG_IMPORT_URL -> https://sq1-api-test.herokuapp.com/posts
- LOG_CHANNEL -> daily
- APP_TIMEZONE -> (optional), you'll need this if you want the app to run in your time zone.
- CACHE_DRIVER -> file|database|redis (if you're using redis, ensure you have redis installed on your PC)
- QUEUE_CONNECTION -> database|redis
- DB_ENGINE -> (optional) innodb

## Installation

Ensure you have the following prerequisite on your machine

- PHP >=7.3 AND <= 8.0
- MySQL
- Redis (optional, needed only when you are using rdis for queues and caching)
- Run `php artisan key:generate`
- Run `php artisan migrate --seed`
- Run `php artisan route:cache`
- Run `php artisan config:cache`

### To execute the Background jobs, run the following command
- Run `php artisan schedule:work`
- Run `php artisan queue:work --tries=3 --timeout=60`
- Run `php artisan queue:retry all`


For Production user ensure you swap the queues and cache into Redis
