<?

class Internuser_model extends CI_Model {

	function __construct(){
		parent::__construct();

		$this->load->helper('cookie');

	}

	function login($email, $passwordhash){
		$query =  $this->db->query("SELECT * FROM intern_alumne WHERE email = '$email' AND password = '$passwordhash'");
     		return $query->result();
	 }


	function getNumberOfAlumnePerMonthByStudy($study){
		$query =  $this->db->query("
		SELECT COUNT(*) AS numberOfAlumne, monthNumber FROM intern_alumne IA
		LEFT JOIN intern_alumne_liste IAL ON IA.ID=IAL.alumne_ID
		WHERE IA.study LIKE '$study%'
		GROUP BY IAL.monthNumber");
  		return $query->result();
	}

	function loginSession() {
		$session_id = get_cookie('session_token');

		if ($session_id == '') return array();

		$query = $this->db->query("SELECT * FROM intern_alumne INNER JOIN intern_alumne_sessions WHERE intern_alumne.ID = intern_alumne_sessions.alumnum_id AND session_id = '$session_id'");

		return $query->result();
	}

	function clearSession() {
		$session_id = get_cookie('session_token');

		if ($session_id == '') return false;

		$query = $this->db->query("DELETE FROM `gahk_dk`.`intern_alumne_sessions` WHERE `intern_alumne_sessions`.`session_id` = '$session_id';");

		delete_cookie('session_token');

		return $query;
	}

	function createSession($alumneId) {
		$this->clearSession();
		$session_id = hash('sha256', $alumneId." ".microtime()." ".rand(0, 1000));
		$query = $this->db->query("INSERT INTO `gahk_dk`.`intern_alumne_sessions` (`alumnum_id`, `session_id`) VALUES ('$alumneId', '$session_id');");

		$cookie = array(
			'name' => 'session_token',
			'value' => $session_id,
			'expire' => time()+86500,
			'path'   => '/',
		);
		set_cookie($cookie);

		return $query;
	}

	function getAlumneByEmail($email){
		$query =  $this->db->query("SELECT id, firstName FROM intern_alumne WHERE email = '".$email."'");
  		return $query->result();
	}
	
	function addForgotPasswordLink($data){
			$this->db->insert('intern_forgotpassword', $data); 		
	}
	
	function getAlumneIdByForgotPassLinkId($linkId){
		$query =  $this->db->query("SELECT alumneid FROM intern_forgotpassword WHERE link = '".$linkId."'");
  		return $query->result();
	}
	
	function updateUser($data, $alumneId){
		$this->db->where('id', $alumneId);
		$this->db->update('intern_alumne', $data);
		if ($this->db->affected_rows() == '1') {
    		return true;
    	} else {
    		return false;
    	}
	}

	function getUser($alumneId) {
		$query = $this->db->get_where('intern_alumne', array('id' => $alumneId));
		$alumne = $query->result();

		if (isset($alumne[0])) {
			return $alumne[0];
		} else {
			return null;
		}
	}

	function changePassword($email, $oldpass, $newpass) {
		$query =  $this->db->query("SELECT id, password FROM intern_alumne WHERE email = '".$email."'");
		$alumne = $query->result();

		if (!isset($alumne[0])) {
			return false;
		}

		if ($alumne[0]->password != hash("sha256", $oldpass)) {
			return false;
		}

		$storedata["password"]=hash("sha256",$newpass);
		$id=$alumne[0]->id;
		return $this->updateUser($storedata, $id);
	}


}

?>
