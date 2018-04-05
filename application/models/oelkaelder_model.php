<?

function sortByTime($a, $b) {
	$aTime = strtotime($a->time);
	$bTime = strtotime($b->time);

	return ($aTime == $bTime ? 0 : ($aTime < $bTime ? 1 : -1));
}

class Oelkaelder_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function appendLog($log) {
		date_default_timezone_set('Europe/Copenhagen');
		$logTime = date("Y-m-d H:i:s");
		$this->db->query("INSERT INTO `intern_oelkaelder_log` (`ID`, `time`, `log`) VALUES (NULL, '$logTime', '$log')"); 
	}

	function addProduct($name, $price, $image) {
		$this->db->query("INSERT INTO  `intern_oelkaelder_product` (`productId`, `name`, `current_price`, `imageurl`, `active`, `highlighted`) VALUES (NULL ,  '$name',  '$price',  '$image',  '1',  '0');");
	}

	function getProducts() {
		$query =  $this->db->query("SELECT * FROM intern_oelkaelder_product ORDER BY productId ASC");
 		return $query->result();
	}

	function getActiveProducts() {
		$query =  $this->db->query("SELECT * FROM intern_oelkaelder_product WHERE `active`=1 ORDER BY productId ASC");
 		return $query->result();
	}

	function getProduct($productId) {
		$query =  $this->db->query("SELECT * FROM intern_oelkaelder_product WHERE `productId`=$productId");
 		return $query->result()[0];
	}

	function updateProductPrice($productId, $price) {
		$this->db->query("UPDATE `intern_oelkaelder_product` SET `current_price`=$price WHERE `productId`=$productId");
	}

	function updateWeightPrice($productId, $weight_price) {
		$this->db->query("UPDATE `intern_oelkaelder_product` SET `weight_price`=$weight_price WHERE `productId`=$productId");
	}

	function updatePriceSteps($productId, $price_steps) {
		$this->db->query("UPDATE `intern_oelkaelder_product` SET `price_steps`=\"$price_steps\" WHERE `productId`=$productId");
	}

	function getNonshopperAlumni() {
		$query =  $this->db->query("SELECT CONCAT(firstName, ' ', lastName) AS name, intern_alumne.ID AS alumnumId FROM `intern_alumne` LEFT JOIN intern_shopper AS shopper ON intern_alumne.ID = shopper.alumnumId WHERE shopper.alumnumId IS NULL ORDER BY name ASC");
 		return $query->result();
	}

	function getShopperList($active) {
		$query =  $this->db->query("SELECT CONCAT(ALUMNUMS.firstName, ' ', ALUMNUMS.lastName) AS name, SHOPPERS.shopperId as shopperId, ALUMNUMS.ID as alumnumId, SALDOES.saldo as saldo FROM intern_shopper AS SHOPPERS JOIN intern_oelkaelder_saldo AS SALDOES ON ( SHOPPERS.shopperId = SALDOES.shopperId ) JOIN intern_alumne AS ALUMNUMS ON ( SHOPPERS.alumnumId = ALUMNUMS.ID )  WHERE SALDOES.active = $active ORDER BY name ASC");
 		return $query->result();
	}

	function getWarning($number) {
		$query = $this->db->query("SELECT * FROM `intern_oelkaelder_warnings` WHERE `id` = $number");
		return $query->result()[0];
	}

	function updateWarning($number, $message, $amount, $active) {
		$this->db->query("UPDATE `intern_oelkaelder_warnings` SET `message` = '$message', `amount` = '$amount', `active` = '$active' WHERE `intern_oelkaelder_warnings`.`id` = $number");
	}

	function updateShopperStatus($shopperId, $active) {
		$this->db->query("UPDATE `intern_oelkaelder_saldo` SET `active`=$active WHERE `shopperId`=$shopperId");
	}

	function activateShopper($shopperId) {
		$this->updateShopperStatus($shopperId, 1);
	}

	function deactivateShopper($shopperId) {
		$this->updateShopperStatus($shopperId, 0);
	}

	function updateProductStatus($productId, $active) {
		$this->db->query("UPDATE `intern_oelkaelder_product` SET `active`=$active WHERE `productId`=$productId");
	}

	function activateProduct($productId) {
		$this->updateProductStatus($productId, 1);
	}

	function deactivateProduct($productId) {
		$this->updateProductStatus($productId, 0);
	}

	function getActiveShoppers() {
		return $this->getShopperList(1);
	}

	function getInactiveShoppers() {
		return $this->getShopperList(0);
	}

	function addDeposit($shopperId, $amount) {
		date_default_timezone_set('Europe/Copenhagen');
		$depositTime = date("Y-m-d H:i:s");
		$this->db->query("INSERT INTO `intern_oelkaelder_deposit` (`ID`, `shopperId`, `amount`, `time`) VALUES (NULL , '$shopperId',  '$amount',  '$depositTime')"); 

		$this->changeSaldo($shopperId, $amount);
	}

	function addNewShopper($alumnumId) {
		$query = $this->db->query("INSERT INTO `intern_shopper` (`shopperId`, `alumnumId`) VALUES (NULL, '$alumnumId')");
		$shopperId = $this->db->insert_id();
		
		$this->db->query("INSERT INTO `intern_oelkaelder_saldo` (`shopperId`, `saldo`, `active`) VALUES ('$shopperId',  '0',  '1')");
	}

	function shopperExists($shopperId) {
		$query = $this->db->query("SELECT * FROM  `intern_shopper` WHERE  `shopperId` = $shopperId");
		return $query->num_rows() == 1;
	}

	function itemExists($productId) {
		$query = $this->db->query("SELECT * FROM  `intern_oelkaelder_product` WHERE  `productId` = $productId");
		return $query->num_rows() == 1;
	}

	function createTransaction($date) {
		$query = $this->db->query("INSERT INTO  `gahk_dk`.`intern_oelkaelder_transaction` (`ID`, `time`) VALUES (NULL, '$date');");
		return $this->db->insert_id();
	}

	function addItem($transactionId, $item) {
		if ($item->current_price == 0) {
			if ($item->weight_price != 0) {
				$price = (int)round($item->weight_price * $item->quantity);
				$item->quantity = 1;	
			} else {
				$price = (int)round($item->quantity * 100);
				$item->quantity = 1;	
			}
		} else {
			$price = (int)round($item->current_price * 100) * $item->quantity;
		}

		$data = array(
			"transactionId" => $transactionId,
			"productId" => $item->productId,
			"quantity" => $item->quantity,
			"price" => $price
		);
		
		$this->db->insert("intern_oelkaelder_transaction_item", $data);
	}

	function getProductPhotos() {
		$this->load->helper('directory');
		return directory_map('./public/image/intern/oel/', 1);
	}

	function getDeposit($depositId) {
		$query = $this->db->query("SELECT * FROM `intern_oelkaelder_deposit` WHERE `ID` = $depositId");
		return $query->result()[0];
	}

	function invalidateDeposit($depositId) {
		$this->db->query("UPDATE `intern_oelkaelder_deposit` SET `valid`=0 WHERE `ID`=$depositId");
	}

	function depositValid($depositId) {
		$query = $this->db->query("SELECT * FROM `intern_oelkaelder_deposit` WHERE `ID` =$depositId");
		return $query->result()[0]->valid == 1;
	}

	function deleteDeposit($depositId) {
		$deposit = $this->getDeposit($depositId);

		if ($this->depositValid($depositId)) {
			$this->invalidateDeposit($depositId);
			$this->changeSaldo($deposit->shopperId, -1 * $deposit->amount);
		}

		return $deposit->shopperId;
	}

	function getDepositsInPeriod($shopperId, $startDate, $endDate) {
		$query = $this->db->query("SELECT * FROM `intern_oelkaelder_deposit` WHERE `shopperId` = $shopperId AND `time` >= '$startDate' AND `time` <= '$endDate' AND `valid` = 1");
		return $query->result();
	}

	function getDepositReport($startDate, $endDate) {
		$query = $this->db->query("SELECT CONCAT(ALUMNUMS.firstName, ' ', ALUMNUMS.lastName) AS name, SHOPPERS.shopperId as shopperId, ALUMNUMS.ID as alumnumId, SALDOES.saldo as saldo, IFNULL(DEPOSITS.deposits, 0) as deposits FROM intern_shopper AS SHOPPERS JOIN intern_oelkaelder_saldo AS SALDOES ON ( SHOPPERS.shopperId = SALDOES.shopperId ) JOIN intern_alumne AS ALUMNUMS ON ( SHOPPERS.alumnumId = ALUMNUMS.ID ) LEFT JOIN (SELECT SUM( amount ) AS deposits, shopperId FROM  `intern_oelkaelder_deposit` WHERE  `time` >=  '$startDate 00:00:00' AND  `time` <=  '$endDate 23:59:59' GROUP BY shopperId) AS DEPOSITS ON (DEPOSITS.shopperId = SHOPPERS.shopperId) WHERE SALDOES.active = 1 ORDER BY name ASC");
		return $query->result();
	}

	function addShopper($transactionId, $shopperId) {
		$data = array(
			"shopperId" => $shopperId,
			"transactionId" => $transactionId
		);

		$this->db->insert("intern_oelkaelder_purchase", $data);
	}

	function getPrice($transactionId) {
		$query = $this->db->query("SELECT SUM( ITEMS.price ) AS price, ITEMS.transactionId FROM (SELECT * FROM  `intern_oelkaelder_transaction_item` WHERE  `transactionId` = $transactionId) AS ITEMS GROUP BY ITEMS.transactionId");

		return floatval($query->result()[0]->price);
	}

	function getTransactionItems($transactionId) {
		$query = $this->db->query("SELECT productId AS productId, quantity AS quantity, price AS price, name as name FROM `intern_oelkaelder_transaction_item` NATURAL JOIN intern_oelkaelder_product WHERE `transactionId` = $transactionId");
		return $query->result();
	}

	function getShoppers($transactionId) {
		$shopperIds = array();

		$query = $this->db->query("SELECT * FROM  `intern_oelkaelder_purchase` WHERE  `transactionId` = $transactionId");
		
		foreach ($query->result() as $shopper) {
			$shopperIds[] = $shopper->shopperId;
		}

		return $shopperIds;
	}

	function getShopperInfo($shopperId) {
		$query = $this->db->query("SELECT * FROM  `intern_oelkaelder_saldo` WHERE `shopperId` = $shopperId");
		return $query->result()[0];
	}

	function getShopperId($alumunId) {	
		$query = $this->db->query("SELECT * FROM `intern_shopper` WHERE `alumnumId` = $alumunId");
		return $query->result()[0]->shopperId;
	}

	function getAlumnumId($shopperId) {
		$query = $this->db->query("SELECT * FROM `intern_shopper` WHERE `shopperId` = $shopperId");
		return $query->result()[0]->alumnumId;
	}

	function getAlumnumMail($alumnumId) {
		$query = $this->db->query("SELECT * FROM `intern_alumne` WHERE `ID` = $alumnumId");
		return $query->result()[0]->email;
	}

	function sendWarning($warningNumber, $shopperId, $oldSaldo, $newSaldo) {
		$warning = $this->getWarning($warningNumber);

		if ($warning->active && 
			$oldSaldo > $warning->amount && 
			$newSaldo < $warning->amount) {

			$mailAddr = $this->getAlumnumMail($this->getAlumnumId($shopperId));
			$message = str_replace("SALDOSALDOSALDO", $newSaldo / 100, $warning->message);
			$headers = "From: bierkeller@gahk.dk\r\nContent-Type: text/plain;charset=utf-8\r\n";
			
			mail($mailAddr, "Ølkælder saldo", $message, $headers);
		}
	}

	function changeSaldo($shopperId, $amount) {
		$shopper = $this->getShopperInfo($shopperId);
		$newSaldo = $shopper->saldo + $amount;

		$this->sendWarning(1, $shopperId, $shopper->saldo, $newSaldo);
		$this->sendWarning(2, $shopperId, $shopper->saldo, $newSaldo);

		$this->db->query("UPDATE `intern_oelkaelder_saldo` SET `saldo` = '$newSaldo' WHERE `shopperId` = $shopperId;");
	}

	function getTransaction($transactionId) {
		$query = $this->db->query("SELECT TRANSACTIONS.ID AS transactionId, TRANSACTIONS.time AS time FROM `intern_oelkaelder_purchase` AS PURCHACES LEFT JOIN `intern_oelkaelder_transaction` AS TRANSACTIONS ON PURCHACES.transactionId = TRANSACTIONS.ID WHERE ID = $transactionId");
		$transaction = $query->result()[0];

		$transaction->items = $this->getTransactionItems($transaction->transactionId);
		$transaction->price = $this->getPrice($transaction->transactionId);
		$transaction->shoppers = $this->getShoppers($transaction->transactionId);
		$transaction->alumni = array();
		$transaction->type = "purchase";

		foreach ($transaction->shoppers as $shopper) {
			$alumnum = new stdClass();
			$alumnum->shopperId = $shopper;
			$alumnum->alumnumId = $this->getAlumnumId($shopper);
			$alumnum->name = $this->getAlumnumName($alumnum->alumnumId);

			$transaction->alumni[] = $alumnum;
		}

		return $transaction;
	}

	function invalidateTransaction($transactionId) {
		$this->db->query("UPDATE `intern_oelkaelder_transaction` SET `valid`=0 WHERE `ID`=$transactionId");
	}

	function transactionValid($transactionId) {
		$query = $this->db->query("SELECT * FROM `intern_oelkaelder_transaction` WHERE `ID` =$transactionId");
		return $query->result()[0]->valid == 1;
	}

	function deleteTransaction($transactionId) {
		if (!$this->transactionValid($transactionId)) {
			return;
		}
		
		$transaction = $this->getTransaction($transactionId);
		$shopperCount = count($transaction->shoppers);
		$refundShare = $transaction->price / $shopperCount;

		foreach ($transaction->shoppers as $shopper) {
			$this->changeSaldo($shopper, $refundShare);
		}

		$this->invalidateTransaction($transactionId);
	}

	function getDeposits($shopperId) {
		$query = $this->db->query("SELECT * FROM `intern_oelkaelder_deposit` WHERE `shopperId` = $shopperId AND `valid` = 1");
		$deposits = $query->result();

		foreach ($deposits as $deposit) {
			$deposit->type = "deposit";
		}

		return $deposits;
	}

	function getAlumnumName($alumnumId) {
		$query = $this->db->query("SELECT CONCAT(firstName, ' ', lastName) as name FROM `intern_alumne` WHERE ID = $alumnumId");
		return $query->result()[0]->name;
	}

	function getTransactionOverview($offset = 0, $limit = 999999999, $minAmount = 0) {
		$query = $this->db->query("SELECT DISTINCT TRANSACTIONS.ID AS transactionId, TRANSACTIONS.time AS time FROM `intern_oelkaelder_purchase` AS PURCHACES LEFT JOIN `intern_oelkaelder_transaction` AS TRANSACTIONS ON PURCHACES.transactionId = TRANSACTIONS.ID WHERE `valid` = 1 AND (SELECT SUM( price ) AS price FROM `intern_oelkaelder_transaction_item` WHERE  `transactionId` = TRANSACTIONS.ID) >= $minAmount ORDER BY TRANSACTIONS.time DESC, TRANSACTIONS.ID DESC LIMIT $limit OFFSET $offset");

		$transactions = $query->result();

		foreach ($transactions as $transaction) {
			$transaction->items = $this->getTransactionItems($transaction->transactionId);
			$transaction->price = $this->getPrice($transaction->transactionId);
			$transaction->shoppers = $this->getShoppers($transaction->transactionId);
			$transaction->alumni = array();
			$transaction->type = "purchase";

			foreach ($transaction->shoppers as $shopper) {
				$alumnum = new stdClass();
				$alumnum->shopperId = $shopper;
				$alumnum->alumnumId = $this->getAlumnumId($shopper);
				$alumnum->name = $this->getAlumnumName($alumnum->alumnumId);

				$transaction->alumni[] = $alumnum;
			}
		}

		return $transactions;
	}

	function getTransactions($shopperId) {
		$query = $this->db->query("SELECT TRANSACTIONS.ID AS transactionId, TRANSACTIONS.time AS time FROM `intern_oelkaelder_purchase` AS PURCHACES LEFT JOIN `intern_oelkaelder_transaction` AS TRANSACTIONS ON PURCHACES.transactionId = TRANSACTIONS.ID WHERE shopperId = $shopperId AND `valid` = 1 ORDER BY TRANSACTIONS.time DESC, TRANSACTIONS.ID DESC");	
		$transactions = $query->result();

		foreach ($transactions as $transaction) {
			$transaction->items = $this->getTransactionItems($transaction->transactionId);
			$transaction->price = $this->getPrice($transaction->transactionId);
			$transaction->shoppers = $this->getShoppers($transaction->transactionId);
			$transaction->alumni = array();
			$transaction->type = "purchase";

			foreach ($transaction->shoppers as $shopper) {
				$alumnum = new stdClass();
				$alumnum->shopperId = $shopper;
				$alumnum->alumnumId = $this->getAlumnumId($shopper);
				$alumnum->name = $this->getAlumnumName($alumnum->alumnumId);

				$transaction->alumni[] = $alumnum;
			}
		}

		return $transactions;
	}

	function getTransactionsAndDeposits($shopperId) {
		$transactions = $this->getTransactions($shopperId);
		$deposits = $this->getDeposits($shopperId);

		if (count($transactions) == 0) $transactions = array();
		if (count($deposits) == 0) $deposits = array();

		$items = array_merge($transactions, $deposits);
		usort($items, 'sortByTime');

		return $items;
	}

	function getShoppingOverview($shopperId, $month) {
		$parts = explode(":", $month);
		$startMonth = 1 + $parts[0]; // Convert the month from 00-11 to 01-12
		$startYear = 0 + $parts[1];
		$endMonth = 1 + $startMonth;
		$endYear = $startYear;

		if ($endMonth == 13) {
			$endMonth = 1;
			$endYear = 1 + $startYear;
		}
		
		//$query = $this->db->query("SELECT name, SUM(price) as amount  FROM `intern_oelkaelder_purchase` JOIN `intern_oelkaelder_transaction` ON `intern_oelkaelder_purchase`.transactionId= `intern_oelkaelder_transaction`.ID JOIN `intern_oelkaelder_transaction_item` ON `intern_oelkaelder_transaction`.ID =`intern_oelkaelder_transaction_item`.transactionId JOIN `intern_oelkaelder_product` ON `intern_oelkaelder_transaction_item`.productId=`intern_oelkaelder_product`.productId WHERE `shopperId` = $shopperId AND `valid` = 1 AND `time` BETWEEN '$startYear/$startMonth/01' AND '$endYear/$endMonth/01' GROUP BY `intern_oelkaelder_product`.productId");

		$query = $this->db->query("SELECT name, CAST(SUM(individualPrice) AS INT) as amount FROM `intern_oelkaelder_purchase` JOIN `intern_oelkaelder_transaction` ON `intern_oelkaelder_purchase`.transactionId= `intern_oelkaelder_transaction`.ID JOIN `intern_oelkaelder_transaction_item` ON `intern_oelkaelder_transaction`.ID =`intern_oelkaelder_transaction_item`.transactionId JOIN `intern_oelkaelder_individual_price` ON `intern_oelkaelder_transaction`.ID =`intern_oelkaelder_individual_price`.ID AND  `intern_oelkaelder_transaction_item`.productId =`intern_oelkaelder_individual_price`.productId JOIN `intern_oelkaelder_product` ON `intern_oelkaelder_individual_price`.productId=`intern_oelkaelder_product`.productId WHERE `shopperId` = $shopperId AND `valid` = 1 AND `time` BETWEEN '$startYear/$startMonth/01' AND '$endYear/$endMonth/01' GROUP BY `intern_oelkaelder_product`.productId");
		return $query->result();
	}


	function getSaleOverview($startDate, $endDate) {
		$query = $this->db->query("SELECT name, CAST(SUM(individualPrice) AS INT) as amount FROM `intern_oelkaelder_purchase` JOIN `intern_oelkaelder_transaction` ON `intern_oelkaelder_purchase`.transactionId= `intern_oelkaelder_transaction`.ID JOIN `intern_oelkaelder_transaction_item` ON `intern_oelkaelder_transaction`.ID =`intern_oelkaelder_transaction_item`.transactionId JOIN `intern_oelkaelder_individual_price` ON `intern_oelkaelder_transaction`.ID =`intern_oelkaelder_individual_price`.ID AND  `intern_oelkaelder_transaction_item`.productId =`intern_oelkaelder_individual_price`.productId JOIN `intern_oelkaelder_product` ON `intern_oelkaelder_individual_price`.productId=`intern_oelkaelder_product`.productId WHERE `valid` = 1 AND `time` BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' GROUP BY `intern_oelkaelder_product`.productId");
		return $query->result();
	}

	function purchase($transaction) {
		if (count($transaction->shoppers) == 0) {
			return "No shoppers specified";
		}

		foreach ($transaction->shoppers as $shopper) {
			$shopperId = $this->getShopperId($shopper);
			if (!$this->shopperExists($shopperId)) {
				return "Unkown shopper";
			}
		}

		if (count($transaction->items) == 0) {
			return "No items";
		}

		foreach ($transaction->items as $item) {
			if (!$this->itemExists($item->productId)) {
				return "Unknown items";
			}
		}

		$transactionId = $this->createTransaction($transaction->date);
		
		foreach ($transaction->items as $item) {
			$this->addItem($transactionId, $item);
		}

		foreach ($transaction->shoppers as $shopper) {
			$shopperId = $this->getShopperId($shopper);
			$this->addShopper($transactionId, $shopperId);
		}

		$price = $this->getPrice($transactionId);
		$shopperCount = count($transaction->shoppers);
		$priceShare = -1 * ($price / $shopperCount);

		foreach ($transaction->shoppers as $shopper) {
			$shopperId = $this->getShopperId($shopper);
			$this->changeSaldo($shopperId, $priceShare);
		}

		return "OK";
	}

}

?>
