<?

class Kvotient_orlov_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function addOrlov($data){
		$this->db->insert('intern_kvotient_orlov_nyintern', $data);
	}



}

?>
