# Documentation

## Introduction
This is a PHP application that is a feeds reader. 
The app can read feeds from multiple feeds and store them to database using terminal commands.
Sample feeds http://www.feedforall.com/sample-feeds.htm. 
The project is built on framework Symfony 4.3

## Setup project

### Prerequisites:
- Operating system: Linux or Mac
- PHP 7.1 or above (required following extensions: ext-simplexml, ext-iconv, ext-ctype)
- MySQL 5.7
- composer
- make

### Steps to setup:
Open terminal at the project root directory
1. Install project's dependencies: `make app-install` 
2. Run `cp .env.example .env` and edit `DATABASE_URL` in file .env to your database credentials.
3. Setup database: `make app-recreate-db`

## How to use
Open terminal at the project root directory
1. To see the command usage, run `php bin/console app:import-feeds --help`
2. Example command `php bin/console app:import-feeds https://www.feedforall.com/sample.xml,https://www.feedforall.com/sample.xml ./test.log`
3. Run `php bin/console server:run` and go to `http://localhost:8000` to see the web interface.

## Development

### Testing
1. Edit `DATABASE_URL` in file .env.test to a testing database credentials.
2. Run `make testing-recreate-db` to setup testing database.
2. Run `php bin/phpunit` to execute unit tests
