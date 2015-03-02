<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quiz extends CI_Controller {

	private $data;

	function __construct(){
		parent::__construct();

		$this->load->model('quiz_model');
	}

	public function index()
	{
		$this->reg_user();
	}
	public function reg_user(){
		$data['phone'] =  '+254720255774';
		$data['name']  =  'shimanyi';
		$data['time']  =  date('y-m-d h:m:s');

		if($this->_no_such_user($data['phone'])){
			$this->quiz_model->reg_user($data);
		} 
<<<<<<< HEAD
		/*else
		# a registered user*/

=======
		else{
			$this->sendNextQue();	
		}	
>>>>>>> f7411f4921a9c481f8a720996968c4f3527f44b3
	}
	public function _no_such_user($phone){
		return $this->quiz_model->is_available($phone);
	}
	public function userCount($phone){
		return $this->quiz_model->usr_count($phone);
	}

	public function sendNextQue(/*$phone, $question*/){

	}	
	public function flagFail(){
		/*	$member_id = 1;
		$this->quiz_model->flagFails($member_id);*/
	}
}

/* End of file quiz.php */
