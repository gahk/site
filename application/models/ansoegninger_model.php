<?

class Ansoegninger_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function addAnsoegning($data){
		$data['day'] = date('j');
		$data['month'] = date('n');
		$data['year'] = date('Y');
		$data['timestamp'] = time();
		$data['female'] = $data['gender']=="female";
		unset($data['gender']);
		$this->db->insert('gahk_ansoegninger', $data); 
		return $this->db->insert_id();
	}


	function getNewestAnsoegninger($from, $to){
		$query =  $this->db->query("
			SELECT ansoeg.*, alumne.firstName AS receiverFirstName, alumne.lastName AS receiverLastName 
			FROM gahk_ansoegninger AS ansoeg LEFT JOIN intern_alumne AS alumne 
				ON ansoeg.receivedByAlumneId = alumne.ID
			ORDER BY ansoeg.id DESC LIMIT $from, $to");
     return $query->result();
	}

	function numberOfAnsoegninger(){
		$query =  $this->db->query("SELECT * FROM gahk_ansoegninger");
		return $query->num_rows();
	}

	function getAnsoegningerById($id){
		$query =  $this->db->query("
			SELECT ansoeg.*, alumne.firstName AS receiverFirstName, alumne.lastName AS receiverLastName 
				FROM gahk_ansoegninger AS ansoeg LEFT JOIN intern_alumne AS alumne 
					ON ansoeg.receivedByAlumneId = alumne.ID
			WHERE ansoeg.id = $id");
		return $query->result();
	}


	function getAnsoegningerByWeek($aDayInWeek, $typeOfAnsoegning){
		//Currently not used (month statistic is used instead)
$beginning_of_week = strtotime('last Monday', $aDayInWeek); 
$end_of_week = strtotime("+1 week", $beginning_of_week); 
		$query =  $this->db->query("SELECT * FROM gahk_ansoegninger WHERE timestamp > $beginning_of_week AND timestamp < $end_of_week AND typeOfAnsoegning	= '$typeOfAnsoegning'");
		return $query->num_rows();
	}


	function getAnsoegningerByMonth($aDayInMonth, $typeOfAnsoegning){
$beginning_of_week = strtotime('first day of this month', $aDayInMonth); 
$end_of_week = strtotime("last day of this month", $beginning_of_week); 
		$query =  $this->db->query("SELECT * FROM gahk_ansoegninger WHERE timestamp > $beginning_of_week AND timestamp < $end_of_week AND typeOfAnsoegning	= '$typeOfAnsoegning'");
		return $query->num_rows();
	}


	function getAnsoegningerByStudyAndMonth(){
		$query =  $this->db->query("
			SELECT CONCAT(year,'-',month) AS date, month, year, university, COUNT( * ) AS antal
			FROM  `gahk_ansoegninger` 
			WHERE `typeOfAnsoegning` = 'rundvisning'
			GROUP BY university, month, year
			ORDER BY year DESC, month DESC
			LIMIT 0,80
			");

		return $query->result();

	}

	function getAnsoegningerByStudyAndThisYear(){
		$query =  $this->db->query("
			SELECT year AS date, university, COUNT( * ) AS antal
			FROM  `gahk_ansoegninger` 
			WHERE year = YEAR(CURDATE()) AND `typeOfAnsoegning` = 'rundvisning'
			GROUP BY university");

		return $query->result();
	}


	function getAnsoegningerByHowYourHeardAndThisYear(){
		$query =  $this->db->query("
			SELECT heardAboutUs as label, COUNT( * ) AS value
			FROM  `gahk_ansoegninger` 
			WHERE year = YEAR(CURDATE()) AND `typeOfAnsoegning` = 'rundvisning' AND heardAboutUs != ''
			GROUP BY heardAboutUs");

		return $query->result();
	}


	function getAnsoegningerByHowYourHeard(){
		$query =  $this->db->query("
			SELECT heardAboutUs as label, COUNT( * ) AS value
			FROM  `gahk_ansoegninger` 
			WHERE `typeOfAnsoegning` = 'rundvisning' AND heardAboutUs != ''
			GROUP BY heardAboutUs");

		return $query->result();
	}
	
	function setAnsoegningAsReceived($ansoegningsId, $receiverAlumneId){
		$data = array('receivedByAlumneId' => $receiverAlumneId );
		$this->db->where('id', $ansoegningsId);
		$this->db->update('gahk_ansoegninger', $data); 
	}
	
	function getAnsoegningerNotReceived(){
		$query =  $this->db->query("SELECT * FROM  `gahk_ansoegninger` WHERE receivedByAlumneId = '0'");
		return $query->result();
	}

	function getPaamindelseForWeek($week){
		$query =  $this->db->query("SELECT * FROM  `gahk_ansoegninger_paamindelse` WHERE week = '$week'");
		return $query->result();		
	}
	
	function insertPaamindelseForWeek($week){
		$data = array('week' => $week);
		$this->db->insert('gahk_ansoegninger_paamindelse', $data); 
	}
	
	
	

	
	
}

?>