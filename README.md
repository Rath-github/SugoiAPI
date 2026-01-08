# SugoiAPI

[![PHP Version](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://www.php.net/)
[![Symfony](https://img.shields.io/badge/Symfony-7.1-black.svg)](https://symfony.com/)
[![License](https://img.shields.io/badge/license-Proprietary-red.svg)](LICENSE)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)](CONTRIBUTING.md)

Olá, este é um projeto open source para compartilhar links de animes de maneira fácil e rápida, sinta-se à vontade para contribuir com o projeto.

> 🚀 **Novo aqui?** Veja o [Guia Rápido (QUICKSTART.md)](QUICKSTART.md) para começar em 5 minutos!

## 📚 Documentação

- **[QUICKSTART.md](QUICKSTART.md)** - Guia rápido para começar
- **[TESTING.md](TESTING.md)** - Guia completo de testes e exemplos
- **[Wiki](https://github.com/yzPeedro/SugoiAPI/wiki)** - Documentação detalhada

## 🚀 Instalação e Execução

### Requisitos
- Docker e Docker Compose
- PHP 8.2 ou superior (para desenvolvimento local)
- Composer (para desenvolvimento local)

### Com Docker (Recomendado)

```bash
# Clone o repositório
git clone https://github.com/yzPeedro/SugoiAPI.git sugoiapi

# Entre no diretório
cd sugoiapi

# Suba os containers
docker compose up -d
```

A API estará disponível em: `http://localhost:1010`

### Usando Makefile (Recomendado)

O projeto inclui um Makefile com comandos úteis:

```bash
# Ver todos os comandos disponíveis
make help

# Iniciar ambiente
make up

# Executar testes
make test

# Testar API
make api-test
```

### Desenvolvimento Local (Sem Docker)

```bash
# Clone o repositório
git clone https://github.com/yzPeedro/SugoiAPI.git sugoiapi
cd sugoiapi

# Instale as dependências
composer install

# Inicie o servidor de desenvolvimento
php -S localhost:1010 -t public/
```

## 📖 API - Endpoints

### Buscar Episódio

```
GET /episode/{anime-slug}/{temporada}/{numero-episodio}
```

**Parâmetros:**
- `anime-slug` (string): slug do anime (ex: "naruto", "one-piece")
- `temporada` (int): número da temporada
- `numero-episodio` (int): número do episódio

**Exemplo de Requisição:**
```bash
curl http://localhost:1010/episode/naruto/1/1
```

### Exemplos de Retornos

#### ✅ Sucesso (200)
```json
{
  "error": false,
  "message": "Success",
  "status": 200,
  "data": [
    {
      "name": "Anime Fire",
      "slug": "anime-fire",
      "has_ads": false,
      "is_embed": false,
      "episodes": [
        {
          "error": false,
          "searched_endpoint": "https://animefire.plus/video/naruto/1",
          "episode": "https://lightspeedst.net/s3/mp4/naruto/sd/1.mp4"
        }
      ]
    }
  ]
}
```

#### ❌ Erro (404)
```json
{
  "error": true,
  "message": "Not Found",
  "status": 404
}
```

### Códigos HTTP

| Código | Status | Descrição |
|:-------|:-------|:----------|
| 200 | OK | Episódio encontrado com sucesso |
| 404 | Not Found | Episódio não encontrado |

## 🧪 Testes

O projeto utiliza PHPUnit para testes automatizados.

### Executar Todos os Testes

```bash
# Com Composer
composer test

# Ou diretamente com PHPUnit
./vendor/bin/phpunit

# Com o binário do projeto
php bin/phpunit
```

### Executar Testes Específicos

```bash
# Testar apenas providers
./vendor/bin/phpunit tests/Unit/Providers

# Testar um arquivo específico
./vendor/bin/phpunit tests/Unit/Support/ResponseSupportTest.php

# Executar um teste específico
./vendor/bin/phpunit --filter testProvidersMustImplementsAllRequiredInterfaces
```

### Testes com Cobertura

```bash
# Gerar relatório de cobertura HTML
./vendor/bin/phpunit --coverage-html coverage/
```

### Estrutura de Testes

```
tests/
├── Unit/
│   ├── Providers/         # Testes dos providers
│   │   └── ProviderTest.php
│   ├── Support/           # Testes das classes de suporte
│   │   └── ResponseSupportTest.php
│   └── Traits/            # Testes dos traits
│       └── HandleProvidersTest.php
└── bootstrap.php
```

### Exemplos de Testes Básicos

#### Testar se a API está respondendo

```bash
# Com curl
curl http://localhost:1010/episode/naruto/1/1

# Com HTTPie (se tiver instalado)
http GET http://localhost:1010/episode/naruto/1/1
```

#### Testar manualmente com PHP

```php
<?php
// test-api.php
$url = 'http://localhost:1010/episode/naruto/1/1';
$response = file_get_contents($url);
$data = json_decode($response, true);

echo "Status: " . ($data['error'] ? 'Erro' : 'Sucesso') . "\n";
echo "Message: " . $data['message'] . "\n";
```

```bash
php test-api.php
```

### Executando Testes no Docker

```bash
# Entrar no container
docker compose exec app bash

# Rodar os testes dentro do container
php bin/phpunit
```

## 🔍 Verificação de Código

### PHP CS Fixer (Code Style)

```bash
# Verificar problemas de estilo
./vendor/bin/php-cs-fixer fix --dry-run --diff

# Corrigir automaticamente
./vendor/bin/php-cs-fixer fix
```

## 🏗️ Estrutura do Projeto

```
src/
├── Actions/              # Actions e serializadores
├── Command/              # Comandos do console
├── Controller/           # Controllers da API
├── EventListeners/       # Event listeners
├── Exceptions/           # Exceções customizadas
├── Providers/            # Implementações dos providers
│   └── Contracts/        # Interfaces dos providers
├── Services/             # Serviços da aplicação
└── Support/              # Classes de suporte
```

## 📦 Providers

Providers são os provedores dos links de animes. Cada provider possui regras específicas de busca.

**Providers Disponíveis:**
- Anime Fire
- Animes Online CC
- Superflix

Para consultar regras específicas de cada provider, acesse a [documentação de providers](https://github.com/yzPeedro/SugoiAPI/wiki/Providers).

### Adicionar Novo Provider

```bash
# Usar o comando CLI
php bin/console app:create-provider NomeDoProvider
```

Isso criará um novo provider em `src/Providers/` com a estrutura necessária.

## 🛠️ Desenvolvimento

### Comandos Úteis

```bash
# Limpar cache
php bin/console cache:clear

# Ver rotas disponíveis
php bin/console debug:router

# Criar novo provider
php bin/console app:create-provider NomeDoProvider
```

### Configurações

As configurações principais estão em:
- `config/services.yaml` - Serviços
- `config/routes.yaml` - Rotas
- `config/packages/` - Configurações de pacotes

## 🤝 Como Contribuir

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/MinhaFeature`)
3. Commit suas mudanças (`git commit -m 'Adiciona MinhaFeature'`)
4. Push para a branch (`git push origin feature/MinhaFeature`)
5. Abra um Pull Request

Para mais detalhes, consulte a [documentação de contribuição](https://github.com/yzPeedro/SugoiAPI/wiki/Contribui%C3%A7%C3%A3o).

### Checklist de Contribuição

- [ ] Código segue os padrões PSR-12
- [ ] Testes foram adicionados/atualizados
- [ ] Todos os testes passam (`./vendor/bin/phpunit`)
- [ ] Código foi verificado com PHP CS Fixer
- [ ] Documentação foi atualizada se necessário

## ⚠️ Disclaimer

Este projeto não tem o intuito de incentivar a pirataria. O projeto foi criado com o intuito de facilitar o acesso a animes de maneira gratuita. Caso você goste do anime, por favor considere apoiar o criador comprando o produto original.

Este projeto não hospeda nenhum conteúdo, apenas redireciona para sites de terceiros. Caso você seja o dono de algum dos links e deseja removê-lo, por favor entre em contato através do email: pedrocruzpessoa16@gmail.com

## 📄 Licença

Este projeto é proprietário. Para mais informações sobre uso comercial ou em outros projetos, entre em contato: pedrocruzpessoa16@gmail.com

## 📧 Contato

- Email: pedrocruzpessoa16@gmail.com
- Assunto sugerido: "SugoiAPI"

Adoraria conhecer projetos que utilizam esta API! 😊