<?
class RoomCriteria_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}


	function getCriteria(){
		$query = $this->db->query("SELECT * FROM `intern_room_criteria`");
		return $query->result();
	}

}

?>