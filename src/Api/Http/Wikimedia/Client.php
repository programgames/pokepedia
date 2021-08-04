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

    /**
     * @param string $endpoint
     * @param $parameters
     */
    public static function edit(string $endpoint,$parameters)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
        curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");

        $output = curl_exec($ch);
        curl_close($ch);
        $content = json_decode($output,true);
        if(!(isset($content['edit']['result']) && $content['edit']['result'] === 'Success')) {
            throw new InvalidResponse($content['error']['info']);
        }
    }
}
