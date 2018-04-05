<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistik extends MY_Controller {

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
			$data['pagename'] = "statistik";
			$data['pageheader'] = "Statistik";

			$data['dtuStatistic'] =  $this->getStudyData("DTU");
			$data['kuStatistic'] =  $this->getStudyData("KU");
			$data['cbsStatistic'] =  $this->getStudyData("CBS");
			$data['rucStatistic'] =  $this->getStudyData("RUC");
			$data['ituStatistic'] =  $this->getStudyData("ITU");
			$data['kunstStatistic'] =  $this->getStudyData("Kunst");
	
			$this->showInternPage('intern/statistik', $data);
		}
	}

	
	public function getAllStudyData(){
			$data['dtuStatistic'] =  $this->getStudyData("DTU");
			$data['kuStatistic'] =  $this->getStudyData("KU");
			$data['cbsStatistic'] =  $this->getStudyData("CBS");
			$data['rucStatistic'] =  $this->getStudyData("RUC");
			$data['ituStatistic'] =  $this->getStudyData("ITU");
			$data['kunstStatistic'] =  $this->getStudyData("Kunst");

		
			$data = array();
			$data = $this->addStudyDataToData("DTU", $data);
			$data = $this->addStudyDataToData("KU", $data);
			$data = $this->addStudyDataToData("CBS", $data);
			$data = $this->addStudyDataToData("RUC", $data);
			$data = $this->addStudyDataToData("ITU", $data);
			$data = $this->addStudyDataToData("Kunst", $data);
			
			$res = "[";
			foreach ($data as $month => $monthAlumneArray) {
				if(count($monthAlumneArray) > 0){
					$res .= "{ \"date\": \"$month\" , ";

					foreach ($monthAlumneArray as $numberOfAlumneMonth) {
						$res .= "\"".$numberOfAlumneMonth["study"]."\": ".$numberOfAlumneMonth["value"].", ";
					}
					$res = substr($res, 0, -2)."}, ";

				}
			}

			$res = substr($res, 0, -2)."]";
			echo $res;


	}

	public function addStudyDataToData($study, $data){
		$result = $this->Internuser_model->getNumberOfAlumnePerMonthByStudy($study);
		foreach ($result as $numberOfAlumneMonth) {
			$valueArray = $this->mn2mstr($numberOfAlumneMonth->monthNumber);				
			if(array_key_exists($valueArray, $data)){
				$monthData = $data[$valueArray];
			} else {
				$monthData = array();
			}

			if($numberOfAlumneMonth->monthNumber != "" && $numberOfAlumneMonth->monthNumber != null){
				$monthData[$study] = array("study" => $study, "value" => $numberOfAlumneMonth->numberOfAlumne); 
			}

			$data[$valueArray] = $monthData;
		}

		return $data;
	}




/*
This method is old and should be removed
*/
	public function getStudyData($study){
		$result = $this->Internuser_model->getNumberOfAlumnePerMonthByStudy($study);
			$statisticToString ="";
	//	$statisticToString = "[";
		$i = 0;
		foreach ($result as $numberOfAlumneMonth) {
			if($i != 0){
				$statisticToString .= ",";
			}
			if($numberOfAlumneMonth->monthNumber != "" && $numberOfAlumneMonth->monthNumber != null){
				$statisticToString .= 
					"{\"date\": \"".$this->mn2mstr($numberOfAlumneMonth->monthNumber)."\", \"$study\": ".$numberOfAlumneMonth->numberOfAlumne."}";


				$i++;
			}
		}
	//	$statisticToString .= "]";

		return $statisticToString;
	}


	

	public function mn2mstr($monthNumber) {

		$Y = (int)(($monthNumber-1)/12);
		 $m = $monthNumber % 12;
		if ($m == 0) $m = 12;
	
		$str = $Y."-".$m."-1";
		return strtolower($str);
	}



	public function getAnsoegningerByStudyAndMonth(){
			$this->load->model('Ansoegninger_model');
			$result = $this->Ansoegninger_model->getAnsoegningerByStudyAndMonth();

			//We collect data by month
			$data = array();
			foreach($result as $ansoegElem){
				if(array_key_exists($ansoegElem->date, $data)){
					$monthData = $data[$ansoegElem->date];
				} else {
					$monthData = array();
				}
				$monthData[$ansoegElem->university] = array("study" => $ansoegElem->university, "value" => $ansoegElem->antal); 
				$data[$ansoegElem->date] = $monthData;
			}

		return $data;
	}

	private function getAnsoegningerByStudyAndThisYear(){
			$this->load->model('Ansoegninger_model');
			$result = $this->Ansoegninger_model->getAnsoegningerByStudyAndThisYear();

			//We collect data by month
			$data = array();
			foreach($result as $ansoegElem){
				if(array_key_exists($ansoegElem->date, $data)){
					$monthData = $data[$ansoegElem->date];
				} else {
					$monthData = array();
				}
				$monthData[$ansoegElem->university] = array("study" => $ansoegElem->university, "value" => $ansoegElem->antal); 
				$data[$ansoegElem->date] = $monthData;
			}

		return $data;
	}


	public function getAnsoegningerByStudyAndThisYearJSON(){
			$data = $this->getAnsoegningerByStudyAndThisYear();

			$res = "[";
			foreach($data as $month => $monthData){
				foreach($monthData as $monthElem){
					$res .= "{\"label\": \"".$monthElem["study"]."\", ";
					$res .= "\"value\":\"".$monthElem["value"]."\"}, ";
				}

			}
			$res = substr($res, 0, -2)."]";
			echo $res;
	}

	public function getAnsoegningerByStudyAndMonthTable(){
			$data = $this->getAnsoegningerByStudyAndMonth();

			$i = 0;
			$res = "<tr>";
			foreach($data as $month => $monthData){
				$i++;
				$res .= "<td><b>$month</b></td>";

				$res .= $this->getColumnOfStudyMonth("AU", $monthData);
				$res .= $this->getColumnOfStudyMonth("AAU", $monthData);
				$res .= $this->getColumnOfStudyMonth("CBS", $monthData);
				$res .= $this->getColumnOfStudyMonth("DTU", $monthData);
				$res .= $this->getColumnOfStudyMonth("ITU", $monthData);
				$res .= $this->getColumnOfStudyMonth("KU", $monthData);
				$res .= $this->getColumnOfStudyMonth("RUC", $monthData);
				$res .= $this->getColumnOfStudyMonth("SDU", $monthData);
				$res .= $this->getColumnOfStudyMonth("Andet", $monthData);

				$res .= "</tr>";
				if($i > 8){ break; } //Limit number of lines in table
			}
			echo $res;
	}

	private function getColumnOfStudyMonth($study, $monthData){
			if(array_key_exists($study, $monthData)){
					return "<td>".$monthData[$study]["value"]."</td>";
				} else {
					return "<td>0</td>";
				}
	}


	public function getAngsoegningStatisticJSON(){
			$this->load->model('Ansoegninger_model');
			$statisticToString = "[";

			for($i=0;$i<18;$i++) {
			//Because we show for 18 months

				$rundvisningRes = $this->Ansoegninger_model->getAnsoegningerByMonth(strtotime("-$i month"), "rundvisning");
				$fremlejeRes = $this->Ansoegninger_model->getAnsoegningerByMonth(strtotime("-$i month"), "fremleje");
				$statistic[$i]['dato'] =date('Y-m', strtotime("-$i month"));

				$statisticToString .= "{\"date\" : \"".$statistic[$i]['dato']."\", ";
					if(count($rundvisningRes) > 0){
					$statistic[$i]['count'] = $rundvisningRes;
					$statisticToString .= "\"rundvisning\": ".$statistic[$i]['count'].", ";	
				} else {
					$statistic[$i]['count'] = 0;
					$statisticToString .= "\"rundvisning\": 0, ";	;	
				}

				if(count($fremlejeRes) > 0){
					$statistic[$i]['countFemleje'] = $fremlejeRes;
					$statisticToString .= "\"fremleje\": ".$statistic[$i]['countFemleje']."";	
				} else {
					$statistic[$i]['countFemleje'] = 0;
					$statisticToString .= "\"fremleje\": 0";
				}
				$statisticToString .= "}, ";
			}

			$statisticToString = substr($statisticToString, 0, -2)."]";
			echo $statisticToString;
	}


	public function getCounterStatistic(){
		$this->load->model('Counter_model');
		$statisticToString = "[";

		for($i=0;$i<31;$i++) {
			$dato = date('d/m-Y', time()-60*60*24*$i);
			$res = $this->Counter_model->get_count_by_date($dato);
			$statistic[$i]['dato'] = date("Y-m-d", strtotime( str_replace('/', '-', $dato) ));

			$statisticToString .= "{\"date\": \"".$statistic[$i]['dato']."\", \"count\":";
			if(count($res) > 0){
				$statistic[$i]['count'] = $res[0]->count;
				$statisticToString .= "\"".$statistic[$i]['count']."\"";	
			} else {
				$statistic[$i]['count'] = 0;
				$statisticToString .= "\"0\"";	
			}
			$statisticToString .= "}, ";
		}
		$statisticToString = substr($statisticToString, 0, -2)."]";
		echo $statisticToString;
	}




	public function getAnsoegningerByHeardAboutUsAndThisYearJSON(){
			$this->load->model('Ansoegninger_model');
			$result = $this->Ansoegninger_model->getAnsoegningerByHowYourHeard();
			echo json_encode($result);
	}






}
