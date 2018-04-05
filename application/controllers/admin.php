<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller {

	public function __construct()	{
			session_start();
			parent::__construct();
			$this->counter();

			$this->load->helper('form');
			$this->load->library('session');
	}

	public function index(){
		$username = $this->session->userdata('username');
		$administrator = $this->session->userdata('administrator');
		$editpage = $this->session->userdata('editpage');
		$fullname = $this->session->userdata('fullname');
		$indstilling = $this->session->userdata('indstilling');
		$oelkaelder = $this->session->userdata('oelkaelder');

		$data['menucat'] = 0;
		$data['bgpic'] = base_url("public/image/bg/adminBg.png");
		if(!$username){
			$this->login();
		} else {
			$data['username'] = $username;
			$data['administrator'] = $administrator;
			$data['editpage'] = $editpage;
			$data['fullname'] = $fullname;
			$data['indstilling'] = $indstilling;
			$data['oelkaelder'] = $oelkaelder;
			$data['statistic'] = $this->getCounterStatistic();
			$data['rundvisningStatistic'] = $this->getAngsoegningStatistic("rundvisning");
			$data['fremlejeStatistic'] = $this->getAngsoegningStatistic("fremleje");

	//		var_dump($data);

			$this->load->view('layout/adminHeader.php', $data);
			$this->load->view('admin/dashboard');
			$this->load->view('admin/ansoeg_statistic_box.php');
			$this->load->view('admin/statisticBox');
			$this->load->view('layout/bottom.php');
		}
	}



	public function login(){
		$this->load->library('form_validation');
		$this->load->model('Adminuser_model');

		$data['menucat'] = 0;
		$data['showError'] = false;
		$data['bgpic'] = base_url("public/image/bg/adminBg.png");
		$showLoginForm = false;

		$this->form_validation->set_rules('email', 'E-mail', 'required');
		$this->form_validation->set_rules('password', 'Kodeord', 'required');

		if ($this->form_validation->run() == FALSE){
			$showLoginForm = true;
		} else {
			//Try to login
			$result = $this->Adminuser_model->login($_POST['email'], hash('sha256', $_POST['password']));

			if(count($result) > 0){
				//Is logged in with success
				$this->session->set_userdata(array('username' => $result[0]->email));
				$this->session->set_userdata(array('administrator' => $result[0]->administrator));
				$this->session->set_userdata(array('editpage' => $result[0]->editpage));
				$this->session->set_userdata(array('indstilling' => $result[0]->indstilling));
				$this->session->set_userdata(array('alumne_id' => $result[0]->alumne_id));
				$this->session->set_userdata(array('fullname' => $result[0]->firstName." ".$result[0]->lastName));
				$this->session->set_userdata(array('akRole' => $result[0]->ak));
				$this->session->set_userdata(array('oelkaelder' => $result[0]->oelkaelder));
				redirect($this->uri->uri_string());
			} else {
				//No access
				$data['showError'] = true;
				$showLoginForm = true;
			}
		}

		if($showLoginForm){
			$data['username'] = "";
			$this->load->view('layout/adminHeader.php', $data);
			$this->load->view('admin/login');
			$this->load->view('layout/bottom.php');
		}

	}


	public function useradm(){
		$username = $this->session->userdata('username');
		$administrator = $this->session->userdata('administrator');
		$fullname = $this->session->userdata('fullname');
		$editpage = $this->session->userdata('editpage');
		$alumne_id = $this->session->userdata('alumne_id');
		$indstilling = $this->session->userdata('indstilling');
		$inspektion = $this->session->userdata('inspektion');
		$akRole = $this->session->userdata('akRole');
		$oelkaelder = $this->session->userdata('oelkaelder');

		$data['menucat'] = 0;
		$data['bgpic'] = base_url("public/image/bg/adminBg.png");
		if(!$username){
			$this->login();
		} else {
			if($administrator != 1){
				echo "Ingen adgang";
			} else{
				$data['username'] = $username;
				$data['administrator'] = $administrator;
				$data['editpage'] = $editpage;
				$data['fullname'] = $fullname;
				$data['indstilling'] = $indstilling;
				$data['inspektion'] = $inspektion;
				$data['loggedInAlumnId'] = $alumne_id;
				$data['akRole'] = $akRole;
				$data['oelkaelder'] = $oelkaelder;
//				$data['statistic'] = $this->getCounterStatistic();

				if($this->session->flashdata('success') != ""){
					$data['success'] = $this->session->flashdata('success');
				}
				if($this->session->flashdata('fejl') != ""){
					$data['fejl'] = $this->session->flashdata('fejl');
				}

				$this->load->model('Adminuser_model');
				$data['useradm'] = $this->Adminuser_model->listAllAdminUser();

				$this->load->view('layout/adminHeader.php', $data);
				$this->load->view('admin/useradm.php');
				$this->load->view('layout/bottom.php');
			}
		}

	}


	private function getCounterStatistic(){
		$statisticToString = "[";

		for($i=0;$i<31;$i++) {
			$dato = date('d/m-Y', time()-60*60*24*$i);
			$res = $this->Counter_model->get_count_by_date($dato);
			$statistic[$i]['dato'] = date("Y-m-d", strtotime( str_replace('/', '-', $dato) ));

			if($i != 0){
				$statisticToString .= ",";
			}
			$statisticToString .= "['".$statistic[$i]['dato']."', ";
			if(count($res) > 0){
				$statistic[$i]['count'] = $res[0]->count;
				$statisticToString .= $statistic[$i]['count']."]";	
			} else {
				$statistic[$i]['count'] = 0;
				$statisticToString .= "0]";	
			}
		}
		$statisticToString .= "]";
		return $statisticToString;
	}



	public function getAngsoegningStatistic($typeOfAnsoegning){
		$this->load->model('Ansoegninger_model');
		$statisticToString = "[";

		for($i=0;$i<18;$i++) {
			//Because we show for 18 months

			$res = $this->Ansoegninger_model->getAnsoegningerByMonth(strtotime("-$i month"), $typeOfAnsoegning);
			$statistic[$i]['dato'] =date('Y-m-d', strtotime("-$i month"));

			if($i != 0){
				$statisticToString .= ",";
			}
			$statisticToString .= "['".$statistic[$i]['dato']."', ";
			if(count($res) > 0){
				$statistic[$i]['count'] = $res;
				$statisticToString .= $statistic[$i]['count']."]";	
			} else {
				$statistic[$i]['count'] = 0;
				$statisticToString .= "0]";	
			}

		}
		$statisticToString .= "]";
		return $statisticToString;
	}



	public function logout(){
		$this->session->sess_destroy();
		session_unset();
		redirect("admin");	
	}

	public function alumneSearch(){
		$searchWord =  $_GET['term'];
		$username = $this->session->userdata('username');
		if($username){
			$this->load->model('Adminuser_model');
			$result = $this->Adminuser_model->searchOnAlumne($searchWord);
			echo json_encode($result);
			return;
		}
	}


	public function adduseradm(){
		$username = $this->session->userdata('username');
		$this->load->model('Adminuser_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('fullname', 'Alumne', 'required');

		if(!$username){
			$this->login();
		} else {
			if ($this->form_validation->run() == FALSE){
				$this->session->set_flashdata('fejl', 'Du skal indtaste navnet på alumnes navn.');
			} else {
				$alumneResult = $this->Adminuser_model->searchOnAlumne($_POST['fullname']);
				if(count($alumneResult) <= 0){
					$this->session->set_flashdata('fejl', 'Alumnen blev ikke fundet. Prøv igen');
				} else if(count($alumneResult) > 1) {
					$this->session->set_flashdata('fejl', 'Flere alumner blev fundet ved søgningen. Skriv mere af alumnens navn');
				} else if(count($alumneResult) == 1){
					//We create the roles
					unset($_POST['fullname']);
					$_POST['alumne_id'] = $alumneResult[0]->id;

					$result = $this->Adminuser_model->addUserAdm($_POST);
					$this->session->set_flashdata('success', '<b>Success.</b> Alumnen har nu fået tildelt rollen.');
				
				}
			}

		}
		redirect("admin/useradm");
		return;
	}


	public function deleteuseradm($id){
		$username = $this->session->userdata('username');
		if(!$username){
			$this->login();
		} else {
			if($id){
				$this->load->model('Adminuser_model');
				$this->session->set_flashdata('success', '<b>Success.</b> Alumnen har nu mistet sin rettighed til at administrerer gahk.dk');
				$this->Adminuser_model->deleteuseradm($id);
			}
		redirect("admin/useradm");
		}
	}


}
