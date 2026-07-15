<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Response;

interface TooManyRedirectsExceptionInterface extends ResponseExceptionInterface
{
    public const string MESSAGE = 'Connected to %s but received too many redirects.';

    /**
     * Guzzle's redirect middleware throws without a captured response, so no real HTTP status is
     * available. 310 ("Too Many Redirects") is the historical status for this exact condition and
     * is far more honest than defaulting to a 200 OK.
     */
    public const int STATUS_CODE_TOO_MANY_REDIRECTS = 310;
}
