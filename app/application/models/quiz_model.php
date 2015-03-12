<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quiz_model extends CI_Model {
	private $data;

	public function reg_user($data){
		$status = $this->db->insert('members', $data);
		if($status){
			#insert the same user to the probation table
			return true;
		} else {
			return false;
		}
	}
	public function is_available($phone){
		$query = $this->db->get_where('members', array('phone' => $phone));
		if($this->db->affected_rows() == 0){
			return true;
		} else {
			return false;
		}
	}

	#@deebeat. Check if user's answer is correct 
	public function validate($phone, $user_answer, $time)
	{
		#@deebeat
		$user_answer = trim(strtolower($user_answer));
		$question = $this->get_question_count($phone);

		#get correct answer
		$correct_answer = trim($this->get_system_answer($question));

		if($user_answer == $correct_answer)
		{
			#correct answer
			return true;
		}
		else
		{
			#wrong answer
			return false;
		}
	}

	#@deebeat. Get the question that the user is working on
	private function get_question_count($phone)
	{
		$question_row = $this->usr_count($phone);

		#get count
		foreach ( ($question_row->result()) as $value)
		{
			$count = $value->quiz_count;
		}

		return $count;
	}

	#@deebeat
	#get the answer to question submitted
	private function get_system_answer($question)
	{
		#get the correct system answer
		$answer = $this->db->get_where("quest_answer", array('quiz_id'=>$question));

		foreach ($answer->result() as  $value)
		{
			$answer = $value->answer;
		}
		return $answer;
	}


	#updates user's count when they get the answer right
	public function update_usr($phone)
	{
		$data = array(
			'quiz_count' => $this->get_question_count($phone) + 1
			);
		
		$this->db->where('phone',$phone);
		$result = $this->db->update('members', $data);

		if($result){
			return true;
		} else {
			return false;
		}
	}
	#end_@deebeat module's


	public function getQuestion($que_num){

		$result = $this->db->get_where('quest_answer',array('quiz_id'=>$que_num));
		if($result){
			return $result;
		} else {
			return false;
		}
	}
	public function usr_count($phone){
		$result = $this->db->get_where('members',array('phone'=>$phone));
		if($result){
			return $result;
		} else {
			return false;
		}
	}

	public function flagFails($member_id){
		#flag a user, change probation to 1

		$this->data['member_id'] = $member_id;
		$this->data['probation_status'] = 1;
		$this->data['date_time'] = date('y-m-d h:m:s');

		$status = $this->db->insert('probation', $this->data);

	}
	public function get_prob_stats($phone){
		$result = $this->db->get_where('members', array('phone'=>$phone));

		if($result){
			return $result;
		} else {
			return false;
		}
	}
	public function prob_stats_update($phone,$var){
		$data = array(
			'probationFlag' => $var
			);
		
		$this->db->where('phone',$phone);
		$result = $this->db->update('members', $data);

		if($result){
			return true;
		} else {
			return false;
		}
	}
	public function to_probation($phone){
		$data = array(
			'probation_status' => 1
			);

		$this->db->where('phone', $phone);
		$result = $this->db->update('members', $data);
		if($result){
			return true;
		} else {
			return false;
		}	
	}
	public function get_redeemQue(){
		$this->db->select('owner');
		$result = $this->db->get('redemptions');

		if ($result){
			return $result;
		} else {
			return false;
		}
	}
	public function on_probation($phone){

		$result = $this->db->get_where('members', array('phone' => $phone,'probation_status' => 1));
		if($result){
			return $result;
		} else {
			return false;
		}
	}

	public function probation_reset($phone){
		$data = array(
			'probation_status' => 0,
			'probationFlag'    => 0
			);
		$this->db->where('phone',$phone);
		$result = $this->db->update('members', $data);

		if($result){
			return true;
		} else {
			return false;
		}
	}
	public function update_probation($phone, $red_que){
		$data = array(
			'redeem_quest' => $red_que
			);

		$this->db->where('phone', $phone);
		$result = $this->db->update('members', $data);

		if($result){
			return true;
		} else {
			return false;
		}
	}
	public function redeem_module($var, $phone){
		print_r($var);
		$result = $this->db->query("SELECT * FROM redemptions INNER JOIN members ON members.redeem_quest = redemptions.owner
						WHERE redemptions.codejam = '$var' AND members.phone='$phone' LIMIT 0 , 30");
		if($this->db->affected_rows() != 0){
			return true;
		} else {
			return false;
		}

	}


	#@deebeat begin->edits()
	#@return an array of phone numbers
	public function get_winners()
	{
		$correct_status = 1;
		#get from db the first 20 correct submissions
		$correct_answers = $this->db->query("SELECT name,phone FROM members WHERE quiz_count >= 6");

		if($correct_answers)
		{
		
			 return $correct_answers;
		}
		else
		{
			return false;
		}

		#update a field to keep count of people who reach threshold
	}

	private function object_to_array($data)
	{
	    if (is_object($data)) {
	         $data = get_object_vars($data);
	    }
	    if (is_array($data)) {
	          return array_map(__FUNCTION__, $data);
	    }
	     else {
	          return $data;
	    }
	}

	#function admin_get_winners() to go to the model
	public function admin_get_winners()
	{
		$winners = $this->db->query("SELECT phone, name FROM members 
												INNER JOIN airtime_winners
												ON members.member_id = airtime_winners.member_id ORDER BY date_time ASC LIMIT 20");

		
		if($winners)
		{
			return $winners;
		}
		else
		{
			return false;
		}

	}

}