<?

class Aklog_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}


	function getLogByAlumneId($alumneId){
		$query =  $this->db->query("SELECT * FROM intern_alumne_aklog WHERE alumne_id = $alumneId ORDER BY id DESC");
		return $query->result();
	}

	function addAkLog($alumneId, $data){
		$alumArray = array("alumne_id" => $alumneId, "timestamp" => time());
		$data = array_merge((array)$data, (array)$alumArray);
		$this->db->insert('intern_alumne_aklog', $data); 
	}

	function getElementById($id){
		$query =  $this->db->query("SELECT * FROM intern_alumne_aklog WHERE id = $id");
		return $query->result();
	}

	function deleteFromLog($id){
		$this->db->delete('intern_alumne_aklog', array('id' => $id));
	}

}

?>
