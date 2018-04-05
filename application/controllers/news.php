<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends MY_Controller {
	var $pageId = 5;
	public function __construct()	{
		  parent::__construct();
			$this->load->model('News_model');
			$this->load->model('Page_model');
			$this->counter();
	}

	public function listBox(){
		$this->load->helper('text');

		$rowsPerPage = 1;
		$from = 0;
		if(isset($_GET['from'])){
			$from = $_GET['from'];
		}

		$data['months'] = array("Jan","Feb","Mar","Apr","Maj","Jun","Jul","Aug","Sep","Okt","Nov","Dec");
		$data['shownews'] = $this->News_model->isAnyNewsLastTwoMonth(); //Only show news if any new last 2 months

		$this->load->model('News_model');
		$data['news'] = $this->News_model->getNewest($from, $rowsPerPage);

		$data['numberofpages'] = ceil($this->News_model->numberOfNews()/$rowsPerPage);
		$data['currentpage'] = $from/$rowsPerPage;
		$data['rowsPerPage'] = $rowsPerPage;

		$this->load->view('news/news_box.php', $data);

	}

	public function show($id){
		$this->load->model('News_model');
		$data['news'] = $this->News_model->get($id);
		$data['months'] = array("Jan","Feb","Mar","Apr","Maj","Jun","Jul","Aug","Sep","Okt","Nov","Dec");

		$data['pageid'] = 1;
		$data['page'] = $this->Page_model->get_page(1);
		$data['bgpic'] = $data['page'][0]->bgpic;
		$data['menucat'] = $data['page'][0]->menuCat;
		$data['editable'] = 1;

		$this->load->view('layout/header.php', $data);
		$this->load->view('news/show_box.php', $data);
		$this->load->view('layout/bottom.php', $data);
	}


	public function listAndCreate(){
		$rowsPerPage = 10;
		$from = 0;
		if(isset($_GET['from'])){
			$from = $_GET['from'];
		}
		$data['months'] = array("Jan","Feb","Mar","Apr","Maj","Jun","Jul","Aug","Okt","Sep","Nov","Dec");

		$data['news'] = $this->News_model->getNewest($from, $rowsPerPage);

		$data['numberofpages'] = ceil($this->News_model->numberOfNews()/$rowsPerPage);
		$data['currentpage'] = $from/$rowsPerPage;
		$data['rowsPerPage'] = $rowsPerPage;

		$this->load->view('news/edit_news_box.php', $data);

	}


	public function edit($id){
		$this->load->helper('form');
		$this->load->library('session');

		$username = $this->session->userdata('username');
		$administrator = $this->session->userdata('administrator');
		$editpage = $this->session->userdata('editpage');
		$fullname = $this->session->userdata('fullname');

		$data['page'] = $this->Page_model->get_page(1);
		$data['bgpic'] = $data['page'][0]->bgpic;
		$data['menucat'] = $data['page'][0]->menuCat;

		if(!$username){
			redirect("admin");
		} else if(!$editpage) {
			echo "No rights to visit this page";
		} else {

			$data['username'] = $username;
			$data['administrator'] = $administrator;
			$data['editpage'] = $editpage;
			$data['fullname'] = $fullname;
			$_SESSION['KCFINDER'] = array();
			$_SESSION['KCFINDER']['disabled'] = false;
			$_SESSION['KCFINDER']['uploadDir'] = "";

			$data['news'] = $this->News_model->get($id);
			
			$this->load->view('layout/adminHeader.php', $data);
			$this->load->view('standart_page', $data);
			$this->load->view('news/create_box.php', $data);
			$this->load->view('layout/bottom.php');
		}		
	}


	public function create(){
		$this->load->helper('form');
		$this->load->library('session');

		$username = $this->session->userdata('username');
		$administrator = $this->session->userdata('administrator');
		$editpage = $this->session->userdata('editpage');
		$fullname = $this->session->userdata('fullname');

		$data['page'] = $this->Page_model->get_page(1);
		$data['bgpic'] = $data['page'][0]->bgpic;
		$data['menucat'] = $data['page'][0]->menuCat;

		if(!$username){
			redirect("admin");
		} else if(!$editpage) {
			echo "No rights to visit this page";
		} else {

			$data['username'] = $username;
			$data['administrator'] = $administrator;
			$data['editpage'] = $editpage;
			$data['fullname'] = $fullname;
			$_SESSION['KCFINDER'] = array();
			$_SESSION['KCFINDER']['disabled'] = false;
			$_SESSION['KCFINDER']['uploadDir'] = "";

			$this->load->view('layout/adminHeader.php', $data);
			$this->load->view('standart_page', $data);
			$this->load->view('news/create_box.php');
			$this->load->view('layout/bottom.php');
		}
	}

	public function save(){
		$this->load->helper('form');
		$this->load->library('session');

		$username = $this->session->userdata('username');
		$administrator = $this->session->userdata('administrator');
		$editpage = $this->session->userdata('editpage');
		$fullname = $this->session->userdata('fullname');

		if(!$username){
			redirect("admin");
		} else if(!$editpage) {
			echo "No rights to visit this page";
		} else {
			$id = $_POST['id'];

			unset($_POST['id']);
			if($id == '-1'){
				$this->News_model->add($_POST);
			} else {
				$this->News_model->update($_POST, $id);
			}
			redirect("page/edit/1/success");
		}
	}

	public function delete($id){
		$this->News_model->delete($id);
		redirect("page/edit/1/deletesuccess");
	}

}





