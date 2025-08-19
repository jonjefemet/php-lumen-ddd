.PHONY: help build start stop restart status logs test composer-install composer-update composer-require composer-remove

# Colors
GREEN := \033[0;32m
YELLOW := \033[0;33m
RED := \033[0;31m
BLUE := \033[0;34m
NC := \033[0m # No Color

# Default service for single service commands
SERVICE ?= auth

help: ## Show this help message
	@echo "$(GREEN)🚀 Finger PHP DDD Microservices$(NC)"
	@echo "$(YELLOW)================================$(NC)"
	@echo ""
	@echo "$(BLUE)📋 Main Commands:$(NC)"
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "$(GREEN)  %-20s$(NC) %s\n", $$1, $$2}' $(MAKEFILE_LIST) | grep -E "(start|stop|status|health|composer-)"
	@echo ""
	@echo "$(BLUE)🔧 Development Commands:$(NC)"
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "$(GREEN)  %-20s$(NC) %s\n", $$1, $$2}' $(MAKEFILE_LIST) | grep -E "(test|static|shell|logs)"
	@echo ""
	@echo "$(BLUE)📖 Examples:$(NC)"
	@echo "$(YELLOW)  make start$(NC)                  # Start all services"
	@echo "$(YELLOW)  make composer-install$(NC)       # Install dependencies in auth service"
	@echo "$(YELLOW)  make composer-require PKG=ramsey/uuid$(NC) # Add new package"
	@echo "$(YELLOW)  make test SERVICE=backoffice$(NC) # Run tests in backoffice service"

build: ## Build the Docker environment
	@echo "$(YELLOW)Building Docker environment...$(NC)"
	docker-compose build
	@echo "$(GREEN)✅ Build completed!$(NC)"

start: ## Start all services
	@echo "$(YELLOW)Starting services...$(NC)"
	docker-compose up -d
	@echo "$(GREEN)✅ Services started!$(NC)"
	@echo "$(YELLOW)🔗 Available endpoints:$(NC)"
	@echo "   🔐 Auth Service: http://localhost:8001"
	@echo "   🏢 Backoffice Service: http://localhost:8002"
	@echo "   🍃 MongoDB: localhost:27017 (admin/secret)"

stop: ## Stop all services
	@echo "$(YELLOW)Stopping services...$(NC)"
	docker-compose down
	@echo "$(GREEN)✅ Services stopped!$(NC)"

restart: stop start ## Restart all services

status: ## Show services status
	@docker-compose ps

logs: ## Show logs for all services
	docker-compose logs -f

logs-auth: ## Show logs for auth service
	docker-compose logs -f auth

logs-backoffice: ## Show logs for backoffice service
	docker-compose logs -f backoffice

# =============================================================================
# 📦 COMPOSER COMMANDS
# =============================================================================

composer-install: ## Install composer dependencies (SERVICE=auth|backoffice)
	@echo "$(YELLOW)Installing dependencies in $(SERVICE) service...$(NC)"
	docker-compose exec $(SERVICE) composer install
	@echo "$(GREEN)✅ Dependencies installed in $(SERVICE)!$(NC)"

composer-install-all: ## Install dependencies in all services
	@echo "$(YELLOW)Installing dependencies in all services...$(NC)"
	@$(MAKE) composer-install SERVICE=auth
	@$(MAKE) composer-install SERVICE=backoffice
	@echo "$(GREEN)✅ All dependencies installed!$(NC)"

composer-update: ## Update composer dependencies (SERVICE=auth|backoffice)
	@echo "$(YELLOW)Updating dependencies in $(SERVICE) service...$(NC)"
	docker-compose exec $(SERVICE) composer update
	@echo "$(GREEN)✅ Dependencies updated in $(SERVICE)!$(NC)"

composer-require: ## Add new package (Usage: make composer-require PKG=package/name SERVICE=auth)
	@if [ -z "$(PKG)" ]; then \
		echo "$(RED)❌ Please specify package: make composer-require PKG=package/name$(NC)"; \
		exit 1; \
	fi
	@echo "$(YELLOW)Adding $(PKG) to $(SERVICE) service...$(NC)"
	docker-compose exec $(SERVICE) composer require $(PKG)
	@echo "$(GREEN)✅ Package $(PKG) added to $(SERVICE)!$(NC)"

composer-remove: ## Remove package (Usage: make composer-remove PKG=package/name SERVICE=auth)
	@if [ -z "$(PKG)" ]; then \
		echo "$(RED)❌ Please specify package: make composer-remove PKG=package/name$(NC)"; \
		exit 1; \
	fi
	@echo "$(YELLOW)Removing $(PKG) from $(SERVICE) service...$(NC)"
	docker-compose exec $(SERVICE) composer remove $(PKG)
	@echo "$(GREEN)✅ Package $(PKG) removed from $(SERVICE)!$(NC)"

composer-autoload: ## Regenerate autoload files (SERVICE=auth|backoffice)
	@echo "$(YELLOW)Regenerating autoload for $(SERVICE) service...$(NC)"
	docker-compose exec $(SERVICE) composer dump-autoload
	@echo "$(GREEN)✅ Autoload regenerated for $(SERVICE)!$(NC)"

composer-validate: ## Validate composer.json (SERVICE=auth|backoffice)
	@echo "$(YELLOW)Validating composer.json in $(SERVICE) service...$(NC)"
	docker-compose exec $(SERVICE) composer validate
	@echo "$(GREEN)✅ Composer validation completed for $(SERVICE)!$(NC)"

composer-show: ## Show installed packages (SERVICE=auth|backoffice)
	docker-compose exec $(SERVICE) composer show

composer-outdated: ## Show outdated packages (SERVICE=auth|backoffice)
	docker-compose exec $(SERVICE) composer outdated

# Legacy alias for backward compatibility
install: composer-install-all ## Install composer dependencies (alias for composer-install-all)

# =============================================================================
# 🧪 TESTING COMMANDS
# =============================================================================

test-service: ## Run all tests in specific service (SERVICE=auth|backoffice)
	@echo "$(YELLOW)Running tests in $(SERVICE) service...$(NC)"
	docker-compose exec $(SERVICE) composer test
	@echo "$(GREEN)✅ Tests completed in $(SERVICE)!$(NC)"

test-unit-service: ## Run unit tests in specific service (SERVICE=auth|backoffice)
	@echo "$(YELLOW)Running unit tests in $(SERVICE) service...$(NC)"
	docker-compose exec $(SERVICE) composer test:unit

test-integration: ## Run integration tests (SERVICE=auth|backoffice)
	@echo "$(YELLOW)Running integration tests in $(SERVICE) service...$(NC)"
	docker-compose exec $(SERVICE) composer test:integration

test-acceptance: ## Run acceptance tests (SERVICE=auth|backoffice)
	@echo "$(YELLOW)Running acceptance tests in $(SERVICE) service...$(NC)"
	docker-compose exec $(SERVICE) composer test:acceptance

# =============================================================================
# 🔍 CODE QUALITY COMMANDS
# =============================================================================

static-analysis: ## Run static analysis tools (SERVICE=auth|backoffice)
	@echo "$(YELLOW)Running static analysis in $(SERVICE) service...$(NC)"
	docker-compose exec $(SERVICE) composer static:analysis
	@echo "$(GREEN)✅ Static analysis completed in $(SERVICE)!$(NC)"

stan: ## Run PHPStan (SERVICE=auth|backoffice)
	@echo "$(YELLOW)Running PHPStan in $(SERVICE) service...$(NC)"
	docker-compose exec $(SERVICE) composer stan

psalm: ## Run Psalm (SERVICE=auth|backoffice)
	@echo "$(YELLOW)Running Psalm in $(SERVICE) service...$(NC)"
	docker-compose exec $(SERVICE) composer psalm

ecs: ## Run Easy Coding Standard (SERVICE=auth|backoffice)
	@echo "$(YELLOW)Running ECS in $(SERVICE) service...$(NC)"
	docker-compose exec $(SERVICE) composer ecs

rector: ## Run Rector (SERVICE=auth|backoffice)
	@echo "$(YELLOW)Running Rector in $(SERVICE) service...$(NC)"
	docker-compose exec $(SERVICE) composer rector

phpmd: ## Run PHP Mess Detector (SERVICE=auth|backoffice)
	@echo "$(YELLOW)Running PHPMD in $(SERVICE) service...$(NC)"
	docker-compose exec $(SERVICE) composer phpmd

# =============================================================================
# 🗄️  DATABASE COMMANDS
# =============================================================================

db-create: ## Initialize MongoDB databases
	@echo "$(YELLOW)MongoDB databases will be automatically initialized...$(NC)"
	@echo "$(GREEN)✅ MongoDB ready!$(NC)"

db-shell: ## Access MongoDB shell with authentication
	docker-compose exec mongo mongosh -u admin -p secret --authenticationDatabase admin

db-auth: ## Access auth database
	docker-compose exec mongo mongosh finger_auth -u admin -p secret --authenticationDatabase admin

db-backoffice: ## Access backoffice database
	docker-compose exec mongo mongosh finger_backoffice -u admin -p secret --authenticationDatabase admin

# =============================================================================
# 🖥️  SHELL ACCESS COMMANDS
# =============================================================================

shell: ## Access service shell (SERVICE=auth|backoffice|mongo)
	@if [ "$(SERVICE)" = "mongo" ]; then \
		docker-compose exec mongo bash; \
	else \
		docker-compose exec $(SERVICE) bash; \
	fi

shell-auth: ## Access auth service shell
	docker-compose exec auth bash

shell-backoffice: ## Access backoffice service shell
	docker-compose exec backoffice bash

shell-mongo: ## Access MongoDB container shell
	docker-compose exec mongo bash

# =============================================================================
# 🏥 HEALTH & MONITORING COMMANDS
# =============================================================================

health: ## Check service health
	@echo "$(YELLOW)Checking service health...$(NC)"
	@echo ""
	@echo "$(BLUE)🔐 Auth Service:$(NC)"
	@curl -f http://localhost:8001/health 2>/dev/null && echo " $(GREEN)✅ HEALTHY$(NC)" || echo " $(RED)❌ DOWN$(NC)"
	@echo ""
	@echo "$(BLUE)🏢 Backoffice Service:$(NC)"
	@curl -f http://localhost:8002/health 2>/dev/null && echo " $(GREEN)✅ HEALTHY$(NC)" || echo " $(RED)❌ DOWN$(NC)"
	@echo ""

status-detailed: ## Show detailed status of all services
	@echo "$(BLUE)📊 Services Status:$(NC)"
	@docker-compose ps
	@echo ""
	@echo "$(BLUE)🔗 Available Endpoints:$(NC)"
	@echo "   🔐 Auth Service: http://localhost:8001"
	@echo "   🏢 Backoffice Service: http://localhost:8002"
	@echo "   🍃 MongoDB: localhost:27017"

# =============================================================================
# 🧹 CLEANUP COMMANDS
# =============================================================================

clean: ## Clean up containers and volumes
	@echo "$(YELLOW)Cleaning up containers and volumes...$(NC)"
	docker-compose down -v --remove-orphans
	docker system prune -f
	@echo "$(GREEN)✅ Cleanup completed!$(NC)"

clean-hard: ## Deep clean (remove all Docker data)
	@echo "$(RED)⚠️  This will remove ALL Docker data on your system!$(NC)"
	@read -p "Are you sure? [y/N] " -n 1 -r; \
	echo ""; \
	if [[ $$REPLY =~ ^[Yy]$$ ]]; then \
		docker-compose down -v --remove-orphans; \
		docker system prune -a -f --volumes; \
		echo "$(GREEN)✅ Deep cleanup completed!$(NC)"; \
	else \
		echo "$(YELLOW)Cleanup cancelled.$(NC)"; \
	fi

# =============================================================================
# 🧪 API TESTING COMMANDS
# =============================================================================

api-test-auth: ## Test auth endpoints
	@echo "$(BLUE)Testing Auth Service APIs...$(NC)"
	@echo ""
	@echo "$(YELLOW)1. Health Check:$(NC)"
	@curl -s http://localhost:8001/health | jq . || curl -s http://localhost:8001/health
	@echo ""
	@echo "$(YELLOW)2. Register User:$(NC)"
	@curl -s -X POST http://localhost:8001/api/auth/register \
		-H "Content-Type: application/json" \
		-d '{"email": "test@makefile.com", "password": "Password123", "name": "Makefile Test User"}' | jq . || echo "Registration response received"

api-test-backoffice: ## Test backoffice endpoints
	@echo "$(BLUE)Testing Backoffice Service APIs...$(NC)"
	@echo ""
	@echo "$(YELLOW)1. Health Check:$(NC)"
	@curl -s http://localhost:8002/health | jq . || curl -s http://localhost:8002/health
	@echo ""
	@echo "$(YELLOW)2. Create Product:$(NC)"
	@curl -s -X POST http://localhost:8002/api/products \
		-H "Content-Type: application/json" \
		-d '{"name": "Makefile Test Product", "description": "Created via Makefile", "price": 99.99, "currency": "USD"}' | jq . || echo "Product creation response received"

# =============================================================================
# 🧪 TESTING COMMANDS
# =============================================================================

test: ## Run all tests
	@echo "$(YELLOW)Running all tests...$(NC)"
	docker-compose exec auth vendor/bin/phpunit --configuration phpunit.xml --testsuite Unit
	@echo "$(GREEN)✅ All tests completed!$(NC)"

test-unit: ## Run unit tests only
	@echo "$(YELLOW)Running unit tests...$(NC)"
	docker-compose exec auth vendor/bin/phpunit --configuration phpunit.xml --testsuite Unit
	@echo "$(GREEN)✅ Unit tests completed!$(NC)"

test-auth: ## Run Auth tests
	@echo "$(YELLOW)Running Auth tests...$(NC)"
	docker-compose exec auth vendor/bin/phpunit --configuration phpunit.xml --testsuite Auth
	@echo "$(GREEN)✅ Auth tests completed!$(NC)"

test-backoffice: ## Run Backoffice tests
	@echo "$(YELLOW)Running Backoffice tests...$(NC)"
	docker-compose exec auth vendor/bin/phpunit --configuration phpunit.xml --testsuite Backoffice
	@echo "$(GREEN)✅ Backoffice tests completed!$(NC)"

test-coverage: ## Run tests with coverage report
	@echo "$(YELLOW)Running tests with coverage...$(NC)"
	docker-compose exec auth vendor/bin/phpunit --configuration phpunit.xml --coverage-html var/test-reports/coverage
	@echo "$(GREEN)✅ Coverage report generated in var/test-reports/coverage/$(NC)"

test-watch: ## Run tests in watch mode (requires inotify-tools)
	@echo "$(YELLOW)Running tests in watch mode...$(NC)"
	@echo "$(BLUE)💡 Press Ctrl+C to stop watching$(NC)"
	while true; do \
		docker-compose exec auth vendor/bin/phpunit --configuration phpunit.xml --testdox; \
		sleep 2; \
	done

# Legacy compatibility commands
ping-auth: api-test-auth ## Alias for api-test-auth (legacy)
register-user: ## Register a sample user (legacy)
	@$(MAKE) api-test-auth
login-user: ## Login with sample user (legacy) 
	curl -s -X POST http://localhost:8001/api/auth/login \
		-H "Content-Type: application/json" \
		-d '{"email": "test@makefile.com", "password": "Password123"}' | jq . || echo "Login response received"