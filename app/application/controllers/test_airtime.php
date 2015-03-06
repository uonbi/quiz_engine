<?php

	require_once "AfricasTalkingGateway.php";

//Specify your credentials
		$username = "codejamer";
		$apiKey   = "097b5f8c738a0bcfa8899ce0c7da3324a728c5921132e3b1c89065316fb00dae";
		
		//Specify the phone number/s and amount in the format shown
		//Example shown assumes we want to send KES 100 to two numbers
		// Please ensure you include the country code for phone numbers (+254 for Kenya in this case)
		
		$recipients = array(
		                array("phoneNumber"=>"+254700XXXYYY", "amount"=>"KES 100"),
					             array("phoneNumber"=>"+254733YYYZZZ", "amount"=>"KES 100")
					           );
		
		//Convert the recipient array into a string. The json string produced will have the format:
		// [{"amount":"KES 100", "phoneNumber":+254700XXXYYY},{"amount":"KES 100", "phoneNumber":+254733YYYZZZ}]
		//A json string with the shown format may be created directly and skip the above steps
		$recipientStringFormat = json_encode($recipients);
		
		//Create an instance of our awesome gateway class and pass your credentials
		$gateway = new AfricasTalkingGateway($username, $apiKey);
		
		// Thats it, hit send and we'll take care of the rest. Any errors will
   // be captured in the Exception class as shown below
   
   try {
   	$results = $gateway->sendAirtime($recipientStringFormat);

   	foreach($results as $result) {
   	 echo $result->status . "<br/>";
   	 echo $result->amount . "<br/>";
   	 echo $result->phoneNumber . "<br/>";
   	 echo $result->discount . "<br/>";
   	 echo $result->requestId . "<br/>";
   	 
   	 //Error message is important when the status is not Success
   	 echo $esult->errorMessage . "<br/>";
   	}
   }
   catch(AfricasTalkingGatewayException $e){
   	echo $e->getMessage();
   }
  ?>