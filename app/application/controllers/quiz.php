<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('receive_sms.php');

class Quiz extends CI_Controller {

	private $data;

	function __construct(){
		parent::__construct();

		$this->load->model('quiz_model');
	}

	public function index()
	{
		$this->haha();
	}
	public function haha(){
		echo 'It is working hahaha XD';
	}

	public function recv_sms($phone,$msg,$time){
		#system access point
		if($this->_no_such_user($phone)){
			$this->reg_user($phone,$msg,$time);
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
	public function reg_user($phone, $name){
		$data['phone'] =  $phone;
		$data['name']  =  $name;
		$data['time']  =  date('y-m-d h:m:s');
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
}
/* End of file quiz.php */
