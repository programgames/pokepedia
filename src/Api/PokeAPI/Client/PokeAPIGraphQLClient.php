<?php


namespace App\Api\PokeAPI\Client;


use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\HttpOptions;

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

        return HttpClient::create()
            ->request('POST', $endpoint, $options->toArray())
            ->toArray();
    }
}