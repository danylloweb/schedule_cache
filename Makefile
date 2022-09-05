CONTAINER_NAME=project_schedule

install:
	cp .env.example .env
	make build
	make up
	make composer-install
	make clear

up:
	docker-compose up -d
	docker ps

down:
	docker-compose down

bash:
	docker exec -it $(CONTAINER_NAME) bash

build:
	docker-compose build

composer-install:
	docker exec $(CONTAINER_NAME) composer install --no-interaction --no-scripts
	docker exec $(CONTAINER_NAME) php artisan migrate

test:
ifdef FILTER
	make up
	make clear
	docker exec -t $(CONTAINER_NAME) composer unit-test -- --filter="$(FILTER)"
else
	make up
	make clear
	docker exec -t $(CONTAINER_NAME) composer unit-test
endif

logs:
	docker-compose logs --follow

clear:
	docker exec $(CONTAINER_NAME) sh -c "php artisan cache:clear"
	docker ps

