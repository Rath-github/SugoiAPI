# 🚀 Guia Rápido - SugoiAPI

Este é um guia rápido para você começar a usar e testar a SugoiAPI em poucos minutos.

## ⚡ Início Rápido (5 minutos)

### 1. Clone e Inicie o Projeto

```bash
# Clone o repositório
git clone https://github.com/yzPeedro/SugoiAPI.git sugoiapi
cd sugoiapi

# Inicie os containers (com Docker)
docker compose up -d

# OU use o Makefile
make up
```

A API estará disponível em: **http://localhost:1010**

### 2. Faça seu Primeiro Teste

```bash
# Teste básico
curl http://localhost:1010/episode/naruto/1/1

# OU use o script de teste
php test-api-example.php naruto 1 1

# OU use o Makefile
make api-test-detailed
```

### 3. Execute os Testes Unitários

```bash
# Instale as dependências (se necessário)
composer install

# Execute os testes
./vendor/bin/phpunit

# OU use o Makefile
make test
```

✅ Pronto! Você já está rodando a API e pode começar a explorar.

---

## 📚 Próximos Passos

### Ver Comandos Disponíveis

```bash
make help
```

### Testar Diferentes Animes

```bash
# Naruto
curl http://localhost:1010/episode/naruto/1/1

# One Piece
curl http://localhost:1010/episode/one-piece/1/1

# Bleach
curl http://localhost:1010/episode/bleach/1/1
```

### Ver Logs em Tempo Real

```bash
make logs
# OU
docker compose logs -f app
```

### Acessar o Shell do Container

```bash
make shell
# OU
docker compose exec app bash
```

---

## 🧪 Comandos de Teste Essenciais

| Comando | Descrição |
|---------|-----------|
| `make test` | Executa todos os testes |
| `make test-coverage` | Gera relatório de cobertura |
| `make api-test` | Testa a API rapidamente |
| `make api-test-detailed` | Testa a API com detalhes |
| `make cs-check` | Verifica estilo do código |
| `make cs-fix` | Corrige estilo do código |
| `make full-test` | Executa verificação completa |

---

## 🔧 Desenvolvimento

### Criar um Novo Provider

```bash
make create-provider NAME=MeuProvider
# OU
php bin/console app:create-provider MeuProvider
```

### Ver Rotas Disponíveis

```bash
make routes
# OU
php bin/console debug:router
```

### Limpar Cache

```bash
make cache-clear
# OU
php bin/console cache:clear
```

---

## 📖 Documentação Completa

- **[README.md](README.md)** - Documentação principal
- **[TESTING.md](TESTING.md)** - Guia completo de testes
- **[Wiki](https://github.com/yzPeedro/SugoiAPI/wiki)** - Documentação detalhada

---

## 🐛 Problemas Comuns

### API não responde?

```bash
# Verificar status dos containers
make status
# OU
docker compose ps

# Reiniciar
make restart
```

### Testes falhando?

```bash
# Reinstalar dependências
rm -rf vendor/
composer install

# Limpar cache
make cache-clear
```

### Porta 1010 já está em uso?

Edite o arquivo `docker-compose.yml` e altere a porta:
```yaml
ports:
  - "8080:1010"  # Mude para a porta que preferir
```

---

## 💡 Dicas

1. **Use o Makefile** - Ele simplifica muito os comandos comuns
2. **Mantenha o Docker rodando** - `make up` antes de começar a trabalhar
3. **Execute testes frequentemente** - `make test` após cada mudança
4. **Use o script PHP** - `php test-api-example.php` para testes detalhados
5. **Verifique os logs** - `make logs` quando algo der errado

---

## 🎯 Workflow Recomendado

```bash
# 1. Inicie o ambiente
make up

# 2. Faça suas alterações no código
# ... edite os arquivos ...

# 3. Execute os testes
make test

# 4. Verifique o estilo do código
make cs-check

# 5. Teste a API
make api-test-detailed

# 6. Commit suas mudanças
git add .
git commit -m "Descrição das mudanças"
```

---

## 📞 Precisa de Ajuda?

- 📧 Email: pedrocruzpessoa16@gmail.com
- 📚 Wiki: https://github.com/yzPeedro/SugoiAPI/wiki
- 🐛 Issues: https://github.com/yzPeedro/SugoiAPI/issues

---

Bom desenvolvimento! 🚀✨
