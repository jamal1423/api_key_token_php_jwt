<?php
session_start();
// $url = $_SERVER['DOCUMENT_ROOT']."/API_KEY/";
// include_once './vendor/phpdotenv/autoload.php';
// $dotenv = \Dotenv\Dotenv::createImmutable($url);
// $dotenv->load();
// $SECRET_KEY = $_ENV['SECRET_KEY'];
// echo $SECRET_KEY;die;

class Token
{
    public function __construct($key_token)
    {
        $this->token = $key_token;
    }
}

class TokenManager
{
    public static string $SECRET_KEY = "2KPZjti02ZLZh9mO2K/ZjyDYo9mO2YbZkiDZhNmO2Kcg2KXZkNmE2Y7Zh9mOINil2ZDZhNmO2ZHYpyDYp9mE2YTZh9mPINmI2Y7Yo9mO2LTZktmH2Y7Yr9mPINij2Y7ZhtmO2ZEg2YXZj9it2Y7ZhdmO2ZHYr9mL2Kcg2LHZjtiz2Y/ZiNmS2YTZjyDYp9mE2YQ=";
    public static function api_key(string $key_token):bool
    {
        date_default_timezone_set("Asia/Jakarta");
        $issuedAt = time();
        $expire = $issuedAt + 28800; //8 jam
        $rand= md5(openssl_random_pseudo_bytes(32));

        if ($key_token != "") {
            $payload = [
                "token" => base64_encode($key_token),
                "rand" => $rand,
                "iat" => $issuedAt,
                "exp" => $expire
            ];

            $jwt = \Firebase\JWT\JWT::encode($payload, TokenManager::$SECRET_KEY, 'HS256');
            $_SESSION['sessionKey'] = $jwt;
            return true;
        } else {
            return false;
        }
    }

    public static function getCurrentToken($getToken): Token
    {
        $jwt = $getToken;
        try {
            $payload = \Firebase\JWT\JWT::decode($jwt, TokenManager::$SECRET_KEY, ['HS256']);
            return new Token($payload->token);
        }
        catch (Exception $exception){
            throw new Exception("Token Not Valid");
        }
    }
}
