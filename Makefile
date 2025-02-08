ecs:
	vendor/bin/ecs check src

stan:
	vendor/bin/phpstan analyse src

ecs-fix:
	vendor/bin/ecs check src --fix