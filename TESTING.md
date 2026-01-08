# 🧪 Exemplos de Testes da API SugoiAPI

Este arquivo contém exemplos práticos de como testar a API usando diferentes ferramentas.

## 📋 Pré-requisitos

Certifique-se de que a API está rodando:
```bash
docker compose up -d
```

## 🌐 Testes com cURL

### Teste básico
```bash
curl http://localhost:1010/episode/naruto/1/1
```

### Teste com formatação JSON (usando jq)
```bash
curl -s http://localhost:1010/episode/naruto/1/1 | jq
```

### Teste com cabeçalhos visíveis
```bash
curl -i http://localhost:1010/episode/naruto/1/1
```

### Teste com timeout
```bash
curl --max-time 30 http://localhost:1010/episode/naruto/1/1
```

### Salvar resposta em arquivo
```bash
curl http://localhost:1010/episode/naruto/1/1 -o response.json
```

## 🔧 Testes com HTTPie (mais amigável)

Se você tiver HTTPie instalado:

```bash
# Instalação
pip install httpie

# Teste básico
http GET http://localhost:1010/episode/naruto/1/1

# Com verbose
http -v GET http://localhost:1010/episode/naruto/1/1
```

## 🐍 Script PHP de Teste

Use o script fornecido:

```bash
# Teste padrão (naruto temporada 1 episódio 1)
php test-api-example.php

# Teste personalizado
php test-api-example.php one-piece 1 5

# Outro exemplo
php test-api-example.php bleach 1 10
```

## 🧪 Testes Unitários

### Executar todos os testes
```bash
composer test
# ou
./vendor/bin/phpunit
# ou
php bin/phpunit
```

### Executar testes específicos
```bash
# Apenas testes de providers
./vendor/bin/phpunit tests/Unit/Providers/

# Apenas testes de support
./vendor/bin/phpunit tests/Unit/Support/

# Teste específico por nome
./vendor/bin/phpunit --filter testProvidersMustImplementsAllRequiredInterfaces
```

### Testes com verbose
```bash
./vendor/bin/phpunit --testdox
```

### Testes com cobertura
```bash
composer test:coverage
# ou
./vendor/bin/phpunit --coverage-html coverage/
# Abrir coverage/index.html no navegador
```

## 🐳 Testes dentro do Docker

```bash
# Entrar no container
docker compose exec app bash

# Rodar os testes
php bin/phpunit

# Testar a API de dentro do container
curl http://localhost:1010/episode/naruto/1/1
```

## 📊 Verificação de Código

### PHP CS Fixer (Code Style)
```bash
# Verificar problemas
composer cs:check
# ou
./vendor/bin/php-cs-fixer fix --dry-run --diff

# Corrigir automaticamente
composer cs:fix
# ou
./vendor/bin/php-cs-fixer fix
```

## 🔍 Testes de Performance

### Teste de tempo de resposta com cURL
```bash
curl -w "\nTempo total: %{time_total}s\n" http://localhost:1010/episode/naruto/1/1
```

### Teste de múltiplas requisições
```bash
for i in {1..5}; do
  echo "Requisição $i:"
  time curl -s http://localhost:1010/episode/naruto/1/$i -o /dev/null
done
```

## 🌍 Testes com diferentes animes

```bash
# Naruto
curl http://localhost:1010/episode/naruto/1/1

# One Piece
curl http://localhost:1010/episode/one-piece/1/1

# Dragon Ball
curl http://localhost:1010/episode/dragon-ball/1/1

# Bleach
curl http://localhost:1010/episode/bleach/1/1
```

## ✅ Checklist de Testes

Antes de fazer um commit, execute:

- [ ] `composer test` - Todos os testes unitários passam
- [ ] `composer cs:check` - Código está formatado corretamente
- [ ] `curl http://localhost:1010/episode/naruto/1/1` - API responde corretamente
- [ ] Testar com pelo menos 2 animes diferentes
- [ ] Verificar logs de erro (se houver)

## 🐛 Troubleshooting

### API não responde
```bash
# Verificar se o container está rodando
docker compose ps

# Ver logs
docker compose logs app

# Reiniciar containers
docker compose restart
```

### Erro de conexão
```bash
# Verificar porta
netstat -tuln | grep 1010

# Testar conexão local
curl http://127.0.0.1:1010/episode/naruto/1/1
```

### Testes falhando
```bash
# Limpar cache
php bin/console cache:clear

# Reinstalar dependências
rm -rf vendor/
composer install

# Executar testes com verbose
./vendor/bin/phpunit --testdox --verbose
```

## 📝 Exemplos de Respostas Esperadas

### Resposta de Sucesso (200)
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
          "episode": "https://example.com/video.mp4"
        }
      ]
    }
  ]
}
```

### Resposta de Erro (404)
```json
{
  "error": true,
  "message": "Not Found",
  "status": 404
}
```

## 🎯 Dicas

1. **Use jq para formatar JSON**: `curl -s URL | jq`
2. **Salve respostas para debug**: `curl URL -o debug.json`
3. **Use o script PHP** para testes mais informativos
4. **Rode testes frequentemente** durante o desenvolvimento
5. **Verifique cobertura** para garantir que todo código está testado
