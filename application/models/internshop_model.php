<?

class Internshop_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function getShopperList() {
		$query = $this->db->query("SELECT ALL_ALUMS.ID AS id, CONCAT(ALL_ALUMS.firstName, ' ', ALL_ALUMS.lastName) AS name FROM `intern_alumne` AS ALL_ALUMS JOIN (SELECT * FROM `intern_alumne_liste` WHERE `monthNumber` = (SELECT monthNumber FROM `intern_alumne_liste` ORDER BY monthNumber DESC LIMIT 1)) AS CUR_ALUMS ON (ALL_ALUMS.ID = CUR_ALUMS.alumne_id) ORDER BY name ASC");
		return $query->result();
	}

}

?>
