<?php

define( 'LOCK_FILE', __DIR__."/Consumer.php".".lock" ); 
file_put_contents( LOCK_FILE, getmypid() . "\n" ); 

$addrr = $argv[1];

$time = 1;

while (true) {
	// Set the HTTP request authentication headers
	$headers = array(
	    'http' => array(
	        'method' => "GET"
	    )
	);
	// Creates a stream context
	$context = stream_context_create($headers);

	// Get url
	//Configuration::get('PL_TOPIC')
	//Configuration::get('PL_SUBSCRIPTION')
	$service_url_get = $addrr."modules/productlive/ajax.php?ajax=true";

	// Open the URL with the HTTP headers (fopen wrappers must be enabled)
	$page = @file_get_contents($service_url_get, false, $context); //fopen($url, 'r', false, $context);
	//$page = @fopen($service_url_get, 'r', false, $context);

	$result  = array( );
	if ( $page != false )
	    $result['content'] = $page;
	else if ( !isset( $http_response_header ) )
	    return null;    // Bad url, timeout

	// Save the header
	$result['header'] = $http_response_header;

	// Get the *last* HTTP status code
	$nLines = count( $http_response_header );
	for ( $i = $nLines-1; $i >= 0; $i-- )
	{
	    $line = $http_response_header[$i];
	    if ( strncasecmp( "HTTP", $line, 4 ) == 0 )
	    {
	        $response = explode( ' ', $line );
	        $result['http_code'] = $response[1];
	        break;
	    }
	}
	sleep($time);
}

unlink( LOCK_FILE ); 
exit(0);

// //echo "start";

// require_once(__DIR__.'/RestAPI.php');
// require_once(__DIR__.'/../../../ProductLiveWrapper.php');

// require_once(__DIR__.'/../../../../../config/config.inc.php');
// require_once(__DIR__.'/../../../../..//init.php');


// define( 'LOCK_FILE', __DIR__."/Consumer.php".".lock" ); 
// file_put_contents( LOCK_FILE, getmypid() . "\n" ); 

// $time = 5;

// //while (true) {
// 	try {
// 		$re = getMessage();
// 		$file = __DIR__.'/temp.txt';
// 		file_put_contents($file, $re);
// 		// if (isset($re) && $re!="noMessage") {
// 		// 	var_dump($re);
// 		// 	//$time = 1;
// 		// } else {
// 		// 	$file = __DIR__.'/noMessage.txt';
// 		// 	implode($s, $re);
// 		// 	file_put_contents($file, $s);
// 		// 	//$time = 5;
// 		// }
// 	} catch (Exception $e) {
// 		$file = __DIR__.'/errors.txt';
// 		file_put_contents($file, $e);
// 	}

// 	//sleep($time);
// //}


// unlink( LOCK_FILE ); 
// exit(0);

// function getMessage() {
// 	$pl = new ProductLiveWrapper();
//     return $pl->updateProductFromProductLiveToMyIT();
// }