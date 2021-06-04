.PHONY: update-dev-database
update-dev-database:
	APP_ENV=dev ./bin/console doctrine:schema:drop --force --no-interaction
	APP_ENV=dev ./bin/console doctrine:schema:update --force --no-interaction
	APP_ENV=dev ./bin/console doctrine:fixtures:load --no-interaction

.PHONY: update-test-database
update-test-database:
	APP_ENV=test ./bin/console doctrine:schema:drop --force --no-interaction
	APP_ENV=test ./bin/console doctrine:schema:update --force --no-interaction
	APP_ENV=test ./bin/console doctrine:fixtures:load --no-interaction

.PHONY: run-tests
run-tests:
	APP_ENV=test ./bin/phpunit
