# Leet Assessment

## Install

Clone this repository.

`git clone git@github.com:matheusmariano/leet-assessment.git`

Clone the Laradock repository.

`git submodules update --init --recursive`

Run the Docker Compose

`docker-compose up -d nginx mysql redis`

Enter the Docker container.

`docker-compose run workspace bash`

Install composer dependencies.

`composer install`

Create a `.env` file.

`cp .env.example .env`

Generate a new `APP_KEY`.

`php artisan key:generate`

Set the `.env` variable `QUEUE_DRIVER` to `redis`

Migrate database.

`php artisan migrate`

## Scheduled tasks

There are some scheduled tasks in this service. It's necessary to configure the cron to trigger the jobs properly.

`* * * * * php {path-to-this-service}/leet-assessment/artisan schedule:run >> /dev/null 2>&1`

### Create Snapshot Job

After configure the scheduled tasks, the service should run a daily job called `CreateSnapshot`. This job will check every every social network profile of every user and create daily snapshots. This job should run in a queue managed by the Redis, so don't forget to check the Redis config in `config/database.php` and start the queues

`php artisan queue:work`

## Tests

To run the tests, it's necessary to create a testing database called `leet_assessment_testing`.

The command to run the tests is

`vendor/bin/phpunit`

## API Docs

To see the API docs, install the Aglio

`npm install -g aglio`

run

`aglio -i api.apib --theme-full-width --no-theme-condense -s`

and open

`http://localhost:3000`
