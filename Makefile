# Makefile

# Run Symfony console commands
console:
	docker-compose exec php bin/console $(cmd)

# Database
migrate:
	docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction

make-migrate:
	docker-compose exec php bin/console doctrine:migrations:diff  --no-interaction

db-create:
	docker-compose exec php bin/console doctrine:database:create --if-not-exists

db-drop:
	docker-compose exec php bin/console doctrine:database:drop --force --if-exists

schema-update:
	docker-compose exec php bin/console doctrine:schema:update --force
db-seed:
	docker-compose exec php bin/console doctrine:fixtures:load --append

# Composer
composer:
	docker-compose exec php composer $(cmd)

# Symfony cache
cache-clear:
	docker-compose exec php bin/console cache:clear

# Run tests
test:
	APP_ENV=test docker-compose exec php ./bin/phpunit

# run consumer
consume:
	docker-compose exec php php bin/console messenger:consume async
