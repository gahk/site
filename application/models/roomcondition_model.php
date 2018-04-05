<?
class RoomCondition_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}


	function addCondition($alumneId, $alumneName,$roomId, $date,$criteria,$comment,$images) {
		//update old is newest
		$this->db->query("UPDATE `intern_room_condition` SET `is_newest` = '0' WHERE `room_id` =$roomId AND `is_newest`=1");
		$this->db->query("INSERT INTO  `intern_room_condition` (`alumne_id`, `alumne_fullname`, `room_id`, `criteria`, `date`,`is_newest`,`comments`,`images`) VALUES ('$alumneId' , '$alumneName',  '$roomId',  '$criteria', '$date',1,'$comment','$images');");
	}

	function getConditionsByRoom($roomId){
		$query = $this->db->query("SELECT * FROM `intern_room_condition` WHERE `room_id`=$roomId ORDER BY date DESC");
		return $query->result();
	}

	function getNewestConditionByRoom($roomId){
		$query = $this->db->query("SELECT * FROM `intern_room_condition` WHERE `room_id`=$roomId AND `is_newest`=1 limit 1");
		return $query->result();
	}

	function getAllNewestConditions(){
		$query = $this->db->query("SELECT * FROM `intern_room_condition` WHERE 	`is_newest`=1");
		return $query->result();
	}



}

?>