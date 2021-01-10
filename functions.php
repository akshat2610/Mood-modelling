<?php //sanitize.php
  define ("SALT_LENGTH", 10);

  //show custom message to the user
	function mysql_fatal_error(){
		return "Hmm! Something is not right";
	}

	//sanitizing methods
	function sanitize_string($var){
		$var = stripslashes($var);
		$var = strip_tags($var);
		$var = htmlentities($var);

		return $var;
	}

	function sanitize($conn, $var){
		$var = $conn->real_escape_string($var);
		$var = sanitize_string($var);
		return $var;
	}

  function generate_salt(){
    $salt = '';
    $letter_dict = ['a', 'b', 'c', 'd', 'e',
                    'f', 'g', 'h', 'i', 'j',
                    'k', 'l', 'm', 'n', 'o',
                    'p', 'q', 'r', 's', 't',
                    'u', 'v', 'w', 'x', 'y', 'z'];

    for ($i = 0; $i < SALT_LENGTH; $i++)
      $salt .= $letter_dict[rand(0, 25)];

    return $salt;
  }

  function invalid_credentials(){
    return "Invalid username or password";
  }

  function validate(){
    return "<br><a href = 'continue.php'>Click here to continue</a></br>";
  }

  function revalidate(){
    return "<br>Please <a href = 'index.php'>Authenticate yourself here</a> to log in.</br>";
  }

  function destroy_data_and_session(){
    $_SESSION = array();
    setcookie(session_name(), '', time() - 2592000, '/');
    session_destroy();
  }
?>
