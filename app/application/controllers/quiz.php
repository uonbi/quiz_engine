<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#helper gateway class
require_once('AfricasTalkingGateway.php');
error_reporting(E_ALL);

class Quiz extends CI_Controller {

	private $data;
	
	function __construct(){
		parent::__construct();

		$this->load->model('quiz_model');
	}

	public function index()
	{
		#credentials
		$username = "codejamer";
		$apikey = "097b5f8c738a0bcfa8899ce0c7da3324a728c5921132e3b1c89065316fb00dae";

		#details from the user
		$phone_number = $this->input->get('from');
		$sender = $this->input->get('to');//shot code(sender)
		$user_message = trim(strtolower($_REQUEST['text']));

		$message_from_user = substr($user_message, 0, 5);
		$succeeding_msg = substr($message_from_user, 5);

		$current_date_time = date("Y-m-d H:i:s");

		if ($message_from_user == "hunt ")
		{
				echo($succeeding_msg);
				exit();

			#send the user a question
			$this->receive_user_msg($phone_number, $succeeding_msg, $current_date_time, $sender);

			#$this->receive_user_msg($phone_number, $succeeding_msg, $current_date_time, $sender);
		}
		/*$phone_number = "+254720255774";
		$msg = 'hunt nerd';
		$time = date('Y-M-d h:m:s');
		$sender = '2345';

		var_dump($this->quiz_model->validate($phone_number, $msg, $time));*/

	}
	

	#send sms to a user
	public function send_new_sms($recipient, $new_question, $sender)
	{

		#credentials
		$username   = "codejamer";
		$apikey = "097b5f8c738a0bcfa8899ce0c7da3324a728c5921132e3b1c89065316fb00dae";

		// Create a new instance of our awesome gateway class
		$gateway = new AfricasTalkingGateway($username, $apikey);
		// Any gateway errors will be captured by our custom Exception class below,
		// so wrap the call in a try-catch block
		
		try
		{
			// Thats it, hit send and we'll take care of the rest.
			

			$results = $gateway->sendMessage($recipient, $new_question, $sender);
				
		}
		catch ( AfricasTalkingGatewayException $e )
		{
			echo "Encountered an error while sending: ".$e->getMessage();
		}
		// DONE!!! 
	}

	#end_receive_new_sms(x, y)->@deebeat

	public function recv_msg($phone, $msg, $time, $sender){
		$data = array(
			'phone' => $phone,
			'msg'  => $msg,
			'time' => $time,
			'sender'=> $sender
			);

		return $data;
	}

	public function receive_user_msg($phone, $msg, $time, $sender){
		#system access point
		$msg = trim(strtolower($msg));

		if($this->_no_such_user($phone)){
			$this->reg_user($phone, $msg, $time);

			$welcome_message = "Welcome to the Amazing Treasure Hunt:). Please reply with your name before we begin the hunt.\n{Powered by: Angani, Africa's Talking and SCI CodeJam}";
			$this->send_new_sms($phone, $welcome_message, $sender);

			#@deebeat_edits
			#send new user a question

			$opener_question = $this->sendQue($phone);

			#send using API helper function
			$this->send_new_sms($phone, $opener_question, $sender);

			#@deebeat->end();

		} elseif ($this->_on_probation($phone)) {

			#validate redemption ans
			$probation_question = $this->redeem_message($this->redeemQue());
			$this->send_new_sms($recipient, $probation_question, $sender);

			$result = $this->quiz_model->redeem_module($phone, $msg);
			if($result){
				#successfully redeemed his|herself
				$next_que = $this->sendQue($phone);

				#@Dennis send this next quetion to the user
				$this->send_new_sms($phone, $next_que, $sender);

			} else {
				#user failed the redemption question
				$red_que = $this->redeemQue();
				$this->quiz_model->update_probation($phone, $red_que);

				$msg = $this->redeem_message($red_que);
				#Dennis pick the message to send here.
				$this->send_new_sms($phone, $msg, $sender);
			}

		} else {
			#answer validations
			$result = $this->quiz_model->validate($phone, $msg, $time);

			if($result){

				#update quiz_count in the db
				$res = $this->quiz_model->update_usr($phone);
				if($res){
					$new_quest = $this->sendQue($phone);

					#@deebeat-send user a new question
					$this->send_new_sms($phone, $new_quest, $sender);

				} else {
					#only magic can get you here XD
				}	

			} else {
	
				#wrong answer was submitted
				if($this->to_probation($phone) != true){
					$same_question = $this->sendQue($phone);
					$var = $this->prob_stats($phone) + 1;
	
					$result = $this->prob_stats_update($phone, $var);

					#@deebeat
					$this->send_new_sms($phone, $same_question, $sender);
				} else {
					#notify user he is on probation and send him a redemption question
					$red_que = $this->redeemQue();
					#update probation table with the users question
					$this->quiz_model->update_probation($phone, $red_que);

					$msg = $this->redeem_message($red_que);
					#Dennis pick the message to send here.

					#@deebeat
					$this->send_new_sms($phone, $msg, $sender);

				}
			}
		}
	}
	public function reg_user($phone, $name,$time){
		$data['phone'] =  $phone;
		$data['name']  =  $name;
		$data['time']  =  $time;
		$data['quiz_count'] = 1;

		$this->quiz_model->reg_user($data);	
	}
	public function _no_such_user($phone){
		return $this->quiz_model->is_available($phone);
	}
	public function userCount($phone){
		$result = $this->quiz_model->usr_count($phone);
		foreach ($result->result() as $row) {
			$quiz_count = $row->quiz_count;
		}
		return $quiz_count;
	}	
	public function getQuestion($que_num){

		$result = $this->quiz_model->getQuestion($que_num);

		foreach ($result->result() as $row) {
			$question = $row->question;
		}

		return $question;
	}
	public function sendQue($phone){
		$user_count = $this->userCount($phone);
		$que = $this->getQuestion($user_count);

		return $que;
	}
	public function prob_stats($phone){
		#@shimanyi
		#checks user status.

		$result = $this->quiz_model->get_prob_stats($phone);
		if ($result){
			foreach ($result->result() as $row) {
				$probationFlag = $row->probationFlag;
			}
		} else {
			return false;
		}	
		return $probationFlag;
	}
	public function prob_stats_update($phone, $var){
		return $this->quiz_model->prob_stats_update($phone, $var);
	}	
	public function to_probation($phone){
		$probationFlag = $this->prob_stats($phone);

		if($probationFlag == 3){
			#put user on probation
			$this->quiz_model->to_probation($phone);
			return true;

		} else {
			return false;
		}
	}
	public function redeemQue(){
		/*
			@Shimanyi
			make the companies generated random in the system.

		*/
		$index = 0;
		$owners = array();

		$result = $this->quiz_model->get_redeemQue();
		if ($result){
			foreach ($result->result() as $row) {
				$owners[$index] = $row->owner;
				$index += 1;
			}

			$random_que = array_rand($owners,1);
			return $random_que;

		} else {
			return false;
		}
	}
	public function user_redeemed($phone){
		#reset probation flag to 0
		$result = $this->quiz_model->probation_reset($phone);

		if ($result){
			return true;
		} else {
			return false;
		}
	}
	public function _on_probation($phone){
		$result = $this->quiz_model->on_probation($phone);

		if($result->num_rows() == 1){
			return true;
		} else {
			return false;
		}
	}
	public function redeem_message($code_owner){
		$redeem_msg = 'You on probation. Submit the '+$code_owner+' from their stand to reedem yourself';
		return $redeem_msg;
	}	
	public function redeem_module($var){
		$result = $this->quiz_model->redeem_module($var);

		if($result){
			return true;
		} else {
			return false;
		}
	}


	#@deebeat-begin-edits()
	public function send_airtime()
	{
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

   	/*foreach($results as $result) {
   	 echo $result->status . "<br/>";
   	 echo $result->amount . "<br/>";
   	 echo $result->phoneNumber . "<br/>";
   	 echo $result->discount . "<br/>";
   	 echo $result->requestId . "<br/>";
   	 */
   	 //Error message is important when the status is not Success
   	 echo $esult->errorMessage . "<br/>";
   	}
   catch(AfricasTalkingGatewayException $e){
   	echo $e->getMessage();
   }

	}

	#method to reward the first 20 pple to get the first 5 questions correct with airtime
	public function award_airtime()
	{
		#array of phone numbers
		$awardees_phones = $this->quiz_model->get_winners();

		#award airtime to each number here
		foreach($awardees_phones as $single_phone)
		{
			$phone = $this->$single_phone;

			#call the send airtime module here
			#get random number from

		}
	}

}
/* End of file quiz.php */