<?php


namespace App\Api\Pokepedia\Client;


use App\Api\Wikimedia\Auth;
use App\Api\Wikimedia\Client;

class PokepediaClient extends Client
{
    public const ENDPOINT = "https://www.pokepedia.fr/api.php";

    private Auth $auth;

    public function login()
    {
        $login_Token = $this->auth->getLoginToken(self::ENDPOINT);
        $this->auth->loginRequest($login_Token, self::ENDPOINT);
        return $this->auth->getCSRFToken(self::ENDPOINT);
    }
}