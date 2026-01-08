#!/usr/bin/env php
<?php
/**
 * Script de exemplo para testar a API SugoiAPI
 * 
 * Uso:
 *   php test-api-example.php [anime-slug] [temporada] [episodio]
 * 
 * Exemplo:
 *   php test-api-example.php naruto 1 1
 */

// Configurações
$baseUrl = 'http://localhost:1010';

// Argumentos da linha de comando
$animeSlug = $argv[1] ?? 'naruto';
$temporada = $argv[2] ?? 1;
$episodio = $argv[3] ?? 1;

// Monta a URL
$url = sprintf('%s/episode/%s/%d/%d', $baseUrl, $animeSlug, $temporada, $episodio);

echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║         SugoiAPI - Teste de Endpoint                    ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n\n";

echo "🔍 Testando endpoint:\n";
echo "   URL: {$url}\n\n";

// Faz a requisição
echo "⏳ Fazendo requisição...\n\n";

$context = stream_context_create([
    'http' => [
        'timeout' => 30,
        'ignore_errors' => true,
    ]
]);

$response = @file_get_contents($url, false, $context);

if ($response === false) {
    echo "❌ Erro: Não foi possível conectar à API\n";
    echo "   Certifique-se de que a API está rodando em {$baseUrl}\n";
    echo "   Execute: docker compose up -d\n";
    exit(1);
}

// Parse da resposta
$data = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "❌ Erro ao decodificar JSON\n";
    echo "   Resposta: {$response}\n";
    exit(1);
}

// Exibe o resultado
if ($data['error']) {
    echo "❌ Erro na requisição:\n";
    echo "   Status: {$data['status']}\n";
    echo "   Mensagem: {$data['message']}\n";
} else {
    echo "✅ Sucesso!\n";
    echo "   Status: {$data['status']}\n";
    echo "   Mensagem: {$data['message']}\n\n";
    
    if (isset($data['data']) && !empty($data['data'])) {
        echo "📺 Providers encontrados: " . count($data['data']) . "\n\n";
        
        foreach ($data['data'] as $provider) {
            echo "   Provider: {$provider['name']}\n";
            echo "   Slug: {$provider['slug']}\n";
            echo "   Tem anúncios: " . ($provider['has_ads'] ? 'Sim' : 'Não') . "\n";
            echo "   É embed: " . ($provider['is_embed'] ? 'Sim' : 'Não') . "\n";
            
            if (!empty($provider['episodes'])) {
                echo "   Episódios encontrados: " . count($provider['episodes']) . "\n";
                
                foreach ($provider['episodes'] as $index => $episode) {
                    if (!$episode['error']) {
                        echo "     - Link " . ($index + 1) . ": {$episode['episode']}\n";
                    }
                }
            }
            echo "\n";
        }
    }
}

echo "\n";
echo "📋 Resposta completa (JSON):\n";
echo str_repeat("─", 60) . "\n";
echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
echo "\n" . str_repeat("─", 60) . "\n";

exit($data['error'] ? 1 : 0);
