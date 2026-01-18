# Gamarky Compute Service Makefile
# ملف Make لخدمة الحسابات

.PHONY: help build up down logs shell test clean migrate collectstatic

help: ## Show this help message
	@echo "Available commands:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

build: ## Build Docker images
	docker-compose build

up: ## Start all services
	docker-compose up -d

down: ## Stop all services
	docker-compose down

logs: ## Show logs for all services
	docker-compose logs -f

logs-web: ## Show logs for web service only
	docker-compose logs -f web

logs-celery: ## Show logs for celery service only
	docker-compose logs -f celery

shell: ## Open Django shell in web container
	docker-compose exec web python manage.py shell

dbshell: ## Open database shell
	docker-compose exec web python manage.py dbshell

psql: ## Connect to PostgreSQL directly
	docker-compose exec db psql -U gamarky -d gamarky_compute

redis-cli: ## Connect to Redis CLI
	docker-compose exec redis redis-cli

test: ## Run tests
	docker-compose exec web python manage.py test

test-coverage: ## Run tests with coverage
	docker-compose exec web coverage run --source='.' manage.py test
	docker-compose exec web coverage report
	docker-compose exec web coverage html

lint: ## Run code linting
	docker-compose exec web flake8 .
	docker-compose exec web black --check .

format: ## Format code with black
	docker-compose exec web black .

migrate: ## Run database migrations
	docker-compose exec web python manage.py migrate

makemigrations: ## Create new migrations
	docker-compose exec web python manage.py makemigrations

collectstatic: ## Collect static files
	docker-compose exec web python manage.py collectstatic --noinput

createsuperuser: ## Create Django superuser
	docker-compose exec web python manage.py createsuperuser

restart-web: ## Restart web service
	docker-compose restart web

restart-celery: ## Restart celery service
	docker-compose restart celery

clean: ## Clean up Docker resources
	docker-compose down -v --remove-orphans
	docker system prune -f

reset-db: ## Reset database (WARNING: This will delete all data)
	docker-compose down
	docker volume rm $$(docker volume ls -q | grep postgres_data) || true
	docker-compose up -d db
	sleep 10
	make migrate
	
backup-db: ## Backup database
	mkdir -p backups
	docker-compose exec db pg_dump -U gamarky gamarky_compute > backups/backup_$$(date +%Y%m%d_%H%M%S).sql

restore-db: ## Restore database from backup (Usage: make restore-db BACKUP_FILE=backup_file.sql)
	@if [ -z "$(BACKUP_FILE)" ]; then echo "Usage: make restore-db BACKUP_FILE=backup_file.sql"; exit 1; fi
	docker-compose exec -T db psql -U gamarky -d gamarky_compute < $(BACKUP_FILE)

flower: ## Access Celery Flower monitoring
	@echo "Opening Flower at http://localhost:5555"
	@which open >/dev/null && open http://localhost:5555 || echo "Please open http://localhost:5555 in your browser"

docs: ## Generate API documentation
	docker-compose exec web python manage.py spectacular --file openapi-schema.yaml
	@echo "OpenAPI schema generated: openapi-schema.yaml"

install: ## Install and setup the project
	cp .env.example .env
	@echo "Environment file created. Please edit .env with your settings."
	make build
	make up
	sleep 15
	make migrate
	@echo "Setup complete! Access the API at http://localhost:8000/api/v1/"

dev: ## Start development environment
	make up
	@echo "Development environment started!"
	@echo "API: http://localhost:8000/api/v1/"
	@echo "Admin: http://localhost:8000/admin/"
	@echo "Flower: http://localhost:5555"
	@echo "API Docs: http://localhost:8000/api/docs/"

status: ## Show status of all services
	docker-compose ps

health: ## Check health of services
	@echo "Checking service health..."
	@curl -f http://localhost:8000/api/v1/health/ || echo "Web service not responding"
	@docker-compose exec redis redis-cli ping || echo "Redis not responding"
	@docker-compose exec db pg_isready -U gamarky || echo "Database not ready"

# Production targets
prod-build: ## Build production images
	docker-compose -f docker-compose.yml -f docker-compose.prod.yml build

prod-up: ## Start production services
	docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d

prod-down: ## Stop production services
	docker-compose -f docker-compose.yml -f docker-compose.prod.yml down

# Development helpers
watch-logs: ## Watch logs with colored output
	docker-compose logs -f --tail=100

quick-test: ## Run a quick test calculation
	@echo "Testing import cost calculation..."
	@curl -X POST http://localhost:8000/api/v1/calc/import-cost/ \
		-H "Content-Type: application/json" \
		-d '{"product_value":"1000","quantity":10,"origin_country":"CN","destination_country":"SA"}' \
		| python -m json.tool || echo "Test failed - is the service running?"

monitor: ## Monitor resource usage
	docker stats $$(docker-compose ps -q)

# Maintenance
cleanup-jobs: ## Cleanup old calculation jobs
	docker-compose exec web python manage.py shell -c "from apps.calc.tasks import cleanup_expired_jobs; cleanup_expired_jobs()"

init-celery-beat: ## Initialize Celery Beat scheduler
	docker-compose exec web python manage.py shell -c "from django_celery_beat.models import PeriodicTask, IntervalSchedule; IntervalSchedule.objects.get_or_create(every=60, period='minutes')[0]"