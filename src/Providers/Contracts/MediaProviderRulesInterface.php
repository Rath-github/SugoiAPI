<?php

namespace App\Providers\Contracts;

use Psr\Http\Message\ResponseInterface;

interface MediaProviderRulesInterface
{
    public function canUsePrefix(): bool;

    public function canUseSuffix(): bool;

    public function mustSerializeEpisode(): bool;

    public function mustHandleResponse(): bool;

    public function responseHasError(ResponseInterface $response): bool;
}
