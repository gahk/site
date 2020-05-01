<?

class Kvotient_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function addKvotientApplication($data){
		$this->db->insert('intern_kvotient_nyintern', $data);
		return $this->db->insert_id();
	}

	function getApplicationsByRoom($roomNr){
		$query =  $this->db->query("SELECT kvotient.*, priority.*, alumne.firstName, alumne.lastName, 0 as won  
			FROM (intern_kvotient_nyintern AS kvotient INNER JOIN intern_kvotient_priority_nyintern AS priority     
			ON kvotient.ID = priority.ansoegnings_id)
				INNER JOIN intern_alumne AS alumne
        	ON kvotient.alumne_id = alumne.ID
			WHERE vaerelse_id = ".$this->db->escape($roomNr).
			"ORDER BY K DESC, applyDatetime ASC, priority.priority ASC");
		return $query->result();
	}
	
	function getApplications(){
		$query =  $this->db->query("SELECT kvotient.*, priority.*  FROM 
			intern_kvotient_nyintern AS kvotient INNER JOIN intern_kvotient_priority_nyintern AS priority     
			ON kvotient.ID = priority.ansoegnings_id ORDER BY K DESC, priority.priority ASC");
		return $query->result();
	}


	function getKvotientDataFromAnsoegningsId($ansoegningsId){
		$query = $this->db->query("
			SELECT * FROM `intern_kvotient_nyintern` AS applica
			LEFT JOIN intern_kvotient_orlov_nyintern AS orlov
			ON applica.ID = orlov.ansoegnings_id
			WHERE applica.ID = ".$this->db->escape($ansoegningsId));
		return $query->result();
	}
	
	function deleteAnsoegningByOfferId($offerId){
		$query = $this->db->query(" 
			DELETE prio, ansoeg, orlov
			FROM intern_kvotient_priority_nyintern AS prio 
			INNER JOIN intern_kvotient_nyintern AS ansoeg ON prio.ansoegnings_id = ansoeg.ID
			LEFT JOIN intern_kvotient_orlov_nyintern AS orlov ON orlov.ansoegnings_id = prio.ansoegnings_id
			INNER JOIN intern_kvotient_offer_nyintern AS offer ON prio.vaerelse_id=offer.vaerelses_id
			WHERE offer.id=".$this->db->escape($offerId));
		//return $query->result();
	}
	
	function getApplicationsByAlumneId($alumneId){
		$query = $this->db->query("
			SELECT kvotient.*, priority.*  
			FROM intern_kvotient_nyintern AS kvotient INNER JOIN intern_kvotient_priority_nyintern AS priority     
				ON kvotient.ID = priority.ansoegnings_id
			WHERE kvotient.alumne_id = ".$this->db->escape($alumneId)."
			ORDER BY K DESC, priority.priority ASC");
		return $query->result();
	}

}

?>
