<?php

require_once(__DIR__.'/../Messages/AcquittalMessage.php');
require_once(__DIR__.'/../../../ProductLiveWrapper.php');
require_once(__DIR__.'/RestAPI.php');

define( 'LOCK_FILE', __DIR__."/Consumer.php".".lock" ); 
file_put_contents( LOCK_FILE, getmypid() . "\n" ); 

$time = 1;

$pl = new ProductLiveWrapper();
$rest = new RestAPI();
while (true) {	
    $response = $rest->getMessage();
    if(isset($response) && $response['http_code']==201) {
        if (isset($response['header']) ) {
            $headers = $response['header'];
            $brokerProperties;
            foreach ($headers as $header) {
                if (strpos($header, "BrokerProperties") !== false && strpos($header, "LockToken") !== false && strpos($header, "MessageId") !== false) {
                    $brokerProperties = json_decode(str_replace("BrokerProperties: ", "", $header), true);
                }
            }
            if (isset($brokerProperties) && isset($brokerProperties['LockToken']) && isset($brokerProperties['MessageId'])) {
                $messageId = $brokerProperties['MessageId'];
                $lockToken = $brokerProperties['LockToken'];
                $productMessage = json_decode($response['content'], true);

                // Process the message
                $re = $pl->updateProductFromProductLiveToMyIT($productMessage);

                // Delete messsage
                $response = $rest->deleteMessage($messageId, $lockToken);

                // Send acquital
                $combinations = array();
                $idProductPl = $productMessage['idProductPl'];
                $idModel = $productMessage['model']['idModel'];
                $idModelPl = $productMessage['model']['idModelPl'];
                $productAcquittal = new productAcquittal($idProductPl, new ModelAcquittal($idModelPl, $idModel), $combinations);
                $errorMessage = array();
                $acquittalMessage = new AcquittalMessage("1", time(), $productAcquittal, $errorMessage, $messageId, ACQUITTAL::END);
                $rest->postMessage($acquittalMessage, "products", "acquittal");
            }
        }
    }
	sleep($time);
}

unlink( LOCK_FILE ); 
exit(0);

