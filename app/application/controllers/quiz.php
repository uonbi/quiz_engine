<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quiz extends CI_Controller {

	private $data;

	function __construct(){
		parent::__construct();

		$this->load->model('quiz_model');
	}

	public function index()
	{
		$this->getQuestion(1);
	}

	#pick values from API i.e, phone, name and time
	public function reg_user(){
		$data['phone'] =  '+254720255774';
		$data['name']  =  'shimanyi';
		$data['time']  =  date('y-m-d h:m:s');
		$data['quiz_count'] = 1;

		if($this->_no_such_user($data['phone'])){
			$this->quiz_model->reg_user($data);
		} 


		else{
			$usr_count = userCount($data['phone']);
			$this->sendNextQue($data['phone'], $usr_count);	
		}	
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

	public function sendNextQue($phone, $question){

	}	
	public function flagFail(){
		
	}
	public function getQuestion($que_num){

		$result = $this->quiz_model->getQuestion($que_num);

		foreach ($result->result() as $row) {
			$question = $row->question;
		}

		return $question;
	}
}

/* End of file quiz.php */
