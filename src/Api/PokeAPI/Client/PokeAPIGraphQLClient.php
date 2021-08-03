<?php

namespace App\Api\PokeAPI\Client;

use App\Exception\InvalidResponse;
use Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\HttpOptions;

//Very lightweight graphQL client
class PokeAPIGraphQLClient
{
    public function sendRequest(string $endpoint, string $query): array
    {
        $options = (new HttpOptions())
            ->setJson(['query' => $query])
            ->setHeaders([
                'Content-Type' => 'application/json',
                'User-Agent' => 'Symfony GraphQL client'
            ]);

        try {
            /** @noinspection PhpUnhandledExceptionInspection */
            return HttpClient::create()
                ->request('POST', $endpoint, $options->toArray())
                ->toArray();
        } catch (Exception $exception) {
            throw new InvalidResponse(sprintf("Invalid response from pokeapi query %s", $query), $exception->getMessage(), $exception);
        }
    }
}
