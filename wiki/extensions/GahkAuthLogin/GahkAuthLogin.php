<?

// Original by Theis F. Hinz
// Last updated by Nicholas Swiatecki <nicholas@swiatecki.com>

require_once($IP."/includes/AuthPlugin.php") ;


class GahkAuthLogin extends AuthPlugin {
        function userExists( $username ) {
                return true; // We cannot answer this question without the token
        }


        function authenticate( $loginUsername, $loginPassword ) {
			$usernameScore = str_replace(" ", "_", $loginUsername);
			$usernameLow = strtolower($usernameScore);
			$hashpwd = hash('sha256', $loginPassword);
			$query = "SELECT * FROM `intern_alumne` WHERE (ID='$usernameLow' OR email='$usernameLow') AND password='$hashpwd';";

			//die($loginUsername . " " . $usernameScore . " " . $usernameLow);
			//die($query);
			//echo $query . "\n";

			//SQL connection stuff
			$username="gahk_dk";
			$password="keldogfrederik";
			$database="gahk_dk";

			$handle = mysql_connect('localhost',$username,$password);
			mysql_select_db($database, $handle);
			$result = mysql_query($query);
			if (!$result) {
				die('Invalid query: ' . mysql_error());
			} else {
				if(1 == mysql_num_rows($result)) {
					return true;
				} else if(0 == mysql_num_rows($result)) {

				}
			}



			return false;
        }

	function autoCreate() {
        	return true;
        }

        function strict() {

        	// Disable local users in MediaWiki
        	return true;
        }

        function canCreateAccounts(){

        	return false;
        }


        function initUser(&$user,$autocreate = true) {
                # Override this to do something.
                // TODO: groups, rights

        	//The update user function does everything else we need done.
			$this->updateUser($user);
			//$user->setRealName("DillerFar");
			
        }

        // When a user logs in, optionally fill in preferences and such.
	function updateUser( &$user ) {


		/* This functions pulls the alumnes Real name from the alumneliste, and inserts into
		the mediawiki DB */
		$tmpEmail = $user->getName();
		$usernameScore = str_replace(" ", "_", $tmpEmail);
		$usernameLow = strtolower($usernameScore);
		$query = "SELECT firstName, lastName FROM intern_alumne WHERE email='".$usernameLow."' LIMIT 1";

		echo "So we meet again $tmpEmail! \n";
		echo "$query \n";

		//SQL connection stuff
		$username="gahk_dk";
		$password="keldogfrederik";
		$database="gahk_dk";

		$handle = mysql_connect('localhost',$username,$password);

		if($handle){
			mysql_select_db($database, $handle);
			$result = mysql_query($query);
			if (!$result) {
				die('Invalid query: ' . mysql_error());
			} else {			
				if(1 == mysql_num_rows($result)) {
					$u = mysql_fetch_array($result);
					$user->setRealName($u['firstName'] . " " . $u['lastName']);
					$user->saveSettings();
					return true;
				} else if(0 == mysql_num_rows($result)) {
					die("Empty Query");
					return false;		
				}
			}
		}else{
			die("NEJ NEJ NEJ");
			return false;
		}	

		return true;
	}

	/*function setPassword( $user, $password ) {
			return false;
	}*/
 
 function allowPasswordChange() {
    return false;
  }



}

?>
