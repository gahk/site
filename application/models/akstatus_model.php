<?

class Akstatus_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}


	function getStatusByAlumneId($alumneId){
		$query =  $this->db->query("SELECT * FROM intern_alumne_akstatus WHERE alumne_id = $alumneId");
		return $query->result();
	}

	function increaseOrCreateStatus($alumneid, $data){
		$query =  $this->db->query("SELECT COUNT(*) as doesExist FROM intern_alumne_akstatus WHERE alumne_id = $alumneid");
		$res = $query->result();

		if($res[0]->doesExist == "1"){
			$query =  $this->db->query("UPDATE intern_alumne_akstatus 
								SET totalkrydser = totalkrydser + ".$data["krydser"]." 
								WHERE alumne_id = $alumneid");
		} else {
			$inserData = Array("alumne_id" => $alumneid, "totalkrydser" => $data["krydser"]);
			$this->db->insert('intern_alumne_akstatus', $inserData); 
		}
	}

	private function getNewestMonthNumber(){
		//Get newest month
		$query =  $this->db->query("SELECT DISTINCT monthNumber
												FROM intern_alumne_liste
												ORDER BY monthNumber DESC 
												LIMIT 0 , 1");
		return $query->result();
	}

	function getAll(){
		$res = $this->getNewestMonthNumber();
		
		//Find alumne which is living at gahk right now by saying that they have be in embedsgruppe in newest month.
		//The alumne is then joined with akstatus table to find his ak-status
		//And joined with aklog to find out how many ak-krydser he haved registered in this period

		$query =  $this->db->query("
	SELECT tAl.ID as alumne_id, firstName, lastName, totalKrydser, SUM(krydser) as krydserInLog 
	FROM  intern_alumne tAl 
		LEFT JOIN intern_alumne_akstatus as tAk ON tAk.alumne_id = tAl.ID
		LEFT JOIN intern_alumne_aklog AS tLog ON tLog.alumne_id = tAl.ID
	WHERE tAl.ID IN 
		(SELECT DISTINCT alumne_id as ID 
		FROM intern_alumne_liste 
		WHERE monthNumber = '".$res[0]->monthNumber."') 
	GROUP BY tAl.ID
	ORDER BY firstName ASC");


		return $query->result();
	}

	function decreaseStatus($alumneId, $decValue){
		$this->db->query("UPDATE intern_alumne_akstatus 
									SET totalkrydser = totalkrydser - ".$decValue." 
									WHERE alumne_id = $alumneId");
	}

	function updateOrCreateStatus($alumneid, $data){
		$query =  $this->db->query("SELECT COUNT(*) as doesExist FROM intern_alumne_akstatus WHERE alumne_id = $alumneid");
		$res = $query->result();

		if($res[0]->doesExist == "1"){
			$query =  $this->db->query("UPDATE intern_alumne_akstatus 
								SET totalkrydser = ".$data["krydser"]." 
								WHERE alumne_id = $alumneid");
		} else {
			$inserData = Array("alumne_id" => $alumneid, "totalkrydser" => $data["krydser"]);
			$this->db->insert('intern_alumne_akstatus', $inserData); 
		}
	}

	function decreaseAllStatus($decValue){
		$this->db->query("UPDATE intern_alumne_akstatus SET totalkrydser = totalkrydser - ".$decValue);

		//We insert all who is not already in the system with -devValue as status
		//Get all alumne_id on alumnelist which live at gahk this month and which is not in akstatus
		$query =  $this->db->query("SELECT ID as alumne_id, firstName 
		 FROM  intern_alumne tAl 
		 WHERE id NOT IN 
			  (SELECT alumne_id as ID FROM intern_alumne_akstatus)
		  AND  tAl.ID IN 
		(SELECT DISTINCT alumne_id as ID 
		FROM intern_alumne_liste 
		WHERE monthNumber = '24178')");
		
		$res = $query->result();
		foreach ($res as $alumner => $alumne) {
			$inserData = Array("alumne_id" => $alumne->alumne_id, "totalkrydser" => $decValue);
			$this->db->insert('intern_alumne_akstatus', -1*$inserData); 			
		}
		
	}

	//This a list of all alumners which is not in ak-list yet
	function getAllSlackers(){
		$query = $this->db->query("SELECT * FROM `intern_alumne_akstatus` AS ak RIGHT JOIN intern_alumne as alumne ON ak.alumne_id = alumne.id
WHERE ak.totalkrydser is NULL");
		return $query->result();
	}

	//The new alumne will have status 0.
	function addAlumneToTable($alumneId){
		$data = array(
		   'alumne_id' => $alumneId,
		   'totalkrydser' => 0
		);
		$this->db->insert('intern_alumne_akstatus', $data); 
	}


}

?>
