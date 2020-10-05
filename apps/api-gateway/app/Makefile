.PHONY: build-dev
build-dev:
	APP_ENV=dev composer install

.PHONY: build-test
build-test:
	APP_ENV=test composer install
	
.PHONY: build-stage
build-stage:
	APP_ENV=stage composer install --no-dev --optimize-autoloader
	APP_ENV=stage composer dump-autoload --optimize --no-dev --classmap-authoritative
	APP_ENV=stage ./app/bin/console cache:clear --no-interaction

.PHONY: build-prod
build-prod:
	APP_ENV=prod composer install --no-dev --optimize-autoloader
	APP_ENV=prod composer dump-autoload --optimize --no-dev --classmap-authoritative
	APP_ENV=prod ./app/bin/console cache:clear --no-interaction

.PHONY: migrate-dev
migrate-dev: build-dev
	APP_ENV=dev ./bin/console doctrine:migrations:migrate --no-interaction
	APP_ENV=dev ./bin/console doctrine:fixtures:load --no-interaction

.PHONY: migrate-stage
migrate-stage:
	APP_ENV=dev ./bin/console doctrine:migrations:migrate --no-interaction

.PHONY: migrate-prod
migrate-prod:
	APP_ENV=dev ./bin/console doctrine:migrations:migrate --no-interaction

.PHONY: run-test-all
run-test-all: build-test
	APP_ENV=test ./bin/console doctrine:schema:drop --force --no-interaction
	APP_ENV=test ./bin/console doctrine:schema:update --force --no-interaction
	APP_ENV=test ./bin/console doctrine:fixtures:load --no-interaction
	APP_ENV=test ./bin/phpunit