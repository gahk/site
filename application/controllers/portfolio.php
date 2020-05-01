<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Portfolio extends MY_Controller {

	public function __construct()	{
			session_start();
			parent::__construct();

			$this->load->helper('form');
			$this->load->library('session');
	}

	public function getPortfolio() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json; charset=utf-8');
		
		return $this->load->view('portfolio', $data, false);
	}
}
