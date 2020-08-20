SHELL := /bin/bash

install:
	composer install
	./bin/console doctrine:migrations:migrate -n
	./bin/console doctrine:fixtures:load -n
.PHONY: install

tests:
	APP_ENV=test ./bin/console doctrine:schema:drop -fn
	APP_ENV=test ./bin/console doctrine:schema:update -fn
	APP_ENV=test ./bin/console doctrine:fixtures:load -n
	APP_ENV=test ./bin/phpunit
.PHONY: tests