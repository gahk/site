<?

class News_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function add($data){
		$data['day'] = date('j');
		$data['month'] = date('n');
		$data['year'] = date('Y');
		$data['timestamp'] = time();
		$this->db->insert('gahk_news', $data); 
	}

	function update($data, $id){
		$this->db->where('id', $id);
		$this->db->update('gahk_news', $data); 
	}

	function get($id){
		$query =  $this->db->query("SELECT * FROM gahk_news WHERE id = '$id'");
		return $query->result();
	}

	function isAnyNewsLastTwoMonth(){
		$query =  $this->db->query("SELECT * FROM gahk_news WHERE timestamp > '".strtotime( '-2 month', time() )."'");
		if($query->num_rows() > 0){
			return 1;
		}
		return 0;
		
	}

	function getNewest($from, $to){
		$query =  $this->db->query("SELECT * FROM gahk_news ORDER BY id DESC LIMIT $from, $to");
		return $query->result();
	}

	function numberOfNews(){
		$query =  $this->db->query("SELECT * FROM gahk_news");
		return $query->num_rows();
	}

	function delete($id){
		$query =  $this->db->delete('gahk_news', array('id' => $id)); 
	}


}

?>
