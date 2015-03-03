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

	#@deebeat. Check if user's answer is correct 
	public function validate($phone, $user_answer, $time)
	{
		#@deebeat
		$user_answer = strtolower($user_answer);
		$question = $this->get_question_count($phone);

		#get correct answer
		$correct_answer = $this->get_system_answer($question + 1);

		if($user_answer == $correct_answer)
		{
			#send next question
			$next_quiz = $this->getQuestion($question + 1);
			return $this->sendNextQue($phone, $next_quiz);

		}
		else
		{
			#wrong, resend same question
			$same_quiz = $this->getQuestion($question);
			return $this->sendNextQue($phone, $next_quiz);

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
}