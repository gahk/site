<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Stamtree extends MY_Controller {

	// Ja, jeg ved godt at et stamtræ ikke hedder StamTree på engelsk.. 

	public function __construct()	{
			session_start();
			parent::__construct();

			$this->load->helper('form');
			$this->load->library('session');
			//$this->load->model('Aklog_model');
			//$this->load->model('Akstatus_model');
			$this->load->model('Stamtree_model');

			//Loading url helper, this gives the base_url function
			$this->load->helper('url');

			// Pass params, because GahkTree has a constructor
			$params = array('1' => 'Dummy Poulsen');
			$this->load->library('GahkTree',$params);
	}

	public function index(){
	
		/* LOGIN CHECK */
		$username = $this->session->userdata('username');
		$fullname = $this->session->userdata('fullname');

		if(!$username){
			$this->session->set_flashdata('redirectToUrlAfterLogin', current_url());
			redirect("nyintern/admin");

		}
		/* END LOGIN CHECK */



		$data['pagename'] = "stamtree";
		$data['pageheader'] = "GAHK's Stamtræ";

		

		

		$data['treeOut'] = json_encode($this->buildTree($this->Stamtree_model->getAllAlumner()));

		$this->showInternPage('intern/stamtree', $data);
	}



	private function nameInResultSet($name,$dataSet){
		$out["result"] = false;
		$out["key"] = 0;

		// determines if a alumne is in the returned set
		foreach ($dataSet as $k => $alumne) {
			if($name == $alumne->name){
				// a match!
				
			//	echo "\n--" . $name . " MATCHED " . $alumne->name . " with key=" . $k;
				
				$out["result"] = true;
				$out["key"] = $k;

				break;
				
			}else{
				// HOLD UP! The line below generates an ugly amount of output
				//echo "\n--" . $name . " did not match " . $alumne->name;
				
			}

			
		}

		return $out;
	}

	private function addToTree($alumne,&$Map,&$Tree){
		
		// Find object
		//echo "Alumne is: " . $alumne->name;

		// Find the parent object in the map
		$P = $Map[$alumne->fylgje];
	
		

		$child = new GahkTree($alumne->name);
		//echo "\n New child is: \n";
		//print_r($child);

		//Sometimes the parent doesn't exsist?
		if(!is_null($P)){

		$P->addChild($child);
		$Map[$alumne->name] = $child;
		}else{
			echo "FEJL!!";

		}
		//echo "\n Now the Map is: \n";
		//print_r($Map);
 
	}


	private function buildTree($dataSet){

		$T = new GahkTree("Hagemanns Ånd");

		/* Fix parrents */
		foreach ($dataSet as $s) {
			
			// Check if the parrent eksists in the set
			
			if ($this->nameInResultSet($s->fylgje, $dataSet)["result"]){
				// Do nothing, 
			//	echo "\n Parent (" . $s->fylgje . ") for " . $s->name . " exists";

			} else{
				// If not, set to root
				//echo "\n Setting root for ". $s->name . "could not find parent" . $s->fylgje;
				$s->fylgje = "Hagemanns Ånd";

				// Shortcut: Add these nodes as children of Root in T
				//$T->addChild(new GahkTree($s->name));

			}
		}

		/* END FIX PARRENTS */
		
		/* Build the tree for the remaining alumner */

		/* 
		Loop all
			(As we sort by moveinDate the parent will always exist)
			find parrent and append to tree 

		*/

		
		$MDMAMap = array("Hagemanns Ånd" => $T);
		//print_r($MDMAMap);

		foreach ($dataSet as $s) {
			
			$this->addToTree($s,$MDMAMap,$T);

		}


	/* End Build the actual tree */



		return $T;


	}






}
