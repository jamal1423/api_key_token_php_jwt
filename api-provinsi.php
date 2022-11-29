<?php
header("Content-Type:application/json");
$header = apache_request_headers();

if(isset($header['key']))
{
    $key = $header['key'];
}
else
{
    $key = "";
}
// var_dump($header);die;

$method = $_SERVER['REQUEST_METHOD'];
$result = array();

require_once __DIR__ . '/token/tokenData.php';
require_once __DIR__ . '/vendor/jwt/autoload.php';

require_once('vendor/phpdotenv/autoload.php');	
// SIMPLE LOAD ENV
\Dotenv\Dotenv::createImmutable(__DIR__)->load();

try {
    $tokenData = TokenManager::getCurrentToken($key);

    $resultKey = base64_decode($tokenData->token);

    // echo $resultKey;die;

    $servername = $_ENV['DB_HOST'];
    $username = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASS'];
    $dbname = $_ENV['DB_NAME'];

    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = "SELECT * FROM tbl_key where api_key='$resultKey'";
    $keyData = $conn->query($sql);

    if($keyData->num_rows > 0)
    {
        if($method =='GET')
        {
            
            $sqlProv = "SELECT * FROM provinces";
            $row = $conn->query($sqlProv);
            
            $result = [
                'code'=> 200,
                'description'=> 'Request Valid',
                'results' => $row->fetch_all(MYSQLI_ASSOC)
            ];
        }
        else
        {
            $result = [
                'code'=> 400,
                'description'=> 'Request Not Valid'
            ];
        }
    }
    else
    {
        $result = [
            'code'=> 400,
            'description'=> 'API Key/Token Not Valid'
        ];
    }

    echo json_encode($result);

} catch (Exception $exception) {
    $result = [
        'code'=> 400,
        'description'=> 'API Key/Token Not Valid'
    ];
    echo json_encode($result);
    // exit(0);
}