<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 'On');
class Vaerelsestjek extends MY_Controller {
	public function __construct()	{
			session_start();
			parent::__construct();

			$this->load->helper('form');
			$this->load->library('session');
			//$this->output->enable_profiler(TRUE);
	}




	public function index(){
		$username = $this->session->userdata('username');
		if(!$username){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
		}else{
			$data['pagename'] = "vaerelsestjek";
			$data['pageheader'] = "Værelsestjek";
			$this->showInternPage('intern/vaerelsestjek/overview', $data);
		}
		
	}





	public function besvar($roomId){
		$username = $this->session->userdata('username');
		if(!$username){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
		}else{
			$data['pagename'] = "besvar";
			$data['errormessage'] = "";
			$data['pageheader'] = "Besvar";
			$data['roomId'] = $roomId;
			$this->load->model("RoomCondition_model");
			$data['conditions'] = $this->RoomCondition_model->getConditionsByRoom($roomId);

			$this->load->model("RoomCriteria_model");
			$data['criteria'] = $this->RoomCriteria_model->getCriteria();

			$this->showInternPage('intern/vaerelsestjek/besvar', $data);
		}
	}





	private function validateFormInput(){
		$this->load->library('form_validation');
		$_POST = $this->security->xss_clean($_POST);
		$this->form_validation->set_rules('selectedwalls', 'Tilstand af vægge', 'required');
	}

	public function akoverview(){
		$username = $this->session->userdata('username');
		$ak = $this->session->userdata('akRole');
		

		if(!$username && !empty($ak)){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
		} else {
			$data['pagename'] = "ak";
			$data['pageheader'] = "AK Oversigt";



			$this->load->model("RoomCondition_model");
			$data['roomConditions'] = $this->RoomCondition_model->getAllNewestConditions();

			$this->load->model("RoomCriteria_model");
			$data['criteria'] = $this->RoomCriteria_model->getCriteria();

			

			$this->showInternPage('intern/vaerelsestjek/akoverview', $data);	
		}	
	}

	
function remove_danish_letters($url) {
    $url = strtolower($url);
    $url=str_replace('æ','ae',$url);
    $url=str_replace('ø','oe',$url);
    $url=str_replace('å','aa',$url);
    $url=str_replace(" ","",$url);
    //remove . 
    $url = preg_replace('/\./', '-', $url, (substr_count($url, '.') - 1));


    return $url;
}

	public function indsend($roomId){
		$username = $this->session->userdata('username');
		
		if($_POST){
			$this->validateFormInput();		

		}
		if (!$_POST || $this->form_validation->run() == FALSE){
			$this->besvar($roomId);
		}else{
			$alumneId = $this->session->userdata('alumne_id');
			$fullname = $this->session->userdata('fullname');
			$this->load->model("RoomCriteria_model");
			$criterias = $this->RoomCriteria_model->getCriteria();


			$criteriaString = "";
			$commentString = "";
			$imageString = "";
			$this->load->library('upload');
			foreach($criterias as $crit){

				$target_dir = "./public/image/intern/roomimages/".$roomId."/".$crit->id;

				$data = "";
				$userfiles = "userfile".$crit->id;
				//upload shit
                    $files = $_FILES;

		            $filesCount = count($_FILES[$userfiles]['name']);
		            if($_FILES[$userfiles]['name'][0]==""){
		            	$filesCount=0;
		            }

		            
		            for($i = 0; $i < $filesCount; $i++){
		                


		                $_FILES[$userfiles]['name'] = $this->remove_danish_letters($files[$userfiles]['name'][$i]);
		                $_FILES[$userfiles]['type'] = $files[$userfiles]['type'][$i];
		                $_FILES[$userfiles]['tmp_name'] = $files[$userfiles]['tmp_name'][$i];
		                $_FILES[$userfiles]['error'] = $files[$userfiles]['error'][$i];
		                $_FILES[$userfiles]['size'] = $files[$userfiles]['size'][$i];

                        

		                if (!file_exists($target_dir)) {
				    		mkdir($target_dir, 0777, true);
						}	

                        $config = array();
                        $config['upload_path']          = $target_dir;
		                $config['allowed_types']        = 'jpg|png|jpeg';
		                $config['max_size']      = '0';
                        $config['overwrite']     = FALSE;

                        $this->upload->initialize($config);


		                if($this->upload->do_upload($userfiles)){

							$fullPathToFile = trim($target_dir,'.').'/'.$_FILES[$userfiles]['name'];
			                if($data!=""){
			                	$data = $data . ";".$fullPathToFile; 
			                }
			                else{
			                	$data = $fullPathToFile;
			                }
		                }else{

		                	var_dump($this->upload->display_errors());
		                }
		                
		            }
		            


				$criteriaString .= $crit->id.":".$_POST["selected".$crit->id].";";
				$commentString .= $crit->id.":".$_POST["comment".$crit->id].";";
				$imageString .= $crit->id.":".$_POST["savedImages".$crit->id].$data."|";

			}

			
			$this->load->model("RoomCondition_model");


			$this->RoomCondition_model->addCondition($alumneId, $fullname,$roomId, date("YmdHis"),$criteriaString,$commentString,$imageString);

			redirect('nyintern/vaerelsestjek');
		}
	}

}