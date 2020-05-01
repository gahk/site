<?

class Kvotientoffer_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function getOffers(){
		$query =  $this->db->query("SELECT * FROM intern_kvotient_offer_nyintern ORDER BY month, vaerelses_id ASC");
 		return $query->result();
	}

	function getMonthsWithOffers(){
		$query =  $this->db->query("SELECT DISTINCT month FROM `intern_kvotient_offer_nyintern` ORDER BY month ASC");
 		return $query->result();
	}

	function getOffersByMonthNr($monthNr){
		$query =  $this->db->query("SELECT * FROM intern_kvotient_offer_nyintern WHERE month = ".$this->db->escape($monthNr)." ORDER BY month, vaerelses_id ASC");
 		return $query->result();
	}
	
	function addOffer($data){
		$this->db->insert('intern_kvotient_offer_nyintern', $data);
		return $this->db->insert_id();
	}

	function deleteOfferById($offerId){
		$this->db->delete('intern_kvotient_offer_nyintern', array('id' => $offerId));
	}

	function getOfferById($id){
		$query =  $this->db->query("SELECT * FROM intern_kvotient_offer_nyintern WHERE id = $id");
 		return $query->result();
	}
	


}

?>
