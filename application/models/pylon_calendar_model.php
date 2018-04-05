<?

class Pylon_calendar_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function get_active_events(){
		$this->db->where('timestamp >=', time()); 
		$this->db->order_by("timestamp", "asc"); 
		$query = $this->db->get('gahk_pylon_calendar');
		return $query->result();
 	}

	function add_event($data){
		$this->db->insert('gahk_pylon_calendar', $data); 
	}

	function delete_event($id){
		$this->db->delete('gahk_pylon_calendar', array('id' => $id)); 
	}

}

?>
