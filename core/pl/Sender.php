<?php

$GLOBALS['product-live-post'] = 'https://productlive.servicebus.windows.net/morgan/messages?timeout=60';
$service_url_get = 'https://productlive.servicebus.windows.net/morgan/subscriptions/allmessages/messages/head?timeout=60';
$GLOBALS['product-live-auth'] = "SharedAccessSignature sr=https%3A%2F%2Fproductlive.servicebus.windows.net%2Fmorgan&sig=HAva7QtjeUwGuDY81T3nME28S5DgeyE1Bx6H9ndRuXg%3D&se=1521566475&skn=Manage";


function getAPI($url, $auth) {
    // Set the HTTP request authentication headers
    $headers = array(
        'http' => array(
            'method' => "POST",
            'header' => "Authorization: " . $auth . "\r\n" .
                "Content-Length: 0"
        )
    );

    // Creates a stream context
    $context = stream_context_create($headers);

    // Open the URL with the HTTP headers (fopen wrappers must be enabled)
    $page = @file_get_contents($url, false, $context); //fopen($url, 'r', false, $context);

    $result  = array( );
    if ( $page != false )
        $result['content'] = $page;
    else if ( !isset( $http_response_header ) )     // TODO handle error
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
            $result['http_code'] = $response[1];    // TODO handle response code
            break;
        }
    }

    return $result;
}

function postAPI($postdata) {
    // Set the HTTP request authentication headers
    $headers = array(
        'http' => array(
            'method' => "POST",
            'header' => "Content-type: application/atom+xml;type=entry;charset=utf-8" . "\r\n" .
                "Authorization: " . $GLOBALS['product-live-auth'] . "\r\n" .
                "Content-Length: " . strlen($postdata) . "\r\n",
            'content' => $postdata
        )
    );

    // Creates a stream context
    $context = stream_context_create($headers);

    // Open the URL with the HTTP headers (fopen wrappers must be enabled)
    $page = @file_get_contents($GLOBALS['product-live-post'], false, $context); //fopen($url, 'r', false, $context);

    $result  = array( );
    if ( $page != false )
        $result['content'] = $page;
    else if ( !isset( $http_response_header ) )     // TODO handle error
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
            $result['http_code'] = $response[1];    // TODO handle response code
            break;
        }
    }

    return $result;
}


//var_dump(postAPI($service_url_post, $auth, " the world"));
//var_dump(getAPI($service_url_get, $auth));