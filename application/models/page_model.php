<?

class Page_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function get_page($id){
		$this->db->where('id', $id); 
   	$query = $this->db->get('gahk_page');
   	return $query->result();
   }

	function update_by_id($id, $data){
		$this->db->where('id', $id);
		$this->db->update('gahk_page', $data); 
	}

}

?>
