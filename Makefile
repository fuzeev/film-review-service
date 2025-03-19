ecs:
	vendor/bin/ecs check

stan:
	vendor/bin/phpstan analyse

ecs-fix:
	vendor/bin/ecs check --fix

psalm:
	vendor/bin/psalm

psalm-info:
	vendor/bin/psalm --show-info=true

fixtures:
	symfony console doctrine:fixtures:load --env=test --no-interaction --purge-with-truncate

test:
	bin/phpunit

full-check: ecs stan psalm test

up:
	@cat .env.local .env.dev .env > .env.combined && \
	docker compose --env-file .env.combined up -d && \
	rm .env.combined

down:
	docker compose down --remove-orphans

restart: down up
