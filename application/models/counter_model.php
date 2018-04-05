<?

class Counter_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function get_count_by_ip($ip){
		$this->db->where('ip', $ip); 
      $query = $this->db->get('gahk_counter');
   	return $query->result();
   }

	function get_count_by_date($dato){
		$this->db->where('dato', $dato); 
   	$query = $this->db->get('gahk_counterdato');
   	return $query->result();
   }

	function get_count_by_week($dato){
		$this->db->where('dato', $dato); 
   	$query = $this->db->get('gahk_counterdato');
   	return $query->result();
   }

	function update_count_by_ip($ip, $data){
		$this->db->where('ip', $ip);
		$this->db->update('gahk_counter', $data); 
	}

	function update_count_by_date($date, $data){
		$this->db->where('dato', $date);
		$this->db->update('gahk_counterdato', $data); 
	}

	function insert_count_by_date($data){
		$this->db->insert('gahk_counterdato', $data); 
	}

	function insert_count_by_ip($data){
		$this->db->insert('gahk_counter', $data); 
	}

}

?>
