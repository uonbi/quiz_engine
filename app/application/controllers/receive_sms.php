<!-- 
  Fetching SMS Messages using a PHP script
  The PHP code snippet below shows how to fetch SMS Messages using our API.
-->
        
<?php
// Include the helper gateway class
require_once('AfricasTalkingGateway.php');
// Specify your login credentials
$username   = "codejamer";
$apikey     = "097b5f8c738a0bcfa8899ce0c7da3324a728c5921132e3b1c89065316fb00dae";
// Create a new instance of our awesome gateway class
$gateway  = new AfricaStalkingGateway($username, $apikey);
// Any gateway errors will be captured by our custom Exception class below, 
// so wrap the call in a try-catch block
try 
{
  // Our gateway will return 10 messages at a time back to you, starting with
  // what you currently believe is the lastReceivedId. Specify 0 for the first
  // time you access the gateway, and the ID of the last message we sent you
  // on subsequent received_results
  $lastReceivedId = 0;
  
  // Here is a sample of how to fetch all messages using a while loop
  do {
    
    $received_results = $gateway->fetchMessages($lastReceivedId);
    foreach($received_results as $result) {
      
      echo " From: " .$result->from;
      echo " To: " .$result->to;
      echo " Message: ".$result->text;
      echo " Date Sent: " .$result->date;
      echo " LinkId: " .$result->linkId;
      echo " id: ".$result->id;
      echo "\n";
      $lastReceivedId = $result->id;

      #@deebeat contribution
      #check if 
      
    }
  } while ( count($received_results) > 0 );
  
  // NOTE: Be sure to save lastReceivedId here for next time
  
}
catch ( AfricasTalkingGatewayException $e )
{
  echo "Encountered an error: ".$e->getMessage();
}
// DONE!!!