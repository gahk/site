<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mydata extends MY_Controller {

	public function __construct()	{
			session_start();
			parent::__construct();

			$this->load->helper('form');
			$this->load->library('session');
			$this->load->model('Internuser_model');
	}

	public function index(){
		$username = $this->session->userdata('username');
		
		if(!$username){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
		} else {
			$data['pagename'] = "mydata";
			$data['pageheader'] = "MAC registrering";

			$this->showInternPage('intern/mydata/mydata', $data);
		}
	}

}
