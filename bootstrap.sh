#!/bin/bash

# Create the test DB only if it doesn't exist
docker-compose exec php php bin/console doctrine:database:create --env=test --if-not-exists

# Create the DB only if it doesn't exist
docker-compose exec php bin/console doctrine:database:create --if-not-exists

docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction

docker-compose exec php bin/console doctrine:fixtures:load --append