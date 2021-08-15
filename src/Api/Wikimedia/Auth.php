<?php

namespace App\Api\Wikimedia;

use Exception;

class Auth
{
    private string $pokepediaUser;
    private string $pokepediaPassword;

    public function __construct(string $pokepediaUser, string $pokepediaPassword)
    {
        $this->pokepediaUser = $pokepediaUser;
        $this->pokepediaPassword = $pokepediaPassword;
    }

    public function getLoginToken(string $endPoint)
    {
        $params1 = [
            "action" => "query",
            "meta" => "tokens",
            "type" => "login",
            "format" => "json"
        ];

        $url = $endPoint . "?" . http_build_query($params1);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
        curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");

        $output = curl_exec($ch);

        if ($output === false) {
            $exception =  new Exception(curl_error($ch), curl_errno($ch));
            curl_close($ch);
            throw $exception;
        }

        $result = json_decode($output, true);
        return $result["query"]["tokens"]["logintoken"];
    }

    public function loginRequest($logintoken, string $endPoint): void
    {
        $params2 = [
            "action" => "login",
            "lgname" => $this->pokepediaUser,
            "lgpassword" => $this->pokepediaPassword,
            "lgtoken" => $logintoken,
            "format" => "json"
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $endPoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params2));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
        curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");

        curl_exec($ch);
        curl_close($ch);
    }

    public function getCSRFToken(string $endPoint)
    {
        $params3 = [
            "action" => "query",
            "meta" => "tokens",
            "format" => "json"
        ];

        $url = $endPoint . "?" . http_build_query($params3);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
        curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");

        $output = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($output, true);
        return $result["query"]["tokens"]["csrftoken"];
    }
}
