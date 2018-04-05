<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Soegvaerelse extends MY_Controller {

	public function __construct()	{
			session_start();
			parent::__construct();

			$this->load->helper('form');
			$this->load->library('session');
	}


	private function getRoomDescriptions(){
		$room = array();
		$roomFloor = array();
		$roomSide = array();
		$roomDetails = array();
		$roomDescription = array();
		$roomOnFloor = 0;
		$room_number = array();
		for($i = 1; $i<=61; $i++) {
			$roomOnFloor++;

			$room[$i]['detail'] = "";
			if($i >=1 && $i <= 10) {
				if($i == 1) { $roomOnFloor = 1;}
				//STUEN
				$room[$i]['floor'] = "stuen";
				$room[$i]['number'] = sprintf("%03d", $roomOnFloor);
				if($i == 9) $room[$i]['detail'] = "(røvhullet)";
				if($i >=1 && $i <= 8){ 
					$room[$i]['side'] = "mod gaden";
				} else {
					$room[$i]['side'] = "mod gården";
				}

			} elseif($i >= 11 && $i <=24) {
				if($i == 11) { $roomOnFloor = 1;}
				//1. sal
				$room[$i]['floor'] = "1. sal";
				$room[$i]['number'] = sprintf("%03d", $roomOnFloor+100);
				if ($i >= 11 && $i <=19) {
					$room[$i]['side'] = "mod gaden";
				} else {
					$room[$i]['side'] = "mod gården";
				}

			} elseif($i >= 25 && $i <=38) {
				if($i == 25) { $roomOnFloor = 1;}
				//2. sal
				$room[$i]['floor'] = "2. sal";
				$room[$i]['number'] = sprintf("%03d", $roomOnFloor+200);
				if($i >= 25 && $i <=33) {
					$room[$i]['side'] = "mod gaden";
				} else {
					$room[$i]['side'] = "mod gården";
				}

			} elseif($i >= 39 && $i <=52) {
				if($i == 39) { $roomOnFloor = 1;}
				//3. sal
				$room[$i]['floor']  = "3. sal";
				$room[$i]['number'] = sprintf("%03d", $roomOnFloor+300);
				if ($i >= 39 && $i <=47) {
					$room[$i]['side'] = "mod gaden";
				} else {
					$room[$i]['side'] = "mod gården";
				}

			} elseif($i >= 53 && $i <=61) {
				if($i == 53) { $roomOnFloor = 1;}
				//4. sal
				$room[$i]['floor']  = "4. sal";
				$room[$i]['number'] = sprintf("%03d", $roomOnFloor+400);
				if($i == 53 || $i == 56) { $room[$i]['detail'] = "(atelierværelse)";}
				if($i == 55) { $room[$i]['detail'] = "(fængslet)";}
				if($i == 54) { $room[$i]['detail'] = "(arresten)";}
				if($i >= 53 && $i <=56) {
					$room[$i]['side'] = "mod gaden";
				} else {
					$room[$i]['side'] = "mod gården";
					$room[$i]['detail'] = "(hemseværelse)";
				}
			}
		}
		return $room;
	}

	public function mn2m($monthNumber) {
		$m = $monthNumber % 12;
		//if ($m == 0) $m = 12;
		return $m;
	}

	public function mn2y($monthNumber) {
		$Y = (int)(($monthNumber)/12);
		return $Y;
	}



	public function index(){
		$username = $this->session->userdata('username');
		$alumneId = $this->session->userdata('alumne_id');
		
		$this->load->model('Kvotientoffer_model');
		$this->load->model('Kvotient_model');
		


		if(!$username){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
		} else {
			$data['pagename'] = "soegvaerelse";
			$data['pageheader'] = "Søg værelse";
			$data['offers'] = $this->Kvotientoffer_model->getOffers(); //All rooms offered
			
			$monthNrWithOffers = $this->Kvotientoffer_model->getMonthsWithOffers(); //Month with rooms offered
			$data['monthWithOffers'] = array();
			foreach ($monthNrWithOffers as $offeredMonthNr) {
				//We get month of year
		    array_push($data['monthWithOffers'], array($this->mn2m($offeredMonthNr->month), $offeredMonthNr->month)); 

			}
			$data['roomdata'] = $this->getRoomDescriptions();
			$data['myApplications'] = $this->Kvotient_model->getApplicationsByAlumneId($alumneId);
			
			$data['success'] = false;
			if($this->uri->segment(4) == "success"){
				$data['success'] = true;
			}

			$this->showInternPage('intern/soegvaerelse/overview', $data);
		}
	}


	public function soeg($monthNr){
		$username = $this->session->userdata('username');
		$alumneId = $this->session->userdata('alumne_id');

		$this->load->model('Kvotientoffer_model');
		$this->load->model('Adminuser_model');

		if(!$username){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
		} else {
			$data['pagename'] = "soegvaerelse";
			$data['pageheader'] = "Søg værelse";
			$data['offers'] = $this->Kvotientoffer_model->getOffersByMonthNr($monthNr);
			$data['monthOfferAsTime'] = $this->mn2y($monthNr)."-".($this->mn2m($monthNr)+1)."-01";
			$data['monthNr'] = $monthNr;
			$data['roomdata'] = $this->getRoomDescriptions();

			$data['userData'] = $this->Adminuser_model->getAlumneOnId($alumneId);

			if($this->uri->segment(4) == "success"){
				$data['success'] = true;
			}

			$this->showInternPage('intern/soegvaerelse/soeg', $data);
		}
	}


	private function validateAnsoegInput(){
		$this->load->library('form_validation');
		$_POST = $this->security->xss_clean($_POST);

		$this->form_validation->set_rules('leaveMonth', 'Måned for afslutning af studie', 'required');
		$this->form_validation->set_rules('leaveYear', 'År for afslutning af studie', 'required');

		$this->form_validation->set_rules('priority[0]', '1. prioritet', 'required');

		//For orlov
		for($i = 0; $i < sizeof($_POST['orlovMoveOutMonth']); $i++){
			$this->form_validation->set_rules('orlovMoveOutMonth['.$i.']', 'Start måned for orlov', '');
			if($_POST['orlovMoveOutMonth'][$i] != ""){
				$this->form_validation->set_rules('orlovMoveOutYear['.$i.']', 'Start måned for orlov', 'required');			
				$this->form_validation->set_rules('orlovMoveInMonth['.$i.']', 'Start måned for orlov', 'required');
				$this->form_validation->set_rules('orlovMoveInYear['.$i.']', 'Start måned for orlov', 'required');			
			}
		}	

	}

	private function getKvotientDataFromPOST($alumneId, $monthNr, $post){
		$this->load->model('Adminuser_model');
		$kvotient['alumne_id'] = $alumneId;
		$kvotient['doneStudyingMonth'] = $_POST['leaveMonth']+$_POST['leaveYear']*12;
		$kvotient['moveMonth'] = $monthNr;

		//moveInDate calculate
		$userData = $this->Adminuser_model->getAlumneOnId($alumneId);
		$moveInMonth = date("m", strtotime($userData[0]->moveInDay))-1;
		$moveInYear = date("Y", strtotime($userData[0]->moveInDay));
		$kvotient['moveInMonth'] = $moveInMonth+$moveInYear*12;

		$kvotient['applyDatetime'] = time();
		return $kvotient;
	}


	public function indsend($monthNr){
		$alumneId = $this->session->userdata('alumne_id');
		$this->load->model('Adminuser_model');
		$this->load->model('Kvotient_model');
		$this->load->model('Kvotient_priority_model');
		$this->load->model('Kvotient_orlov_model');

		if($_POST){
			$this->validateAnsoegInput();		
		}

		if (!$_POST || $this->form_validation->run() == FALSE){
		//	var_dump($_POST);
			$this->soeg($monthNr);
		} else {
			//Validated okay
			$kvotient = $this->getKvotientDataFromPOST($alumneId, $monthNr, $_POST);
			$kvotient['K'] = $this->calculateK($kvotient, $_POST);
			$priorityData['ansoegnings_id'] = $this->Kvotient_model->addKvotientApplication($kvotient);

			//Add priority
			$priorityData['alumne_id'] = $alumneId;
			for($i = 0; $i < sizeof($_POST['priority']); $i++){
					if($_POST['priority'][$i] != 0){
						$priorityData['priority'] = $i+1;
						$priorityData['vaerelse_id'] = $_POST['priority'][$i];
						$this->Kvotient_priority_model->addPriority($priorityData);
					}
			}
			

			//Add orlov
			$orlovData['ansoegnings_id'] = $priorityData['ansoegnings_id'];
			for($i = 0; $i < sizeof($_POST['orlovMoveOutMonth']); $i++){
					if($_POST['orlovMoveOutMonth'][$i] != ""){
						$orlovData['orlov_start'] = $_POST['orlovMoveOutMonth'][$i]+$_POST['orlovMoveOutYear'][$i]*12;
						$orlovData['orlov_end'] = $_POST['orlovMoveInMonth'][$i]+$_POST['orlovMoveInYear'][$i]*12;
						$orlovData['numberOfMonths'] = $orlovData['orlov_end']-$orlovData['orlov_start'];
						$this->Kvotient_orlov_model->addOrlov($orlovData);
					}
			}


			redirect('/nyintern/soegvaerelse/index/success');
		}


	}

	private function calculateA($kvotientData, $orlovData){
		$a = $kvotientData['moveMonth'] - $kvotientData['moveInMonth']; //missing orlov
				//Substrahiers the orlov from a.
		for($i = 0; $i < sizeof($orlovData['orlovMoveOutMonth']); $i++){
			if($orlovData['orlovMoveOutMonth'][$i] != ""){
			$orlovMoveOutMonthNr = $orlovData['orlovMoveOutMonth'][$i]+$orlovData['orlovMoveOutYear'][$i]*12;
			$orlovMoveInMonthNr = $orlovData['orlovMoveInMonth'][$i]+$orlovData['orlovMoveInYear'][$i]*12;
			$orlovLength = $orlovMoveInMonthNr - $orlovMoveOutMonthNr;
			$a = $a - $orlovLength;
			}
		}
	
		return $a;
	}

	private function calculateB($kvotientData){
		return $kvotientData['doneStudyingMonth'] - $kvotientData['moveMonth'];
	}	

	public function calculateK($kvotientData, $orlovData){
		$a = $this->calculateA($kvotientData, $orlovData);

		$b = $this->calculateB($kvotientData);
		return number_format($a*100.0/($a+$b+12), 2);
	}


	public function getKAsJson($monthNr){
		$alumneId = $this->session->userdata('alumne_id');

		if($_POST){
			$this->validateAnsoegInput();
		}
		if ($_POST && !$this->form_validation->run() == FALSE){
			//valid input
			$kvotient = $this->getKvotientDataFromPOST($alumneId, $monthNr, $_POST);
			$res['K'] = $this->calculateK($kvotient, $_POST);
			$res['a'] = $this->calculateA($kvotient, $_POST);
			$res['b'] = $this->calculateB($kvotient);

			echo json_encode($res);
		} else {
			echo validation_errors();
		}

	}
	
	public function getKvotientData($ansoegningsId){
		$this->load->model('Kvotient_model');
		$month = array("Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December");

		$username = $this->session->userdata('username');
		$fullname = $this->session->userdata('fullname');
		$alumneId = $this->session->userdata('alumne_id');
		$akRole = $this->session->userdata('akRole');
		$indstilling = $this->session->userdata('indstilling');
		

		$this->load->model('Kvotientoffer_model');


		$kvotientData = $this->Kvotient_model->getKvotientDataFromAnsoegningsId($ansoegningsId);
		if(!$username && (!empty($indstilling) || $kvotientData[0]->alumne_id != $alumneId)){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
		} else {
			/*
			 * This i a hack. 
			 * 1: We find total month of orlov
			 * 2: We make a fake period of that lengh, and calculate K and A from it.
			 * Just made such that we can reuse methods.
			 * */
			$totalOrlovMonth = 0;
			$orlovToViewData = array();
			foreach ($kvotientData as $i=>$key) {
				$totalOrlovMonth += $key->numberOfMonths;
				
				if($key->numberOfMonths > 0){
					$ovlovStartYear =  floor($key->orlov_start / 12);
					$orlovToViewData[$i]["orlovStart"] = "1. ".$month[($key->orlov_start % 12)]." ".$ovlovStartYear;
					$ovlovEndYear =  floor($key->orlov_end / 12);
					$orlovToViewData[$i]["orlovSlut"] = "1. ".$month[($key->orlov_end % 12)]." ".$ovlovEndYear;
					$orlovToViewData[$i]["orlovLength"] = $key->numberOfMonths;
				} 
			}
			$orlovData['orlovMoveOutMonth'][0] = 1;
			$orlovData['orlovMoveOutYear'][0] = 0;
			$orlovData['orlovMoveInMonth'][0] = 1+$totalOrlovMonth;
			$orlovData['orlovMoveInYear'][0] = 0;
	
			//var_dump($kvotientData);
			
			//Parses data
			$data['k'] = $this->calculateK((array) $kvotientData[0], $orlovData);
			$data['a'] = $this->calculateA((array) $kvotientData[0], $orlovData);
			$data['b'] = $this->calculateB((array) $kvotientData[0]);
			
			//var_dump($kvotientData);
			$moveInYear = floor($kvotientData[0]->moveInMonth / 12);
			$data['moveInMonth'] = "1. ".$month[($kvotientData[0]->moveInMonth % 12)]." ".$moveInYear;
			
			$moveYear = floor($kvotientData[0]->moveMonth / 12);
			$data['moveMonth'] = "1. ".$month[($kvotientData[0]->moveMonth % 12)]." ".$moveYear;
			
			$doneStudyingYear = floor($kvotientData[0]->doneStudyingMonth / 12);
			$data['doneStudying'] = "1. ".$month[($kvotientData[0]->doneStudyingMonth % 12)]." ".$doneStudyingYear;
			$data['applyDatetime'] = $kvotientData[0]->applyDatetime;
			
			$data['orlov'] = $orlovToViewData;	
			$data['totalOrlov'] = $totalOrlovMonth;
			
			$this->load->view('intern/soegvaerelse/kvotientDetailFrame', $data);	
		}
	}


	
	public function admin(){
		$username = $this->session->userdata('username');
		$indstilling = $this->session->userdata('indstilling');
		
		$this->load->model('Kvotientoffer_model');

		if(!$username && !empty($indstilling)){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
		} else {
			$data['pagename'] = "soegvaerelse";
			$data['pageheader'] = "Søg værelse";

			$data['offers'] = $this->Kvotientoffer_model->getOffers(); //All rooms offered
			$data['roomdata'] = $this->getRoomDescriptions();
			
			$data['success'] = false;
			if($this->uri->segment(4) == "success"){
				$data['success'] = true;				
			}

			$this->showInternPage('intern/soegvaerelse/admin', $data);	
		}	
	}

	public function getApplicationByRoom($roomNr){
		$username = $this->session->userdata('username');
		$fullname = $this->session->userdata('fullname');
		$alumneId = $this->session->userdata('alumne_id');
		$akRole = $this->session->userdata('akRole');
		$indstilling = $this->session->userdata('indstilling');
		$this->load->model('Kvotient_model');


		if(!$username && !empty($indstilling)){
			echo json_encode("Ikke adgang");
		} else {
			$applications = $this->Kvotient_model->getApplicationsByRoom($roomNr);
			foreach ($applications as $application) {
				//echo $application->ID."  ".$application->alumne_id."   ".$this->wonRoomAlgorithm($roomNr)."<br />";
				if($application->alumne_id == $this->wonRoomAlgorithm($roomNr)){
					$application->won =  1;
				}
			}
			echo json_encode($applications);
			
		}	
	}

	public function wonRoomAlgorithm($roomNr){
		$this->load->model('Kvotient_model');
		$this->load->model('Kvotientoffer_model');
		$applications = $this->Kvotient_model->getApplications();
		$applications[0]->won =  1;
		

		foreach ($applications as $application) {
			if(empty($roomOccupied[$application->vaerelse_id]) && empty($alumneGotRoom[$application->alumne_id])){			
				//echo "faar vaerelse: ".$application->vaerelse_id." ".$application->alumne_id."<br />";
				$roomOccupied[$application->vaerelse_id] = $application->alumne_id;
				$alumneGotRoom[$application->alumne_id] = $application->vaerelse_id;
				if(!empty($roomOccupied[$roomNr])){
					return $roomOccupied[$roomNr];
				}
				
			}
		}
		if(empty($roomOccupied[$roomNr])){
			return -1;
		}
		return $roomOccupied[$roomNr];
	}


	public function closeOffer($id){
		$this->load->model('Kvotientoffer_model');
		$this->load->model('Kvotient_model');
		
		$username = $this->session->userdata('username');
		$fullname = $this->session->userdata('fullname');
		$alumneId = $this->session->userdata('alumne_id');
		$akRole = $this->session->userdata('akRole');
		$indstilling = $this->session->userdata('indstilling');
		
		if(!$username && !empty($indstilling)){
			echo json_encode("Ikke adgang");
		} else {
			$this->Kvotient_model->deleteAnsoegningByOfferId($id);
			$this->Kvotientoffer_model->deleteOfferById($id);
			redirect("nyintern/soegvaerelse/admin/success");
		}
	}
	
	public function createoffer(){
		$this->load->model('Kvotientoffer_model');
		$this->load->library('form_validation');
		$_POST = $this->security->xss_clean($_POST);
		$this->form_validation->set_rules('month', 'Måned', 'required');
		$this->form_validation->set_rules('year', 'År', 'required');
		$this->form_validation->set_rules('vaerelses_id', 'Værelse', 'required');
		
		$username = $this->session->userdata('username');
		$fullname = $this->session->userdata('fullname');
		$alumneId = $this->session->userdata('alumne_id');
		$akRole = $this->session->userdata('akRole');
		$indstilling = $this->session->userdata('indstilling');
		
		if(!$username && !empty($indstilling)){
			echo json_encode("Ikke adgang");
		} else {
			if($this->form_validation->run() == FALSE){
				$this->admin();
			} else {
				$_POST['month']=$_POST['month'] + $_POST['year']*12;
				unset($_POST['year']);
				$roomDescription = $this->getRoomDescriptions();
				$_POST['vaerelses_num'] = $roomDescription[$_POST['vaerelses_id']]['number'];
	
				$this->Kvotientoffer_model->addOffer($_POST);
				
				redirect("nyintern/soegvaerelse/admin/success");
			}
		}
	}


}
