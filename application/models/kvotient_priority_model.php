<?

class Kvotient_priority_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function addPriority($data){
		$this->db->insert('intern_kvotient_priority_nyintern', $data);
	}
	
	//Not used anymore
	function deletePriorityByAnsoegningId($id){
		$this->db->delete('intern_kvotient_priority_nyintern', array('vaerelse_id' => $id));
	}
	


}

?>
