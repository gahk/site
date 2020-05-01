<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Optagelse extends MY_Controller {
	public $indstillingMail = "indstillingen@gahk.dk";
	public $heardAboutUsOption = array(
		array("danish"=>"På sociale medier (Facebook eller Instagram)", "english"=>"On social media (Facebook or Instagram)"), 
		array("danish"=>"På en hjemmeside", "english"=>"On the web"),
		array("danish"=>"Fra beboere der var på mit studie for at fortælle om kollegiet", "english"=>"From residents who were at my university to talk about the kollegium"),
		array("danish"=>"Annonce i avis", "english"=>"Advertisement in a newspaper"),
		array("danish"=>"Fået anbefalet af en jeg kender", "english"=>"Recommended by one i know"),
		array("danish"=>"Set en plakat", "english"=>"I saw a poster"),
		array("danish"=>"Selv fundet frem til det", "english"=>"I looked it up myself")
	); 
	public function __construct()	{
		  parent::__construct();
			$this->output->set_header("HTTP/1.0 200 OK");
			$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
			$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
			$this->output->set_header("Pragma: no-cache"); 
			$this->output->cache(0);
			$this->load->model('Page_model');
			$this->load->model('Pylon_calendar_model');
			$this->counter();
			$this->load->library('recaptcha');
			$this->load->helper('form');
	}
	public function index()	{
		$pageId = 6;
		$data['page'] = $this->Page_model->get_page($pageId);
		$data['bgpic'] = $data['page'][0]->bgpic;
		$data['pageid'] = $data['page'][0]->id;
		$data['menucat'] = $data['page'][0]->menuCat;
		$data['hidefooter'] = true;
		$this->load->view('layout/header.php', $data);
		$this->load->view('optagelse/overview');
		$this->load->view('layout/bottom.php');
	}
	public function ansoeg()	{
		$this->load->library('form_validation');
		$data['recaptcha'] = $this->recaptcha->getWidget();
		$pageId = 7;
		$data['page'] = $this->Page_model->get_page($pageId);
		$data['bgpic'] = $data['page'][0]->bgpic;
		$data['pageid'] = $data['page'][0]->id;
		$data['menucat'] = $data['page'][0]->menuCat;
		$data['language'] = "danish";
		$data['heardAboutUsOption'] = $this->heardAboutUsOption;
		$data['success'] = false;
		if($this->uri->segment(3) == "success"){
			$data['success'] = true;
		}
		$data['hidefooter'] = true;
		$this->load->view('layout/header.php', $data);
		$this->load->view('small_standart_page');
		$this->load->view('optagelse/rundvisning_box');
		$this->load->view('layout/bottom.php');
	}
	public function send_rundvisning(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules('fullName', 'Fulde navn', 'required');
		$this->form_validation->set_rules('gender', 'Køn', 'required');
		$this->form_validation->set_rules('email', 'E-mail', 'required');
		$this->form_validation->set_rules('age', 'Alder', 'required');
		$this->form_validation->set_rules('studyyear', 'Antal år studeret', 'required');
		$this->form_validation->set_rules('yearleft', 'Studieår tilbage', 'required');

		$this->form_validation->set_rules('university', 'Universitet', 'required');
		$this->form_validation->set_rules('heardAboutUs', 'Hvorfra har du hørt om kollegiet?', 'required');
		$this->form_validation->set_rules('fieldofstudy', 'Studieretning', 'required');
		$this->form_validation->set_rules('motivation', 'Motivation', 'required');
		$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|callback_validateCaptcha');
		if ($this->form_validation->run() == FALSE){
			$this->ansoeg();
		} else {
		//Persist
		$_POST = $this->security->xss_clean($_POST);
		unset($_POST['g-recaptcha-response']);
		$_POST['typeOfAnsoegning'] = "rundvisning";
		$this->load->model('Ansoegninger_model');
		$ansoegId = $this->Ansoegninger_model->addAnsoegning($_POST);
		//Mail
		$message  = "Til indstillingen.\r\n";
		$message .= "Forespørgsel om rundvisning er modtaget fra følgende person:\r\n\r\n";
		$message .= "Navn: ".$_POST['fullName']."\r\n";
		$message .= "E-mail: ".$_POST['email']."\r\n";
		$message .= "Alder: ".$_POST['age']."\r\n";
		$message .= "Antal år studeret: ".$_POST['studyyear']."\r\n";
		$message .= "Studieår tilbage: ".$_POST['yearleft']."\r\n";

		$message .= "Universitet: ".$_POST['university']."\r\n";
		$message .= "Studieretning: ".$_POST['fieldofstudy']."\r\n";
		$message .= "Hvorfra har du hørt om kollegiet?: ".$_POST['heardAboutUs']."\r\n";
		$message .= "\nForespørgslen blev modtaget d. ".date("j/n/Y")."\r\n\r\n";
		$message .= "Motivation:\r\n".$_POST['motivation']."\r\n\r\n";
		$message .= "Registrer ansøgningen som modtaget på følgende link:\r\n<a href='".base_url("optagelse/setasreceived/".$ansoegId)."'>".base_url("optagelse/setasreceived/".$ansoegId)."</a>\r\n\r\n";
		
			if ($this->sendMail($message, $_POST['email'], $_POST['fullName'], false, $this->indstillingMail)){
				//We send a auto-reply
				$autosvar  = "".$_POST['fullName']."\r\n\r\n";
				$autosvar .= "Vi har modtaget din anmodning om at komme\r\n";
				$autosvar .= "på rundvisning på G. A. Hagemanns Kollegium.\r\n";
				$autosvar .= "Vi bestræber os på at besvare så mange anmodninger som muligt, bliver du dog ikke inviteret på rundvisning, er du velkommen til at sende en anmodning igen.\r\n\r\n";
				$autosvar .= "Vi giver desværre ikke afslag.\r\n\r\n";
				$autosvar .= "Mvh. Indstillingen\r\n";
				$autosvar .= "G. A. Hagemanns\r\n";
				$autosvar .= "Kristianiagade 10\r\n";
				$autosvar .= "2100 København Ø\r\n\r\n";
				$autosvar .= "Dette er et autosvar og kan ikke besvares.";
				$this->sendMail($autosvar, "autosvar@gahk.dk", "G. A. Hagemanns Kollegium", false, $_POST['email']);
				redirect('/optagelse/ansoeg/success');
			} else {
				echo "Systemet er for tiden nede. Prøv igen senere eller kontakt os på it@gahk.dk";
			}
		}
	}
/**
*   FREMLEJE
*/
	public function fremlej()	{
		$this->load->library('form_validation');
		if($this->uri->segment(3) == "eng"){
			$pageId = 9;
			$this->lang->load('recaptcha', 'english');
			$this->lang->load('fremleje', 'english');
			$data['language'] = "english";
		} else {
			$pageId = 8;
			$this->lang->load('fremleje', 'danish');
			$this->lang->load('recaptcha', 'danish');
			$data['language'] = "danish";
		}
		$data['recaptcha'] = $this->recaptcha->getWidget();
		//Sending if success message should be shown
		$data['success'] = false;
		if($this->uri->segment(3) == "success"){
			$data['success'] = true;
		}
		$data['page'] = $this->Page_model->get_page($pageId);
		$data['bgpic'] = $data['page'][0]->bgpic;
		$data['pageid'] = $data['page'][0]->id;
		$data['menucat'] = $data['page'][0]->menuCat;
		$data['heardAboutUsOption'] = $this->heardAboutUsOption;
		$data['hidefooter'] = true;
		$this->load->view('layout/header.php', $data);
		$this->load->view('small_standart_page');
		$this->load->view('optagelse/fremlej_box', $data);
		$this->load->view('layout/bottom.php');
	}
	public function send_fremleje(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules('fullName', 'Fulde navn', 'required');
		$this->form_validation->set_rules('email', 'E-mail', 'required');
		$this->form_validation->set_rules('age', 'Alder', 'required');
		$this->form_validation->set_rules('occupation', 'occupation', 'required');
		$this->form_validation->set_rules('heardAboutUs', 'Hvorfra har du hørt om kollegiet?', 'required');
		$this->form_validation->set_rules('motivation', 'Motivation', 'required');
		$this->form_validation->set_rules('gender','Køn','required');
		$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|callback_validateCaptcha');
		if ($this->form_validation->run() == FALSE){
			$this->fremlej();
		} else {
			//Persist
			$_POST = $this->security->xss_clean($_POST);
			unset($_POST['g-recaptcha-response']);
			$_POST['typeOfAnsoegning'] = "fremleje";
			$this->load->model('Ansoegninger_model');
			$this->Ansoegninger_model->addAnsoegning($_POST);
			//Mail
			$message = "Til indstillingen.\r\n";
			$message .= "Forespørgsel om fremleje er modtaget fra følgende person:\r\n\r\n";
			$message .= "Navn: ".$_POST['fullName']."\r\n";
			$message .= "E-mail: ".$_POST['email']."\r\n";
			$message .= "Alder: ".$_POST['age']."\r\n";
			$message .= "Beskæftigelse: ".$_POST['occupation']."\r\n";
			$message .= "Hvorfra har du hørt om kollegiet?: ".$_POST['heardAboutUs']."\r\n";
			$message .= "\nForespørgslen blev modtaget d. ".date("j/n/Y")."\r\n\r\n";
			$message .= "Motivation:\r\n".$_POST['motivation']."";
			//if ($this->sendMail($message, $_POST['email'], $_POST['fullName'], true, $this->indstillingMail)){
			if(TRUE){
				//Auto reply
				if($this->uri->segment(3) == "eng"){
					$autosvar  = "".$_POST['fullName']."\r\n\r\n";
					$autosvar .= "Thanks for your application for subletting\r\n";
					$autosvar .= "a room at G. A. Hagemanns Kollegium.\r\n";
					$autosvar .= "We have received your information and will contact you if we have anything to offer you.\r\n\r\n";
					$autosvar .= "Mvh. Indstillingen\r\n";
					$autosvar .= "G. A. Hagemanns\r\n";
					$autosvar .= "Kristianiagade 10\r\n";
					$autosvar .= "2100 København Ø\r\n\r\n";
					$autosvar .= "This is a no-reply and cannot be replied to";
				} else {
					$autosvar  = "".$_POST['fullName']."\r\n\r\n";
					$autosvar .= "Tak for din ansøgning om fremlejning\r\n";
					$autosvar .= "af et værelse på G. A. Hagemanns Kollegium.\r\n";
					$autosvar .= "Vi har modtaget din ansøgning og vil kontakte dig hvis vi har et værelse ledigt som vi kan tilbyde dig \r\n\r\n";
					$autosvar .= "Mvh. Indstillingen\r\n";
					$autosvar .= "G. A. Hagemanns\r\n";
					$autosvar .= "Kristianiagade 10\r\n";
					$autosvar .= "2100 København Ø\r\n\r\n";
					$autosvar .= "Dette er et autosvar og kan ikke besvares.";
				}
				$this->sendMail($autosvar, "autosvar@gahk.dk", "G. A. Hagemanns Kollegium", true, $_POST['email']);
				redirect('/optagelse/fremlej/success');
			} else {
				echo "The system is currently down. Try again later or contact us by it@gahk.dk";
			}
		}
	}
	private function sendMail($message, $from, $fromName, $isFremlejer, $to){
		
		$this->_CI =& get_instance();
    	$this->_CI->config->load('email');
    	$subject = "";
			if($isFremlejer){
				$subject = "GAHK Fremleje Ansogning: ".$_POST['fullName'];
			} else {
				$subject = "GAHK Rundvisning: ".$_POST['fullName'];
			}
		
		$config['protocol'] = "smtp";
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = "plain";
        $config['crlf'] = "\r\n";    
		$config['newline'] = "\r\n"; 
    	$this->_eConfig = $this->_CI->config->item('smtp');
    
        $config['smtp_pass'] = $this->_eConfig['smtp_pass'];
        $config['smtp_host'] = $this->_eConfig['smtp_host'];
        $config['smtp_user'] = $this->_eConfig['smtp_user'];
        $this->load->library('email');
        $this->email->initialize($config);
		$this->email->from('autosvar@gahk.dk', $fromName);
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message);
		if(!$this->email->send()){
			var_dump($this->email->print_debugger());
			return FALSE;
		}
		return TRUE;
	}
	public function validateCaptcha($value) {
	    $recaptcha = $_POST['g-recaptcha-response'];
        if (!empty($recaptcha)) {
            $response = $this->recaptcha->verifyResponse($recaptcha,$_SERVER['REMOTE_ADDR']);
            if (isset($response['success']) and $response['success'] === true) {
                return TRUE;
            }
        }else{
            return FALSE;
        }
	    
	}
	public function listansoegninger(){
		$rowsPerPage = 50;
		$this->load->library('session');
		$username = $this->session->userdata('username');
		$editpage = $this->session->userdata('editpage');
		$administrator = $this->session->userdata('administrator');
		$indstilling = $this->session->userdata('indstilling');
		$fullname = $this->session->userdata('fullname');
		$akRole = $this->session->userdata('akRole');
		$alumneId = $this->session->userdata('alumne_id');
		$data['menucat'] = 0;
		$data['bgpic'] = base_url("public/image/bg/adminBg.png");
		if(!$username){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
		} else if($indstilling != 1){
				echo "Ingen adgang";
		} else{
				$data['username'] = $username;
				$data['editpage'] = $editpage;
				$data['administrator'] = $administrator;
				$data['fullname'] = $fullname;
				$data['akRole'] = $akRole;
				$data['indstilling'] = $indstilling;
				$data['pagename'] = "ansogninger";
				$data['pageheader'] = "Ansøgninger";
				$data['months'] = array("Jan","Feb","Mar","Apr","Maj","Jun","Jul","Aug","Sep","Okt","Nov","Dec");
				$this->load->model('Ansoegninger_model');
				$from = 0;
				if(isset($_GET['from'])){
					$from = $_GET['from'];
				}
				
				$data['ansoegninger'] = $this->Ansoegninger_model->getNewestAnsoegninger($from, $rowsPerPage);
				$data['numberofpages'] = ceil($this->Ansoegninger_model->numberOfAnsoegninger()/$rowsPerPage);
				$data['currentpage'] = $from/$rowsPerPage;
				$data['rowsPerPage'] = $rowsPerPage;
//				$this->load->view('intern/header', $data);
//				$this->load->view('layout/adminHeader.php', $data);
//				$this->load->view('optagelse/list_ansoegninger_box.php');
//				$this->load->view('layout/bottom.php');
//				$this->load->view('intern/footer');
				$this->showInternPage('optagelse/list_ansoegninger_box.php', $data);
		}
	
	}
	public function showAnsoegning($id){
		$this->load->library('session');
		$username = $this->session->userdata('username');
		$editpage = $this->session->userdata('editpage');
		$administrator = $this->session->userdata('administrator');
		$fullname = $this->session->userdata('fullname');
		$akRole = $this->session->userdata('akRole');
		$indstilling = $this->session->userdata('indstilling');
		$alumneId = $this->session->userdata('alumne_id');
		
		
		$data['menucat'] = 0;
		$data['bgpic'] = base_url("public/image/bg/adminBg.png");
		if(!$username){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
		} else if($indstilling != 1){
				echo "Ingen adgang";
		} else{
				$data['username'] = $username;
				$data['editpage'] = $editpage;
				$data['administrator'] = $administrator;
				$data['fullname'] = $fullname;
				$data['akRole'] = $akRole;
				$data['indstilling'] = $indstilling;
				$data['pagename'] = "ansogninger";
				$data['pageheader'] = "Ansøgning";
				
				$data['months'] = array("Jan","Feb","Mar","Apr","Maj","Jun","Jul","Aug","Okt","Sep","Nov","Dec");
				$this->load->model('Ansoegninger_model');
				$data['ansoegning'] = $this->Ansoegninger_model->getAnsoegningerById($id);
				$data['success'] = false;
				if($this->uri->segment(4) == "success"){
					$data['success'] = true;
				}
				//$this->load->view('layout/adminHeader.php', $data);
				//$this->load->view('intern/header', $data);		
				//$this->load->view('optagelse/show_ansoegninger_box.php', $data);
				//$this->load->view('intern/footer');
				//$this->load->view('layout/bottom.php');
				$this->showInternPage('optagelse/show_ansoegninger_box.php', $data);
		}
	}
	public function setasreceived($id){
		$this->load->library('session');
		$username = $this->session->userdata('username');
		$editpage = $this->session->userdata('editpage');
		$administrator = $this->session->userdata('administrator');
		$fullname = $this->session->userdata('fullname');
		$akRole = $this->session->userdata('akRole');
		$indstilling = $this->session->userdata('indstilling');
		$alumneId = $this->session->userdata('alumne_id');
		
		
		$data['menucat'] = 0;
		$data['bgpic'] = base_url("public/image/bg/adminBg.png");
		if(!$username){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
		} else if(empty($indstilling)){
			echo "Ingen adgang";
		} else {
			
			$this->load->model('Ansoegninger_model');
			$this->Ansoegninger_model->setAnsoegningAsReceived($id, $alumneId);
			redirect("/optagelse/showAnsoegning/$id/success");
			
		}
	}
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */