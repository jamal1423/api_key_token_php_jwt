<?php
	require_once __DIR__ . '/token/tokenData.php';
    require_once __DIR__ . '/vendor/jwt/autoload.php';
    
	require_once('vendor/phpdotenv/autoload.php');
	// use Dotenv\Dotenv;
	
	// SIMPLE LOAD ENV
	\Dotenv\Dotenv::createImmutable(__DIR__)->load();
	
	// Immutability and Repository Customization
	// $repository = Dotenv\Repository\RepositoryBuilder::createWithNoAdapters()
    // ->addAdapter(Dotenv\Repository\Adapter\EnvConstAdapter::class)
    // ->addWriter(Dotenv\Repository\Adapter\PutenvAdapter::class)
    // ->immutable()
    // ->make();
	// $dotenv = Dotenv\Dotenv::create($repository, __DIR__);
	// $dotenv->load();

	// var_dump($_ENV);die;
	// echo $_ENV['API_KEY'];die;


	$keyToken = $_ENV['API_KEY'];

	TokenManager::api_key($keyToken);

	$key = $_SESSION['sessionKey'];

	// echo $key;die;

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "http://localhost/API_KEY/api-provinsi.php",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_HTTPHEADER => array(
	    "key: $key"
	  ),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	$data = json_decode($response, true);
	echo $response;
?>