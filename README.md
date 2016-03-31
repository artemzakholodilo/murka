For installing application run:

composer install

php ./app/console doctrine:schema:update --force

in ./app/config/parameters.yml write gmail account data

to run email queue watcher run:
php ./app/console mail:send