<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quiz extends CI_Controller {

	private $data;

	function __construct(){
		parent::__construct();

		$this->load->model('quiz_model');
	}

	public function index()
	{
		$this->flagFail();
	}
	public function reg_user(){
		$data['phone'] =  '+254720255774';
		$data['name']  =  'shimanyi';
		$data['time']  =  date('y-m-d h:m:s');

		if($this->_no_such_user($data['phone'])){

			$this->quiz_model->reg_user($data);

		} 
		/*else
		# a registered user*/

	}
	public function _no_such_user($phone){
		
	}	

	public function flagFail(){
		echo 'something';

		$member_id = 1;
		$this->quiz_model->flagFails($member_id);
	}
}

/* End of file quiz.php */
