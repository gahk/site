<?

class Adminuser_model extends CI_Model {

	function __construct(){
		parent::__construct();

		$this->load->helper('cookie');
	}

	function login($email, $passwordhash){
		$query =  $this->db->query("SELECT * FROM gahk_admin_user INNER JOIN intern_alumne ON gahk_admin_user.alumne_id = intern_alumne.ID WHERE email = '$email' AND password = '$passwordhash'");
     return $query->result();
	 }

	function searchOnAlumne($searchWord){
		$query =  $this->db->query("SELECT id,  concat( firstName, ' ', lastName ) AS label FROM intern_alumne WHERE  CONCAT(firstName,' ',lastName) like '%$searchWord%'");
     return $query->result();
	 }

	function loginSession() {
		$session_id = get_cookie('session_token');

		if ($session_id == '') return array();

		$query = $this->db->query("SELECT * FROM intern_alumne INNER JOIN intern_alumne_sessions INNER JOIN gahk_admin_user ON gahk_admin_user.alumne_id = intern_alumne.ID WHERE intern_alumne.ID = intern_alumne_sessions.alumnum_id AND session_id = '$session_id'");

		return $query->result();
	}

	function listAllAdminUser(){
		$query =  $this->db->query("SELECT gahk_admin_user.id, firstName, lastName, gahk_admin_user.* FROM gahk_admin_user INNER JOIN intern_alumne ON gahk_admin_user.alumne_id = intern_alumne.ID ORDER BY firstName");
     return $query->result();
	 }

	function addUserAdm($data){
		$this->db->insert('gahk_admin_user', $data); 
	}
		
	function deleteuseradm($id){
		$this->db->delete('gahk_admin_user', array('id' => $id)); 
	}

	function getAlumneOnId($alumneId){
		$query =  $this->db->query("SELECT firstName, lastName, moveInDay FROM intern_alumne WHERE ID = $alumneId");
     return $query->result();
	}
	
	
}

?>