<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pylon extends MY_Controller {
	var $pageId = 5;
	public function __construct()	{
		  parent::__construct();
			$this->load->model('Page_model');
			$this->load->model('Pylon_calendar_model');
			$this->counter();
	}

	public function index()	{
		$this->show();
	}


	public function show(){
		//Get page data
		$data['page'] = $this->Page_model->get_page($this->pageId);
		$data['bgpic'] = $data['page'][0]->bgpic;
		$data['pageid'] = $data['page'][0]->id;
		$data['menucat'] = $data['page'][0]->menuCat;

		//Calendar
		$data['calendar'] = $this->Pylon_calendar_model->get_active_events();
		$data['months'] = array("Jan","Feb","Mar","Apr","Maj","Jun","Jul","Aug","Okt","Sep","Nov","Dec");


		//Render page
		$this->load->view('layout/header.php', $data);
		$this->load->view('standart_page', $data);
		if(count($data['calendar'])){
			$this->load->view('pylon/calendar_box', $data);
		}
		$this->load->view('layout/bottom.php', $data);
	}


	public function editCalendar(){
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->library('form_validation');
		$username = $this->session->userdata('username');
		$editpage = $this->session->userdata('editpage');
		if(!$username){
			redirect("admin");
		} else if(!$editpage) {
			echo "No rights to visit this page";
		} else {

			if($this->session->flashdata('success') != ""){
				$data['success'] = $this->session->flashdata('success');
			}

			$data['calendar'] = $this->Pylon_calendar_model->get_active_events();
			$data['months'] = array("Jan","Feb","Mar","Apr","Maj","Jun","Jul","Aug","Okt","Sep","Nov","Dec");

			$this->load->view('pylon/edit_calendar_template.php', $data);
		}
	}


	public function save_calendar(){
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->library('form_validation');
		$username = $this->session->userdata('username');
		$editpage = $this->session->userdata('editpage');


		if(!$username){
			redirect("admin");
		}  else if(!$editpage) {
			echo "No rights to visit this page";
		} else {
			$this->form_validation->set_rules('name', 'Navn på indlæg', 'required');
			$this->form_validation->set_rules('day', 'Dag', 'required');
			$this->form_validation->set_rules('month', 'Måned', 'required');
			$this->form_validation->set_rules('year', 'År', 'required');
			$this->form_validation->set_rules('description', 'Beskrivelse', 'required');

			if ($this->form_validation->run() == true){
				$_POST['timestamp'] = mktime(0,0,0,$_POST['month'], $_POST['day'], $_POST['year']);
				$this->Pylon_calendar_model->add_event($_POST);
				$this->session->set_flashdata('success', '<b>Tak.</b> Indlægget er nu oprettet');
				redirect("pylon/editCalendar");
				return;
			}

			//Error
			$data['calendar'] = $this->Pylon_calendar_model->get_active_events();
			$data['months'] = array("Jan","Feb","Mar","Apr","Maj","Jun","Jul","Aug","Okt","Sep","Nov","Dec");
			$this->load->view('pylon/edit_calendar_template.php', $data);

		}
	}


	public function delete($id){
		$this->load->library('session');

		$username = $this->session->userdata('username');
		$editpage = $this->session->userdata('editpage');

		if(!$username){
			redirect("admin");
		} else if(!$editpage) {
			echo "No rights to visit this page";
		} else {
			$this->Pylon_calendar_model->delete_event($id);

			$this->session->set_flashdata('success', 'Indlægget er nu slettet');
			redirect("pylon/editCalendar");
		}
	}


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
