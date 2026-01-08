<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SwaggerController
{
    /**
     * Redirect root to documentation.
     *
     * @return RedirectResponse
     */
    #[Route('/', name: 'root', methods: ['GET'])]
    public function root(): RedirectResponse
    {
        return new RedirectResponse('/api/doc', Response::HTTP_MOVED_PERMANENTLY);
    }

    /**
     * Display Swagger UI documentation.
     *
     * @return Response
     */
    #[Route('/api/doc', name: 'swagger_ui', methods: ['GET'])]
    public function swagger(): Response
    {
        $html = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <title>Sugoi API - Swagger UI</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@4/swagger-ui.css">
    <style>
        html { box-sizing: border-box; overflow: -moz-scrollbars-vertical; overflow-y: scroll; }
        *, *:before, *:after { box-sizing: inherit; }
        body { margin: 0; background: #fafafa; }
    </style>
</head>
<body>
    <div id="swagger-ui"></div>
    <script src="https://unpkg.com/swagger-ui-dist@4/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@4/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            const ui = SwaggerUIBundle({
                url: "/openapi.json",
                dom_id: '#swagger-ui',
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                layout: "StandaloneLayout",
                deepLinking: true
            });
            window.ui = ui;
        };
    </script>
</body>
</html>
HTML;

        return new Response($html, Response::HTTP_OK, ['Content-Type' => 'text/html']);
    }

    /**
     * Serve OpenAPI JSON schema.
     *
     * @return Response
     */
    #[Route('/api/doc.json', name: 'openapi_json', methods: ['GET'])]
    #[Route('/openapi.json', name: 'openapi_json_public', methods: ['GET'])]
    public function openapi(): Response
    {
        $filePath = dirname(__DIR__) . '/../public/openapi.json';

        if (!file_exists($filePath)) {
            return new Response(
                json_encode(['error' => 'OpenAPI schema not found']),
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json']
            );
        }

        $content = file_get_contents($filePath);

        return new Response(
            $content,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }
}
