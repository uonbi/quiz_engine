<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quiz_model extends CI_Model {
	private $data;

	public function reg_user($data){
		$status = $this->db->insert('members', $data);
		if($status){
			return true;
		} else {}
	}
	public function is_available($phone){
		$query = $this->db->get_where('members', array('phone' => $phone));
		if($this->db->affected_rows() == 0){
			return true;
		} else {
			return false;
		}
	}
	public function validate($phone_number, $msg, $time){
		#@deebeat
		
	}


	public function getNextQuestion(){

	}
<<<<<<< HEAD

=======
	public function usr_count($phone){
		$result = $this->get_where('members',array('phone'=>$phone))
	}
>>>>>>> f7411f4921a9c481f8a720996968c4f3527f44b3

	public function flagFails($member_id){
		#flag a user, change probation to 1

		$this->data['member_id'] = $member_id;
		$this->data['probation_status'] = 1;
		$this->data['date_time'] = date('y-m-d h:m:s');

		$status = $this->db->insert('probation', $this->data);

	}
}