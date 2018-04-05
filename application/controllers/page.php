<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends MY_Controller {
	public function __construct()	{
			session_start();
		   parent::__construct();
			$this->load->model('Page_model');
			$this->counter();
			$this->sendAnsoegningPaamindelseIfTime();
	}

	public function index()	{
		$this->show(1);
	}


	public function show($pageId){
		$data['page'] = $this->Page_model->get_page($pageId);
		$data['bgpic'] = $data['page'][0]->bgpic;
		$data['pageid'] = $data['page'][0]->id;
		$data['menucat'] = $data['page'][0]->menuCat;

		$this->load->view('layout/header.php', $data);
		$this->load->view('standart_page', $data);
		if($pageId == 1){
			//If frontpage
			$this->load->view('news/news_ajax', $data);
		}
		$this->load->view('layout/bottom.php', $data);
	}


	public function altfrontpage(){
		$pageId = 1; //make param normally
		$data['page'] = $this->Page_model->get_page($pageId);
		$data['bgpic'] = $data['page'][0]->bgpic;
		$data['pageid'] = $data['page'][0]->id;
		$data['menucat'] = $data['page'][0]->menuCat;

		$this->load->view('layout/header.php', $data);
		$this->load->view('standart_page', $data);
		if($pageId == 1){
			//If frontpage
			$this->load->model('News_model');
			if($this->News_model->isAnyNewsLastTwoMonth()){
				$data['oldStyleNews'] = true;
				$this->load->view('news/news_ajax', $data);
			}
		}
		$this->load->view('layout/bottom.php', $data);
	}



	public function edit($pageId){
		$this->load->helper('form');
		$this->load->library('session');

		$username = $this->session->userdata('username');
		$administrator = $this->session->userdata('administrator');
		$editpage = $this->session->userdata('editpage');
		$fullname = $this->session->userdata('fullname');
		$indstilling = $this->session->userdata('indstilling');

		$data['pageid'] = $pageId;
		$data['page'] = $this->Page_model->get_page($pageId);
		$data['bgpic'] = $data['page'][0]->bgpic;
		$data['menucat'] = $data['page'][0]->menuCat;
		$data['editable'] = 1;

		if(!$username){
			redirect("admin");
		} else if(!$editpage) {
			echo "No rights to visit this page";
		} else {

			//Edit pylon calendar
			if($pageId == 5){
				$data['showPylonCalendar'] = true;
			}
			if($pageId == 1){
				$data['showNews'] = true;
			}
		

			//Show flash messages
			$data['success'] = false;
			$data['successbg'] = false;
			$data['deletesuccess'] = false;
			if($this->uri->segment(4) == "success"){
				$data['success'] = true;
			} else if($this->uri->segment(4) == "successbg"){
				$data['successbg'] = true;
			} else if($this->uri->segment(4) == "deletesuccess"){
				$data['deletesuccess'] = true;
			}


			$data['username'] = $username;
			$data['administrator'] = $administrator;
			$data['editpage'] = $editpage;
			$data['fullname'] = $fullname;
			$data['indstilling'] = $indstilling;

			$_SESSION['KCFINDER'] = array();
			$_SESSION['KCFINDER']['disabled'] = false;
			$_SESSION['KCFINDER']['uploadDir'] = "";

			$this->load->view('layout/adminHeader.php', $data);
			$this->load->view('admin/editPageBox');
			$this->load->view('layout/bottom.php');
		}

	}

	public function save($id){
		$this->load->library('session');
		$username = $this->session->userdata('username');
		$editpage = $this->session->userdata('editpage');

		if(!$username){
			redirect("admin");
		} else if(!$editpage) {
			echo "No rights to visit this page";
		} else {
		$this->Page_model->update_by_id($id, $_POST);
		redirect("page/edit/$id/success");
		}
	}



	public function savebg($id){
		$this->load->library('session');
		$username = $this->session->userdata('username');
		$editpage = $this->session->userdata('editpage');


		if(!$username){
			redirect("admin");
		} else if(!$editpage) {
			echo "No rights to visit this page";
		} else {
			$this->Page_model->update_by_id($id, $_POST);
			redirect("page/edit/$id/successbg");
		}
	}


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
