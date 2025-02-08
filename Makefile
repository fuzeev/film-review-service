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

test:
	bin/phpunit

full-check: ecs stan psalm test
