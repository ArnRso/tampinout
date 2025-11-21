.PHONY: help build run stop clean logs shell

# Variables
IMAGE_NAME = tampinout
CONTAINER_NAME = tampinout-app
PORT = 8080

help: ## Affiche cette aide
	@echo "Commandes disponibles:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}'

build: ## Build l'image Docker
	docker build -t $(IMAGE_NAME) .

run: ## Lance le conteneur
	docker run -d \
		--name $(CONTAINER_NAME) \
		-p $(PORT):80 \
		-v $(PWD)/var/data.db:/app/var/data.db \
		$(IMAGE_NAME)
	@echo "Application accessible sur http://localhost:$(PORT)"

stop: ## Arrête le conteneur
	docker stop $(CONTAINER_NAME) || true
	docker rm $(CONTAINER_NAME) || true

clean: stop ## Arrête et supprime le conteneur et l'image
	docker rmi $(IMAGE_NAME) || true

logs: ## Affiche les logs du conteneur
	docker logs -f $(CONTAINER_NAME)

shell: ## Ouvre un shell dans le conteneur
	docker exec -it $(CONTAINER_NAME) sh

rebuild: clean build ## Rebuild complet (clean + build)

restart: stop run ## Redémarre le conteneur

dev: ## Lance les outils de développement (watch + serveur Symfony)
	npm run watch & symfony server:start

install: ## Installation initiale du projet
	composer install
	npm install
	php bin/console doctrine:migrations:migrate --no-interaction
	npm run build
