<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quiz extends CI_Controller {

	private $data;

	public function index()
	{
		$this->load->view('home');
	}
}

/* End of file quiz.php */
