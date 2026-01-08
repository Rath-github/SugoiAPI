# Swagger/OpenAPI Documentation

A documentação Swagger/OpenAPI da Sugoi API está disponível em dois formatos, sem dependências externas adicionais.

## Acessando a Documentação

### 1. **Swagger UI (Interface Web)**
Acesse diretamente no seu navegador:
```
http://localhost/api/doc
```

### 2. **OpenAPI JSON Schema**
```
http://localhost/api/doc.json
ou
http://localhost/openapi.json
```

## Exemplos de Uso

### Buscar um Episódio
```bash
curl -X GET "http://localhost/episode/naruto/1/1" \
  -H "Accept: application/json"
```

**Resposta de Sucesso (200):**
```json
[
  {
    "provider": "Anime Fire",
    "slug": "naruto",
    "season": 1,
    "episode": 1,
    "url": "https://animefire.plus/video/naruto/1/1",
    "has_ads": true,
    "is_embed": false
  },
  {
    "provider": "Superflix",
    "slug": "naruto",
    "season": 1,
    "episode": 1,
    "url": "https://superflixapi.top/serie/naruto/1/1",
    "has_ads": true,
    "is_embed": true
  }
]
```

**Resposta de Erro (404):**
```json
{
  "error": true,
  "message": "Episode not found",
  "status": 404
}
```

## Parametros

| Parâmetro | Tipo | Descrição | Exemplo |
|-----------|------|-----------|---------|
| `slug` | string | Slug do anime | `naruto` |
| `season` | integer | Número da temporada | `1` |
| `episodeNumber` | integer | Número do episódio | `1` |

## Providers Disponíveis

- **Anime Fire** - Sem anúncios, embed: false
- **AnimesOnline.cc** - Com anúncios, embed: false
- **Superflix** - Com anúncios, embed: true

## Códigos de Status HTTP

| Código | Descrição |
|--------|-----------|
| 200 | Episódio encontrado com sucesso |
| 404 | Episódio não encontrado |
| 500 | Erro interno do servidor |

## Arquitetura da Documentação

A solução é implementada sem dependências externas:

- `public/openapi.json` - Schema OpenAPI 3.0.0 estático
- `src/Controller/SwaggerController.php` - Controlador que serve:
  - `GET /api/doc` - UI Swagger com CDN do jsdelivr
  - `GET /api/doc.json` - Alias para o schema
  - `GET /openapi.json` - Acesso público ao schema
- `src/Controller/MediaController.php` - Anotações PHP com Swagger/OpenAPI

## Instalação Opcional de Bundle

Se desejar usar o Nelmio API Doc Bundle para geração automática (requer composer com OpenSSL funcionando):

```bash
composer require nelmio/api-doc-bundle
```

Depois configure em `config/packages/nelmio_api_doc.yaml`:
```yaml
nelmio_api_doc:
    documentation:
        info:
            title: Sugoi API
            description: 'API desenvolvida para assistir animes legendados sem anúncios'
            version: 2.3.2
        servers:
            - url: http://localhost
              description: 'Local development server'
            - url: https://api.sugoi.example.com
              description: 'Production server'
```


