COMPOSE_FILE = -f docker-compose.yml --env-file .env

up:
	docker compose $(COMPOSE_FILE) up -d --build

down:
	docker compose $(COMPOSE_FILE) down

log:
	docker compose $(COMPOSE_FILE) logs -f

bash:
	docker compose $(COMPOSE_FILE) exec backend bash

artisan:
	docker compose $(COMPOSE_FILE) exec backend php artisan $(filter-out $@,$(MAKECMDGOALS))

test:
	docker compose $(COMPOSE_FILE) exec backend ./vendor/bin/phpunit

test-unit:
	docker compose $(COMPOSE_FILE) exec backend ./vendor/bin/phpunit --filter Unit

test-feature:
	docker compose $(COMPOSE_FILE) exec backend ./vendor/bin/phpunit --filter Feature

lint:
	docker compose $(COMPOSE_FILE) exec backend ./vendor/bin/phpstan analyse ./app

# прример запуска джобы dispatch(new \App\Jobs\GetNewsJob());
tinker:
	docker compose $(COMPOSE_FILE) exec backend php artisan tinker


%:
    @:
