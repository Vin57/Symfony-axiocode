## Lancer à la racine du projet

cd app && composer update && php bin/console doctrine:secrets:decrypt-to-local && docker-compose up --build 

## Dans le container app

php bin/console doctrine:database:create && \
php bin/console doctrine:schema:update --force && \
php bin/console doctrine:fixtures:load && \

## Dans postman accèder à la route http://localhost/api/product/{id_product}
