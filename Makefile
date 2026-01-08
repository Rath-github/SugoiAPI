.PHONY: help install up down restart logs test test-coverage cs-check cs-fix shell api-test clean

# Cores para output
GREEN  := \033[0;32m
YELLOW := \033[0;33m
RESET  := \033[0m

help: ## Mostra esta mensagem de ajuda
	@echo ""
	@echo "$(GREEN)SugoiAPI - Comandos Disponíveis$(RESET)"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  $(YELLOW)%-20s$(RESET) %s\n", $$1, $$2}'
	@echo ""

install: ## Instala as dependências do projeto
	@echo "$(GREEN)Instalando dependências...$(RESET)"
	composer install

up: ## Inicia os containers Docker
	@echo "$(GREEN)Iniciando containers...$(RESET)"
	docker compose up -d
	@echo "$(GREEN)API disponível em: http://localhost:1010$(RESET)"

down: ## Para os containers Docker
	@echo "$(YELLOW)Parando containers...$(RESET)"
	docker compose down

restart: ## Reinicia os containers Docker
	@echo "$(YELLOW)Reiniciando containers...$(RESET)"
	docker compose restart

logs: ## Mostra os logs dos containers
	docker compose logs -f app

test: ## Executa os testes unitários
	@echo "$(GREEN)Executando testes...$(RESET)"
	./vendor/bin/phpunit

test-unit: ## Executa apenas testes unitários
	@echo "$(GREEN)Executando testes unitários...$(RESET)"
	./vendor/bin/phpunit tests/Unit

test-coverage: ## Gera relatório de cobertura de testes
	@echo "$(GREEN)Gerando cobertura de testes...$(RESET)"
	./vendor/bin/phpunit --coverage-html coverage/
	@echo "$(GREEN)Relatório gerado em: coverage/index.html$(RESET)"

test-watch: ## Executa testes em modo watch
	@echo "$(GREEN)Executando testes em modo watch...$(RESET)"
	@while true; do \
		clear; \
		./vendor/bin/phpunit; \
		inotifywait -qre close_write src/ tests/; \
	done

cs-check: ## Verifica o estilo do código (dry-run)
	@echo "$(GREEN)Verificando estilo do código...$(RESET)"
	./vendor/bin/php-cs-fixer fix --dry-run --diff

cs-fix: ## Corrige automaticamente o estilo do código
	@echo "$(GREEN)Corrigindo estilo do código...$(RESET)"
	./vendor/bin/php-cs-fixer fix

shell: ## Acessa o shell do container
	@echo "$(GREEN)Acessando shell do container...$(RESET)"
	docker compose exec app bash

api-test: ## Testa a API com exemplos
	@echo "$(GREEN)Testando API...$(RESET)"
	@echo "$(YELLOW)Teste 1: Naruto S01E01$(RESET)"
	@curl -s http://localhost:1010/episode/naruto/1/1 | jq -r '.message' || echo "Erro na requisição"
	@echo ""
	@echo "$(YELLOW)Teste 2: One Piece S01E01$(RESET)"
	@curl -s http://localhost:1010/episode/one-piece/1/1 | jq -r '.message' || echo "Erro na requisição"
	@echo ""

api-test-detailed: ## Testa a API com detalhes usando script PHP
	@echo "$(GREEN)Testando API com detalhes...$(RESET)"
	php test-api-example.php naruto 1 1

cache-clear: ## Limpa o cache do Symfony
	@echo "$(GREEN)Limpando cache...$(RESET)"
	php bin/console cache:clear

routes: ## Lista as rotas disponíveis
	@echo "$(GREEN)Rotas disponíveis:$(RESET)"
	php bin/console debug:router

clean: ## Remove arquivos temporários e cache
	@echo "$(YELLOW)Limpando arquivos temporários...$(RESET)"
	rm -rf var/cache/*
	rm -rf var/log/*
	rm -rf coverage/
	@echo "$(GREEN)Limpeza concluída!$(RESET)"

create-provider: ## Cria um novo provider (uso: make create-provider NAME=NomeProvider)
	@if [ -z "$(NAME)" ]; then \
		echo "$(YELLOW)Uso: make create-provider NAME=NomeProvider$(RESET)"; \
	else \
		php bin/console app:create-provider $(NAME); \
	fi

status: ## Mostra o status dos containers
	@echo "$(GREEN)Status dos containers:$(RESET)"
	docker compose ps

build: ## Reconstrói as imagens Docker
	@echo "$(GREEN)Reconstruindo imagens...$(RESET)"
	docker compose build

rebuild: down build up ## Reconstrói e reinicia os containers

full-test: cs-check test api-test ## Executa verificação completa (code style + testes + API)
	@echo ""
	@echo "$(GREEN)✓ Todas as verificações passaram!$(RESET)"

dev: up logs ## Inicia o ambiente de desenvolvimento e mostra logs

# Atalhos
t: test ## Atalho para test
tc: test-coverage ## Atalho para test-coverage
cc: cache-clear ## Atalho para cache-clear
cf: cs-fix ## Atalho para cs-fix
