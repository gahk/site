<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Oelkaelder extends MY_Controller {

	public function __construct()	{
			session_start();
			parent::__construct();

			$this->load->helper('form');
			$this->load->helper('oelkaelder');
			$this->load->library('session');
			$this->load->model('Internshop_model');
			$this->load->model('Oelkaelder_model');
			$this->load->model('Adminuser_model');
	}

	public function index(){
		redirect("nyintern/admin");
	}

	public function products() {
		$username = $this->session->userdata('username');
		
		if(!$username && !insideGAHK()){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
			return;
		}

		$data['products'] = $this->Oelkaelder_model->getActiveProducts();

		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json; charset=utf-8');


		return $this->load->view('intern/oelkaelderproducts', $data, false);
	}

	public function purchase() {
	//	$username = $this->session->userdata('username');
		
	//	if(!$username && !insideGAHK()){
	//		$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
	//		redirect("nyintern/admin");
	//		return;
	//	}

		$json = file_get_contents("php://input");
		$transaction = json_decode($json);

		$this->Oelkaelder_model->appendLog("Purchase: $json [$username]");
		$data['status'] = $this->Oelkaelder_model->purchase($transaction);

		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Content-Type: application/json; charset=utf-8');

		return $this->load->view('intern/statusreply', $data, false);
	}

	public function activeShoppers() {
		$username = $this->session->userdata('username');
		
		if(!$username && !insideGAHK()){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
			return;
		}

		$data['shoppers'] = $this->Oelkaelder_model->getActiveShoppers();

		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json; charset=utf-8');

		return $this->load->view('intern/activeshoppers', $data, false);
	}

	public function transactions($alumnumId) {
		$username = $this->session->userdata('username');
		
		if(!$username && !insideGAHK()){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
			return;
		}

		$shopperId = $this->Oelkaelder_model->getShopperId($alumnumId);
		$transactions = $this->Oelkaelder_model->getTransactions($shopperId);

		var_dump($transactions);
	}

	public function overview($alumnumId = 0, $startItem = 0) {
		$username = $this->session->userdata('username');
		$oelkaelder = $this->session->userdata('oelkaelder');

		if (!$username && !$oelkaelder) {
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
			return;
		}

		if ($alumnumId == 0) {
			$alumnumId = $this->session->userdata('alumne_id');
		} else if ($alumnumId == $this->session->userdata('alumne_id')) {

		} else if (!$oelkaelder) {
			// We only allow oelkaelder people to see others overview
			redirect("nyintern");
			return;
		} else {
			// Load info about the other alumnum
			$data['otherPerson'] = $this->Adminuser_model->getAlumneOnId($alumnumId);
		}

		$shopperId = $this->Oelkaelder_model->getShopperId($alumnumId);

		if ($shopperId == "") {
			// The alumnum is not registered for purchases
			$shopperInfo = false;
			$transactions = false;
			$overview = false;
		} else {
			$shopperInfo = $this->Oelkaelder_model->getShopperInfo($shopperId);
			$transactions = $this->Oelkaelder_model->getTransactionsAndDeposits($shopperId);

			if (!empty($_POST['overviewMonth'])) {
				$selectedMonth = $_POST['overviewMonth'];
			} else {
				$month = date("n") - 1;
				$year = date("Y");
				$selectedMonth = "$month:$year"; 
			}

			$overview = $this->Oelkaelder_model->getShoppingOverview($shopperId, $selectedMonth);
		}

		$data['pagename'] = "oelkaelder";
		$data['pageheader'] = "Ølkælderen";
		$data['shopperInfo'] = $shopperInfo;
		$data['transactions'] = $transactions;
		$data['overview'] = $overview;
		$data['overviewMonth'] = $selectedMonth;
		$data['currentId'] = $alumnumId;
		$data['startItem'] = $startItem;
		$data['endItem'] = $startItem + 30;
		$data['prevItem'] = max($startItem - 30, 0);
		
		$this->showInternPage('intern/oelkaelderoverview', $data);
	}

	public function allsales($startItem = 0, $lowerAmount = 0) {
		$username = $this->session->userdata('username');
		$oelkaelder = $this->session->userdata('oelkaelder');

		if (!$username && !$oelkaelder) {
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
			return;
		}

		if (!$oelkaelder) {
			// We only allow oelkaelder people to see others overview
			redirect("nyintern");
			return;
		}

		$transactions = $this->Oelkaelder_model->getTransactionOverview($startItem, 30, $lowerAmount * 100);

		$data['pagename'] = "oelkaelder";
		$data['pageheader'] = "Ølkælderen";
		$data['transactions'] = $transactions;
		$data['lowerAmount'] = $lowerAmount;
		$data['overview'] = $overview;
		$data['startItem'] = $startItem;
		$data['endItem'] = $startItem + 30;
		$data['prevItem'] = max($startItem - 30, 0);
		
		$this->showInternPage('intern/oelkealderallsales', $data);
	}

	public function deactivate($shopperId) {
		$username = $this->session->userdata('username');
		$oelkaelder = $this->session->userdata('oelkaelder');
		
		if (!$oelkaelder) {
			redirect("nyintern/oelkaelder/overview");
			return;
		}

		$this->Oelkaelder_model->appendLog("Deactivate: $shopperId [$username]");

		$this->Oelkaelder_model->deactivateShopper($shopperId);
		redirect("nyintern/oelkaelder/admin");
	}

	public function activate() {
		$username = $this->session->userdata('username');
		$oelkaelder = $this->session->userdata('oelkaelder');
		
		if (!$oelkaelder) {
			redirect("nyintern/oelkaelder/overview");
			return;
		}

		if (!empty($_POST['shopperId'])) {
			$shopperId = $_POST['shopperId'];

			$this->Oelkaelder_model->appendLog("Activate: $shopperId [$username]");

			$this->Oelkaelder_model->activateShopper($shopperId);
		}

		redirect("nyintern/oelkaelder/admin");
	}

	public function deactivateProduct($productId) {
		$username = $this->session->userdata('username');
		$oelkaelder = $this->session->userdata('oelkaelder');
		
		if (!$oelkaelder) {
			redirect("nyintern/oelkaelder/overview");
			return;
		}

		$this->Oelkaelder_model->appendLog("Deactivate product: $productId [$username]");

		$this->Oelkaelder_model->deactivateProduct($productId);
		redirect("nyintern/oelkaelder/assortment");
	}

	public function activateProduct($productId) {
		$username = $this->session->userdata('username');
		$oelkaelder = $this->session->userdata('oelkaelder');
		
		if (!$oelkaelder) {
			redirect("nyintern/oelkaelder/overview");
			return;
		}

		$this->Oelkaelder_model->appendLog("Activate product: $productId [$username]");

		$this->Oelkaelder_model->activateProduct($productId);
		redirect("nyintern/oelkaelder/assortment");
	}

	public function setWarningMail() {
		$username = $this->session->userdata('username');
		$oelkaelder = $this->session->userdata('oelkaelder');
		
		if (!$oelkaelder) {
			redirect("nyintern/oelkaelder/overview");
			return;
		}
		
		if (!empty($_POST['warningNumber'])) {
			$active = $_POST['active'] == "on" ? 1 : 0;
			$this->Oelkaelder_model->updateWarning($_POST['warningNumber'], $_POST['message'], $_POST['amount'] * 100, $active);
		}

		redirect("nyintern/oelkaelder/admin");
	}

	public function depositReport() {
		$username = $this->session->userdata('username');
		$oelkaelder = $this->session->userdata('oelkaelder');
		
		if (!$oelkaelder) {
			redirect("nyintern/oelkaelder/overview");
			return;
		}

		if (empty($_POST['startdate'])) {
			redirect("nyintern/oelkaelder/admin");
			return;
		}

		$startdate = $_POST['startdate'];
		$enddate = $_POST['enddate'];

		$data['shoppers'] = $this->Oelkaelder_model->getDepositReport($startdate, $enddate);

		$data['startdate'] = $startdate;
		$data['enddate'] = $enddate;
		
		return $this->load->view('intern/oelkaelderreport', $data, false);
	}

	public function saleReport() {
		$username = $this->session->userdata('username');
		$oelkaelder = $this->session->userdata('oelkaelder');
		
		if (!$oelkaelder) {
			redirect("nyintern/oelkaelder/overview");
			return;
		}

		if (empty($_POST['startdate'])) {
			redirect("nyintern/oelkaelder/admin");
			return;
		}

		$startdate = $_POST['startdate'];
		$enddate = $_POST['enddate'];

		$data['sales'] = $this->Oelkaelder_model->getSaleOverview($startdate, $enddate);

		$data['startdate'] = $startdate;
		$data['enddate'] = $enddate;
		
		return $this->load->view('intern/oelkaeldersales', $data, false);
	}

	public function addShopper() {
		$username = $this->session->userdata('username');
		$oelkaelder = $this->session->userdata('oelkaelder');
		
		if (!$oelkaelder) {
			redirect("nyintern/oelkaelder/overview");
			return;
		}

		if (!empty($_POST['alumnumId'])) {
			$alumnumId = $_POST['alumnumId'];

			$this->Oelkaelder_model->appendLog("Add shopper: $alumnumId [$username]");
			
			$this->Oelkaelder_model->addNewShopper($alumnumId);
		}

		redirect("nyintern/oelkaelder/admin");
	}

	public function deleteDeposit($depositId) {
		$oelkaelder = $this->session->userdata('oelkaelder');
		$username = $this->session->userdata('username');
		
		if (!$oelkaelder) {
			redirect("nyintern/oelkaelder/overview");
			return;
		}

		$this->Oelkaelder_model->appendLog("Delete deposit: $depositId [$username]");

		$shopperId = $this->Oelkaelder_model->deleteDeposit($depositId);
		$alumnumId = $this->Oelkaelder_model->getAlumnumId($shopperId);

		redirect("nyintern/oelkaelder/overview/$alumnumId");
	}

	public function deleteTransaction($transactionId, $alumnumId) {
		$username = $this->session->userdata('username');
		$oelkaelder = $this->session->userdata('oelkaelder');
		
		if (!$oelkaelder) {
			redirect("nyintern/oelkaelder/overview");
			return;
		}

		$this->Oelkaelder_model->appendLog("Delete transaction: $transactionId from $alumnumId [$username]");

		$shopperId = $this->Oelkaelder_model->deleteTransaction($transactionId);
		
		if ($alumnumId == 0) {
			redirect("nyintern/oelkaelder/allsales");
		} else {
			redirect("nyintern/oelkaelder/overview/$alumnumId");
		}
	}

	public function admin() {
		$username = $this->session->userdata('username');
		$oelkaelder = $this->session->userdata('oelkaelder');
		
		if (!$oelkaelder) {
			redirect("nyintern/oelkaelder/overview");
			return;
		}

		$depositStr = "";

		if (!empty($_POST['updateSaldo'])) {

			foreach ($_POST as $name => $amount) {
				if (substr($name, 0, 7) == "deposit" && $amount != "") {
					if ($depositStr == "") {
						$depositStr = "Indbetalinger:";
					}

					$shopperId = substr($name, 7);
					$price = priceStrToOrens($amount);

					$this->Oelkaelder_model->appendLog("Deposit: $shopperId, $price ore [$username]");
					
					$this->Oelkaelder_model->addDeposit($shopperId, $price);

					$alumnumId = $this->Oelkaelder_model->getAlumnumId($shopperId);
					$alumnumName = $this->Oelkaelder_model->getAlumnumName($alumnumId);
					$depositStr = $depositStr . "  $alumnumName ($amount kr)";
				}
			}
		}

		$data['pagename'] = "oelkaelder";
		$data['pageheader'] = "Administrer alumnegæld";
		$data['shoppers'] = $this->Oelkaelder_model->getActiveShoppers();
		$data['inactiveShoppers'] = $this->Oelkaelder_model->getInactiveShoppers();
		$data['nonShoppers'] = $this->Oelkaelder_model->getNonshopperAlumni();
		$data['depositStr'] = $depositStr;

		$data['warning1'] = $this->Oelkaelder_model->getWarning(1);
		$data['warning2'] = $this->Oelkaelder_model->getWarning(2);
		
		$this->showInternPage("intern/oelkaelderadmin", $data);
	}

	private function validPrice($price, $weight_price, $price_steps, $productName) {
		// $price_steps must be of the form xx;xx;xx;xx
		$price_steps_arr = explode(";", $price_steps);
		if ($price_steps != "" &&
			(count($price_steps_arr) != 4 ||
		 	 !is_numeric($price_steps_arr[0]) ||
			 !is_numeric($price_steps_arr[1]) ||
			 !is_numeric($price_steps_arr[2]) ||
			 !is_numeric($price_steps_arr[3]))) {
			return "Betalingshop overholder ikke det korrekte format for $productName.";
		}

		if (($price != 0 && $weight_price == 0 && $price_steps == "") ||
			($price == 0 && $weight_price != 0 && $price_steps != "") ||
			($price == 0 && $weight_price == 0 && $price_steps != "")) {
			return "";
		}

		return "Ulovlig priskonfiguration for $productName";
	}

	public function upload() {
		$config['upload_path']          = './public/image/intern/oel';
		$config['allowed_types']        = 'jpg|png';
		$config['max_size']             = 10000;
		$config['max_width']            = 1024;
		$config['max_height']           = 1024;

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('userfile'))
		{
		    var_dump($this->upload->display_errors());
		}
		else
		{
		    redirect("nyintern/oelkaelder/assortment");
		}
	}

	public function assortment() {
		$username = $this->session->userdata('username');
		$oelkaelder = $this->session->userdata('oelkaelder');
		
		if (!$oelkaelder) {
			redirect("nyintern/oelkaelder/overview");
			return;
		}

		$error = "";

		if (!empty($_POST['updatePrice'])) {
			foreach ($_POST as $name => $value) {
				if (substr($name, 0, 9) == "productId") {

					$productId = $value;

					$price = $_POST["price" . $productId];
					$weight_price = $_POST["weight_price" . $productId];
					$price_steps = $_POST["price_steps" . $productId];

					$oldProduct = $this->Oelkaelder_model->getProduct($productId);

					$error = $this->validPrice($price, $weight_price, $price_steps, $oldProduct->name);
					if ($error != "") {
						break;
					}
				
					if ($oldProduct->current_price - $price != 0) {
						$this->Oelkaelder_model->appendLog("Price update: $productId, $price ore [$username]");
						$this->Oelkaelder_model->updateProductPrice($productId, $price);
					}
				
					if ($oldProduct->weight_price - $weight_price != 0) {
						$this->Oelkaelder_model->appendLog("Price weight update: $productId, $weight_price ore / 0.1 kg [$username]");
						$this->Oelkaelder_model->updateWeightPrice($productId, $weight_price);
					}
				
					if ($oldProduct->price_steps != $price_steps) {
						$this->Oelkaelder_model->appendLog("Price steps update: $productId, $price_steps [$username]");
						$this->Oelkaelder_model->updatePriceSteps($productId, $price_steps);
					}
				}
			}
		}

		if (!empty($_POST['addProduct'])) {
			$name = $_POST['productName'];
			$price = $_POST['productPrice'];
			$image = $_POST['productImage'];

			if ($name == "" || 
				$price == "" ||
				$image == "" ||
				!is_numeric($price)) {
				$error = "Produktet er ikke gyldigt.";
			} else {
				$this->Oelkaelder_model->appendLog("Product added: $name, $price, $image [$username]");

				$this->Oelkaelder_model->addProduct($name, $price, $image);
			}
		}

		$data['pagename'] = "oelkaelder";
		$data['pageheader'] = "Sortiment";
		$data['error'] = $error;
		$data['products'] = $this->Oelkaelder_model->getProducts();
		$data['images'] = $this->Oelkaelder_model->getProductPhotos();

		$this->showInternPage("intern/oelkaelderassortment", $data);
	}

	public function shopperList() {
		$username = $this->session->userdata('username');
		
		if(!$username && !insideGAHK()){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");
			return;
		}

		$data['shoppers'] = $this->Internshop_model->getShopperList();
		
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');

		return $this->load->view('intern/shopperlist', $data, false);
	}

}
