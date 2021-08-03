<?php

namespace App\Api\Http\Wikimedia;

use App\Exception\InvalidResponse;
use Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Translation\Exception\InvalidResourceException;

class Client
{
    /** @noinspection PhpUnhandledExceptionInspection */
    public static function parse($url): array
    {
        $client = HttpClient::create();
        try {
            $content =  $client->request('GET', $url)->toArray();
            if (!array_key_exists('parse', $content)) {
                throw new InvalidResponse(sprintf('Invalid response from url %s, parse information is missing', $url));
            }
        } catch (Exception $exception) {
            throw new InvalidResourceException(sprintf('Invalid response from url %s', $url), $exception->getCode(), $exception);
        }

        return $content;
    }
}
