<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#helper gateway class
require_once('AfricasTalkingGateway.php');


class Quiz extends CI_Controller {

	private $data;
	
	function __construct(){
		parent::__construct();

		$this->load->model('quiz_model');
		
	}

	public function index()
	{
		#credentials
		$username   = "codejamer";
		$apikey = "097b5f8c738a0bcfa8899ce0c7da3324a728c5921132e3b1c89065316fb00dae";
		
		$this->receive_new_sms($username, $apikey);
	}
	
	#@deebeat-> receive_sms(using the GateWay)(From the API)
	public function receive_new_sms($username, $apikey)
	{
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
		      $phone = $result->from;
		      $date = $result->date;
		      $msg = $result->text;

		      #start querying the DB
		      $this->recv_sms($phone, $msg, $date);
		      
		    }
		  } while ( count($received_results) > 0 );
		  
		  // NOTE: Be sure to save lastReceivedId here for next time
		  
		}
		catch ( AfricasTalkingGatewayException $e )
		{
		  echo "Encountered an error: ".$e->getMessage();
		}
	}
	#end_receive_new_sms(x, y)->@deebeat


	public function recv_sms($phone,$msg,$time){
		#system access point
		if($this->_no_such_user($phone)){
			$this->reg_user($phone,$msg,$time);
		} elseif ($this->_on_probation($phone)) {
			/*
			- take ans and compare with the code in the db
			if correct redeem the guy 
			else 
			Send notify him that he is still on probation and he needs to give the codejam code of
			eg Google to proceed.
			*/

			#@Dennis - system to sent probation message



		} else {
			#answer validations
			$result = $this->quiz_model->validate($phone, $msg, $time);
			if($result){
				#update quiz_count in the db
				$res = $this->quiz_model->update_usr($phone);
				if($res){
					$this->sendQue($phone);
				} else {
					#only magic can get you here XD
				}	

			} else {
				#wrong answer was submitted
				$this->sendQue($phone);
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

		$toSend = array(
			'phone'   => $phone,
			'message' => $que
			);
		return $toSend;
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
	public function to_probation($phone){
		$probationFlag = $this->prob_stats($phone);

		if($probationFlag == 3){
			#put user on probation
			if($this->quiz_model->to_probation($phone)){
				return true;
			} else { 
				return false;
			}

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
			return $question;

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

}
/* End of file quiz.php */
