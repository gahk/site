<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Alumneliste extends MY_Controller {

	public function __construct()	{
			session_start();
			parent::__construct();

			$this->load->helper('form');
			$this->load->library('session');
			$this->load->model('Internuser_model');
	}

	public function index(){
		$username = $this->session->userdata('username');
		
		if(!$username && !insideGAHK()){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
		} else {
			$data['pagename'] = "alumneliste";
			$data['pageheader'] = "Alumneliste";
			$data['closenetwork'] = false;
			$data['changeList'] = false;

			$this->showInternPage('intern/alumneliste/liste', $data);
		}
	}

	public function json(){
		if (!insideGAHK()) {
			show_error('Must be inside of GAHK', 500);
			return;
		} else {
			$this->load->view('intern/alumneliste/json', array(), false);
		}
	}

	public function closeNetwork() {
		$username = $this->session->userdata('username');
		$inspektion = $this->session->userdata('inspektion');
		$kokkengruppe = $this->session->userdata('kokkengruppe');

		if(!$username){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
		} elseif(!$inspektion && !$kokkengruppe){
			redirect("nyintern/alumneliste");
		} else {
			$data['pagename'] = "luknetværk";
			$data['pageheader'] = "Luk netværk";
			$data['closenetwork'] = true;
			$data['changeList'] = false;

			$this->showInternPage('intern/alumneliste/liste', $data);
		}
	}

	public function update() {
		$username = $this->session->userdata('username');
		$inspektion = $this->session->userdata('inspektion');
		$indstilling = $this->session->userdata('indstilling');
		
		if(!$username){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
		} elseif(!$indstilling && !$inspektion){
			redirect("nyintern/alumneliste");
		} else {
			$data['pagename'] = "alumneliste";
			$data['pageheader'] = "Rediger alumneliste";
			$data['closenetwork'] = false;
			$data['changeList'] = true;

			$this->showInternPage('intern/alumneliste/liste', $data);
		}
	}

	public function configure() {
		$username = $this->session->userdata('username');
		$indstilling = $this->session->userdata('indstilling');
		
		if(!$username){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
		} elseif(!$indstilling){
			redirect("nyintern/alumneliste");
		} else {
			$data['pagename'] = "alumneliste";
			$data['pageheader'] = "Konfigurer alumneliste";
			$data['closenetwork'] = false;
			$data['changeList'] = false;

			$this->showInternPage('intern/alumneliste/konfigurer', $data);
		}
	}

}
