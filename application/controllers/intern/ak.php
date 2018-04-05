<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ak extends MY_Controller {

	public function __construct()	{
			session_start();
			parent::__construct();

			$this->load->helper('form');
			$this->load->library('session');
			$this->load->model('Aklog_model');
			$this->load->model('Akstatus_model');
	}

	public function index(){
		$alumneId = $this->session->userdata('alumne_id');
		
		$this->showPersonalLog($alumneId);
	}


	public function showPersonalLog($alumneId){
		$username = $this->session->userdata('username');
		$fullname = $this->session->userdata('fullname');
		$usersAlumneId = $this->session->userdata('alumne_id');
		$akRole = $this->session->userdata('akRole');
		
		$data['username'] = $username;
		$data['fullname'] = $fullname;
		$data['akRole'] = $akRole;
		$data['pagename'] = "ak";
		$data['pageheader'] = "Ak-krydser";

		if(!$username){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
		} else if($alumneId != $usersAlumneId &&  empty($akRole)) {
			redirect("nyintern/ak");

		} else {
			$this->load->library('form_validation');
			if($alumneId != $usersAlumneId){
				$this->load->model('Adminuser_model');
				$data['otherPerson'] = $this->Adminuser_model->getAlumneOnId($alumneId);
			} else {
				//Only allowed to submis to log on own user
				$this->submitkrydser($alumneId);
			}
			$this->updatestatus($alumneId);
			$data['aklog'] = $this->Aklog_model->getLogByAlumneId($alumneId);
			$data['akstatus'] = $this->Akstatus_model->getStatusByAlumneId($alumneId);
			$data['visitedAlumneId'] = $alumneId;

			$data['success'] = false;
			if($this->uri->segment(5) == "success"){
				$data['success'] = true;
			}

			$this->showInternPage('intern/ak', $data);
		}

	}

	private function submitkrydser($alumneId){
		$username = $this->session->userdata('username');
		$fullname = $this->session->userdata('fullname');
		$usersAlumneId = $this->session->userdata('alumne_id');
		$akRole = $this->session->userdata('akRole');

		if($username == ""){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
		} else if(empty($akRole) && $alumneId != $usersAlumneId) {
			echo "Ingen adgang";
		} else {
			if (!empty($_POST['formname']) && $_POST['formname'] == "addtolog"){
				$this->form_validation->set_rules('krydser', '\'Antal krydser\'', 'required|is_natural_no_zero');
				$this->form_validation->set_rules('comment', 'Kommentar', 'required');
				if($this->form_validation->run() == TRUE) {
					unset($_POST['formname']);
					$_POST = $this->security->xss_clean($_POST);

					$this->Akstatus_model->increaseOrCreateStatus($alumneId, $_POST);
					$this->Aklog_model->addAkLog($alumneId, $_POST);
					redirect("nyintern/ak/showPersonalLog/$alumneId/success");
				}
			}

		}
	}


	public function admin(){
		$username = $this->session->userdata('username');
		$akRole = $this->session->userdata('akRole');
		

		if(!$username || empty($akRole)){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
		} else {
			$data['pagename'] = "ak";
			$data['pageheader'] = "Administrer Ak-krydser";
			
			$data['success'] = false;
			if($this->uri->segment(4) == "success"){
				$data['success'] = true;
			}

			$data['allAkStatus'] = $this->Akstatus_model->getAll();

			$this->showInternPage('intern/adminAk', $data);
		}

	}

	public function delete_log_element($idOnElement){
		$username = $this->session->userdata('username');
		$fullname = $this->session->userdata('fullname');
		$alumneId = $this->session->userdata('alumne_id');
		$akRole = $this->session->userdata('akRole');

		$backAlumneId = $this->uri->segment(5, $alumneId);

		if(!$username || empty($akRole)){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
		} else {
			$elementToDelete = $this->Aklog_model->getElementById($idOnElement);

			$this->Akstatus_model->decreaseStatus($elementToDelete[0]->alumne_id, $elementToDelete[0]->krydser);

			$this->Aklog_model->deleteFromLog($idOnElement);
			redirect("nyintern/ak/showPersonalLog/$backAlumneId/success");
		}
	}


	public function updatestatus($backAlumneId){
		$username = $this->session->userdata('username');
		$fullname = $this->session->userdata('fullname');
		$alumneId = $this->session->userdata('alumne_id');
		$akRole = $this->session->userdata('akRole');

		if($username && !empty($akRole)){

			if (!empty($_POST['formname']) && $_POST['formname'] == "updatestatus"){ 
				$this->form_validation->set_rules('krydser', '\'Status\'', 'required|integer');

				if($this->form_validation->run() == TRUE) {
					unset($_POST['formname']);
					$_POST = $this->security->xss_clean($_POST);

					$this->Akstatus_model->updateOrCreateStatus($backAlumneId, $_POST);
					redirect("nyintern/ak/showPersonalLog/$backAlumneId/success");
				}

			}
		}
	}


	public function reduceAllKrydser(){
		$username = $this->session->userdata('username');
		$fullname = $this->session->userdata('fullname');
		$alumneId = $this->session->userdata('alumne_id');
		$akRole = $this->session->userdata('akRole');
		
		if($username && !empty($akRole)){
			//You are have access
		
			$this->load->library('form_validation');
			$this->form_validation->set_rules('krydser', '\'Antal krydser\'', 'required|is_natural_no_zero');
			if($this->form_validation->run() == false) {
				$this->admin();
			} else {
				$notInAkList = $this->Akstatus_model->getAllSlackers();
				foreach($notInAkList as $slacker){
					$this->Akstatus_model->addAlumneToTable($slacker->ID);
				}

				$this->Akstatus_model->decreaseAllStatus($_POST['krydser']);
				redirect("/nyintern/ak/admin/success");
			}
		}
	}
	





}
