.PHONY: help build up down shell test install clean

help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Available targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  %-15s %s\n", $$1, $$2}' $(MAKEFILE_LIST)

DOCKER_COMPOSE = docker compose

build: ## Build Docker image
	$(DOCKER_COMPOSE) build

up: ## Start jukebox container
	$(DOCKER_COMPOSE) up

down: ## Stop jukebox container
	$(DOCKER_COMPOSE) down

shell: ## Open shell in container
	$(DOCKER_COMPOSE) exec app /bin/bash

test-init: ## Initialize test database (creates DB and runs migrations)
	$(DOCKER_COMPOSE) exec app php bin/console doctrine:database:create --env=test --if-not-exists
	$(DOCKER_COMPOSE) exec app php bin/console doctrine:migrations:migrate --env=test --no-interaction

test: ## Run tests
	$(DOCKER_COMPOSE) exec app env APP_ENV=test vendor/bin/phpunit

install: ## Install dependencies
	$(DOCKER_COMPOSE) exec app composer install

migrate: ## Run database migrations
	$(DOCKER_COMPOSE) exec app php bin/console doctrine:migrations:migrate --no-interaction

clean: ## Clean vendor and cache
	$(DOCKER_COMPOSE) exec app rm -rf vendor composer.lock var/cache/*

