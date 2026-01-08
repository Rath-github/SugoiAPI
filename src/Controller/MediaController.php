<?php

namespace App\Controller;

use App\Exceptions\ProviderNotRegisteredException;
use App\Services\MediaService;
use App\Support\ResponseSupport;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @OA\Info(
 *      title="Sugoi API",
 *      version="2.3.2",
 *      description="API desenvolvida para assistir animes legendados sem anúncios"
 * )
 * @OA\Server(
 *      url="http://localhost",
 *      description="Local development server"
 * )
 */
class MediaController
{
    private MediaService $mediaService;

    public function __construct()
    {
        $this->mediaService = new MediaService();
    }

    /**
     * Search for an anime episode.
     *
     * This endpoint searches for a specific anime episode across all registered providers.
     * It returns a list of available episodes from different providers with their respective URLs.
     *
     * @return Response JSON response containing episode data or error
     *
     * @throws ProviderNotRegisteredException
     *
     * @OA\Get(
     *     path="/episode/{slug}/{season}/{episodeNumber}",
     *     summary="Search for an episode",
     *     description="Search for a specific anime episode across all registered providers",
     *     operationId="searchEpisode",
     *     tags={"Episodes"},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         description="The anime slug (e.g., 'naruto', 'attack-on-titan')",
     *         required=true,
     *         @OA\Schema(type="string", example="naruto")
     *     ),
     *     @OA\Parameter(
     *         name="season",
     *         in="path",
     *         description="The season number",
     *         required=true,
     *         @OA\Schema(type="integer", example=1, minimum=1)
     *     ),
     *     @OA\Parameter(
     *         name="episodeNumber",
     *         in="path",
     *         description="The episode number",
     *         required=true,
     *         @OA\Schema(type="integer", example=1, minimum=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved episode data from providers",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="provider", type="string", example="Anime Fire"),
     *                 @OA\Property(property="slug", type="string", example="naruto"),
     *                 @OA\Property(property="season", type="integer", example=1),
     *                 @OA\Property(property="episode", type="integer", example=1),
     *                 @OA\Property(property="url", type="string", format="uri", example="https://animefire.plus/video/naruto/1/1"),
     *                 @OA\Property(property="has_ads", type="boolean", example=true),
     *                 @OA\Property(property="is_embed", type="boolean", example=false)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Episode not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Episode not found"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Provider error"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    #[Route('/episode/{slug}/{season}/{episodeNumber}', name: 'episodes', methods: ['GET'])]
    public function episode(string $slug, int $season, int $episodeNumber): Response
    {
        return ResponseSupport::json(
            $this->mediaService->searchEpisode($episodeNumber, $season, $slug)
        );
    }
}
