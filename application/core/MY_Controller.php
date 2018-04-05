<?php

class MY_Controller extends CI_Controller {

	public function __construct()	{
			parent::__construct();

			$this->load->library('session');
			$this->load->helper('gahk_helper');
	}

	public function showInternPage($template_name, $vars = array(), $return = FALSE)
    {
    	$username = $this->session->userdata('username');
		$fullname = $this->session->userdata('fullname');
		$alumneId = $this->session->userdata('alumne_id');
		$akRole = $this->session->userdata('akRole');
		$indstilling = $this->session->userdata('indstilling');
		$inspektion = $this->session->userdata('inspektion');
		$kokkengruppe = $this->session->userdata('kokkengruppe');
		$oelkaelder = $this->session->userdata('oelkaelder');

		$vars['username'] = $username;
		$vars['fullname'] = $fullname;
		$vars['alumneId'] = $alumneId;
		$vars['akRole'] = $akRole;
		$vars['indstilling'] = $indstilling;
		$vars['inspektion'] = $inspektion;
		$vars['kokkengruppe'] = $kokkengruppe;
		$vars['oelkaelder'] = $oelkaelder;

        $content  = $this->load->view('intern/header.php', $vars, $return);
        $content .= $this->load->view($template_name, $vars, $return);
        $content .= $this->load->view('intern/footer.php', $vars, $return);

        if ($return)
        {
            return $content;
        }
    }

    public function counter(){
			$this->load->model('Counter_model');

			/*
			* This part is a counter
			*/

			date_default_timezone_set('Europe/Copenhagen');
			$ip		= $_SERVER['REMOTE_ADDR'];
			$today	= time();
			$dato = date("d/m-Y");
			$needCountByDate = false;

			//Update count by IP
			$query = $this->Counter_model->get_count_by_ip($ip);
			if(count($query) > 1){
				echo "ERROR IN COUNTER";
			} else if(count($query) == 1)  {

				//If ip have visited page before
				if(abs($today - $query[0]->lastCount) > 60*30) {

						$countpp	= $query[0]->count+1;
						$id			= $query[0]->id;

						$data = array(
						'count' => $countpp,
						'lastCount' => $today,
						'lastcountdato' => $dato
						);
						$query = $this->Counter_model->update_count_by_ip($ip, $data);

						$needCountByDate = true;
				}
			}	else {
				$data = array(
					'ip' => $ip,
     		   	'count' => 1,
					'lastCount' => $today,
					'lastcountdato' => $dato
      		);
				$this->Counter_model->insert_count_by_ip($data);
				$needCountByDate = true;
			}


			if($needCountByDate){			
				//Update count by DATO
				$queryOnDate = $this->Counter_model->get_count_by_date($dato);
				if(count($queryOnDate) > 1){
					echo "ERROR IN COUNTERDATE";
				} else if(count($queryOnDate) == 1){
						$data = array(
						 'count' => ($queryOnDate[0]->count)+1
						);
						$this->Counter_model->update_count_by_date($dato, $data);
				}	else {
					$data = array(
						'dato' => $dato,
						'count' => 1
					);
					$this->Counter_model->insert_count_by_date($data);
				}
				//END Update count by DATO
			}
    }


	public function sendAnsoegningPaamindelseIfTime(){
		$this->load->model('Ansoegninger_model');
		$week = date("W Y");
		if(count($this->Ansoegninger_model->getPaamindelseForWeek($week)) == 0){
			$this->sendPaamindelsesMail("indstillingen@gahk.dk");
			$this->Ansoegninger_model->insertPaamindelseForWeek($week);
		}
		
	}
	
	
	private function sendPaamindelsesMail($to){
			$this->load->model('Ansoegninger_model');
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= "From: Gahk.dk <bot@gahk.dk>" . "\r\n";

			$subject = "Der er flere umodtagede ansoegninger";	
			$urlToSystem =  base_url("/optagelse/listAnsoegninger");
			$notReceivedAnsoegninger = $this->Ansoegninger_model->getAnsoegningerNotReceived();
			$ansoegningsRow = "<tr><td>Dato</td><td>Navn</td></tr>";
			foreach ($notReceivedAnsoegninger as $ansoegning) {
					$ansoegningsRow .= "<tr><td>$ansoegning->day/$ansoegning->month $ansoegning->year</td><td>$ansoegning->fullName</td></tr>";
			}
			
			
			if(count($notReceivedAnsoegninger) != 0){
				$title = "<h2>".count($notReceivedAnsoegninger)." ansøgninger er ikke modtaget</h2>";
				
				return mail($to,
					$subject,
					"
						<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 3.2 Final//EN'>
						<html>
							<body>
								$title
								Kære Indstilling.<br />
								Der er modtaget flere ansoegninger, men som fortsat ikke er registreret.<br />
								I kan se alle ansøgninger her: <a href='$urlToSystem'>$urlToSystem</a>. <br /><br />
								Ansøgningerne kan ses herunder:<br />
								<table>
								$ansoegningsRow
								</table>
								<br />
								Vær opmærksom på at der ikke er garanti for at i modtager en mail for alle ansøgninger som bliver lavet.<br /> 
								Sørg derfor for at tjekke systemet på gahk.dk (især hvis i ikke har set en påmindelse som denne længe).<br />
								Registrer en ansøgning som modtaget under ansøgningerne på systemet, for at undgå at de bliver vist på disse mails fremover.
								<br /><br/>
								
								Hilsen Gahk.dk<br />
								Ps. Dette er en automatisk påmindelse som udsendes 1 gang om ugen.
							</body>
						</html>
					", "$headers"	
					);
			}
	}	

}
