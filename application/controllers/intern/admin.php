<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller {

	public function __construct()	{
			session_start();
			parent::__construct();

			$this->load->helper('form');
			$this->load->library('session');
	}

	public function index(){
		$username = $this->session->userdata('username');
		$administrator = $this->session->userdata('administrator');
		$editpage = $this->session->userdata('editpage');
		$fullname = $this->session->userdata('fullname');
		$indstilling = $this->session->userdata('indstilling');


		if(!$username){
			$this->login();
		} else {
			redirect("nyintern");
		}
	}

	public function editinfo() {
		$username = $this->session->userdata('username');
		$alumneId = $this->session->userdata('alumne_id');
		
		if(!$username){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
			return;
		}

		$this->load->library('form_validation');
		$this->load->model('Internuser_model');

		$data['showError'] = false;

		$this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
		$this->form_validation->set_rules('phone', 'Telefonnummer', 'required|min_length[8]');

		$alumne = $this->Internuser_model->getUser($alumneId);

		$data = array(
			"email" => $alumne->email,
			"phone" => $alumne->phone
		);

		if ($this->form_validation->run() == TRUE) {
			$data = array(
				"email" => $_POST['email'],
				"phone" => $_POST['phone']
			);

			$this->Internuser_model->updateUser($data, $alumneId);
		}

		$data['pageheader'] = "Rediger oplysninger";

		$this->showInternPage('intern/editinfo', $data);
	}

	public function changepassword() {
		$username = $this->session->userdata('username');
		
		if(!$username){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
			return;
		}

		$this->load->library('form_validation');
		$this->load->model('Internuser_model');

		$data['showError'] = false;

		$this->form_validation->set_rules('oldpassword', 'Gammelt kodeord', 'required');
		$this->form_validation->set_rules('newpassword', 'Nyt kodeord', 'required');
		$this->form_validation->set_rules('confpassword', 'Gentag kodeord', 'required');

		if ($this->form_validation->run() == FALSE){
			
		} elseif ($_POST['newpassword'] != $_POST['confpassword']) {
			$data['showError'] = true;
			$data['errorText'] = "De to passwords matchede ikke!";
		} else {
			if ($this->Internuser_model->changePassword($username, $_POST['oldpassword'], $_POST['newpassword'])) {
				redirect("nyintern/admin/logout");
				return;
			} else {
				$data['showError'] = true;
				$data['errorText'] = "Det gamle kodeord er ikke korrekt!";
			}
		}

		$data['pageheader'] = "Skift kodeord";

		$this->showInternPage('intern/changepass', $data);
	}

	public function login(){
		$username = $this->session->userdata('username');
		
		if($username){
			redirect("nyintern");
		}

		$this->load->library('form_validation');
		$this->load->model('Internuser_model');
		$this->load->model('Adminuser_model');

		// Try to login using cookies
		$result = $this->Adminuser_model->loginSession();
		if (count($result) == 0) {
			//Not admin, we try regular user
			$result = $this->Internuser_model->loginSession();
		}
		if(count($result) > 0){
			//Is logged in with success
			$this->startSession($result);
			return;
		}


		$showLoginForm = false;
		$data['showError'] = false;

		$this->form_validation->set_rules('email', 'E-mail', 'required');
		$this->form_validation->set_rules('password', 'Kodeord', 'required');

		if ($this->form_validation->run() == FALSE){
			$showLoginForm = true;
		} else {
			//Try to login
			$result = $this->Adminuser_model->login($_POST['email'], hash('sha256', $_POST['password']));

			if(count($result) == 0){
				//Not admin, we try regular user
				$result = $this->Internuser_model->login($_POST['email'], hash('sha256', $_POST['password']));
			}

			//Do the rest
			if(count($result) > 0){
				// Save the login session
				$this->Internuser_model->createSession($result[0]->ID);

				//Is logged in with success
				$this->startSession($result);
				return;
			} else {
				//No access
				$data['showError'] = true;
				$showLoginForm = true;
			}
		}

		if($showLoginForm){
			$data['username'] = "";
			$data['pagename'] = "logind";
			$data['pageheader'] = "Log ind";
			$this->session->keep_flashdata('redirectToUrlAfterLogin'); //Remember where we redirect to

			$this->load->view('intern/header.php', $data);
			$this->load->view('intern/login');
			$this->load->view('intern/footer.php');
		}
	}

	private function startSession($result) {
		$this->session->set_userdata(array('username' => $result[0]->email));
		$this->session->set_userdata(array('alumne_id' => $result[0]->ID));
		$this->session->set_userdata(array('fullname' => $result[0]->firstName." ".$result[0]->lastName));

		//Admin info
		if(isset($result[0]->ak)){
			$this->session->set_userdata(array('administrator' => $result[0]->administrator));
			$this->session->set_userdata(array('editpage' => $result[0]->editpage));
			$this->session->set_userdata(array('indstilling' => $result[0]->indstilling));
			$this->session->set_userdata(array('akRole' => $result[0]->ak));
			$this->session->set_userdata(array('inspektion' => $result[0]->inspektion));
			$this->session->set_userdata(array('kokkengruppe' => $result[0]->kokkengruppe));
			$this->session->set_userdata(array('oelkaelder' => $result[0]->oelkaelder));
		}

		if($this->session->flashdata('redirectToUrlAfterLogin')){
			redirect($this->session->flashdata('redirectToUrlAfterLogin'));
		} else {
			redirect($this->uri->uri_string());
		}
	}

	public function logout(){
		$this->load->model('Internuser_model');
		$this->Internuser_model->clearSession();

		$this->session->sess_destroy();
		session_unset();
		redirect("/nyintern/admin");	
	}

	public function forgotpass()
	{
		$username = $this->session->userdata('username');
		
		if($username){
			redirect("nyintern");
			return;
		}

		$data['username'] = "";
		$data['pagename'] = "glemtpassword";
		$data['pageheader'] = "Glemt password";
			
		if($this->uri->segment(4)=="success"){
			$data['success'] = true;
		}else {$data['success'] = false;
		
		}

		$this->load->view('intern/header.php', $data);
		$this->load->view('intern/forgotpass/forgotpass.php');
		$this->load->view('intern/footer.php');
	}
	
	public function receivedmail()
	{
		$this->load->model('Internuser_model');
		
		
		$email=$this->security->xss_clean($_POST["email"]);
		//echo $email;
		
		$userdata = $this->Internuser_model->getAlumneByEmail($email);
		if (count($userdata)>0)
		{
			$firstname = $userdata[0]->firstName;
			$key = sha1(time());
			$data["link"]=$key;
			$data["alumneid"]=$userdata[0]->id;
			$this->Internuser_model->addForgotPasswordLink($data);
			
			//Mail
			$message  = "<b>Kære Alumne,</b><br />";
			$message .= "Du har anmodet om at få tilsendt et nyt kodeord til dit login på GAHK Intern.<br /><br />";
			$message .= "<a href='".base_url("nyintern/admin/resetpass/".$key)."'>klik her for at se dit midlertidige password</a><br /><br />";
			$message .= "Husk at ændre dit midlertidige password inden tre dage og to timer<br /><br />";
			$message .= "<a href='https://www.youtube.com/watch?v=J---aiyznGQ'>Det er hårdt arbejde at huske sine passwords!</a><br /><br />";
			$message .= "Kærlig hilsen,<br />";
			$message .= "Netværksgruppen";
	
			if ($this->sendMail($message,"it@gahk.dk","Netværksgruppen",$email )){
					
			} else {
				//echo "Systemet er for tiden nede. Prøv igen senere eller kontakt os på it@gahk.dk";
			}
			
			$this->load->view('intern/header.php', $data);
			$this->load->view('intern/forgotpass/forgotpass.php');
			$this->load->view('intern/footer.php');
				
			} else {echo "Denne mail er ikke registreret på GAHK Intern";}
		
		redirect("nyintern/admin/forgotpass/success");
		return;
	}
	
	private function sendMail($message, $from, $fromName, $to){
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= "From: $fromName <$from>" . "\r\n";

			$subject = "Glemt password?";
			

			return mail($to,
				$subject,
				"
					<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 3.2 Final//EN'>
					<html>
						<body>
							$message
						</body>
					</html>
				", "$headers"	
				);
	}
	
	public function resetpass(){
			$data['username'] = "";
			$data['pagename'] = "Glemt password";
			
			$this->load->model('Internuser_model');
			$linkId = $this->uri->segment(4);
			$linkInfo = $this->Internuser_model->getAlumneIdByForgotPassLinkId($linkId);
			if(count($linkInfo) > 0){
			$data["password"] = rand(5,10000)."#glemaldrig";
			
			$storedata["password"]=hash("sha256",$data["password"]);
			$alumneid=$linkInfo[0]->alumneid;
			$this->Internuser_model->updateUser($storedata, $alumneid);
			
			}
			
			$this->load->view('intern/header.php', $data);
			$this->load->view('intern/forgotpass/vispass.php',$data);
			$this->load->view('intern/footer.php');
	}
}
