phpcbf class-*
phpcs class-*

## Install
docker-compose up -d
docker-compose exec wordpress /usr/local/bin/exec-phpunit.sh tm_wp_plugin $1

# Scafold tests - now part of /tests
# docker-compose exec wordpress wp scaffold plugin-tests tm_wp_plugin --allow-root
