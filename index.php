<?php //index.php
  session_start();
  echo <<<_HTML
    <html>
      <head>
        <title> Mood Modelling </title>
        <link rel = "stylesheet" type = "text/css" href = "index.css">
        <script type = "text/javascript" src = "index.js"> </script>
       </head>
      <body>
        <div id = "topPanel">
          <h2> Welcome to the exercise of mood modelling </h2>
          <form action = "index.php" method = "post" onsubmit = "return validateSignIn(this)">
            <br>
              <input type = "text" name = "username" placeholder = "Enter username">
              <input type = "password" name = "password" placeholder = "Enter password">
              <input type = "submit" name = "signin" value = "Sign in">
            </br>
          </form>
        </div>

        <div id = "midPanel">
          <div id = "infoPanel">
            <h2> Document your life and maximize net pleasure </h2>
            <h4> <img src = "assets\\images\\document.png"> </img> Journal your day and emotions </h4>
            <h4> <img src = "assets\\images\\analyze.png"> </img> Recognize what factors affect your happiness the most </h4>
            <h4> <img src = "assets\\images\\maximize.png"> </img> Maximize happiness </h4>
          </div>

          <div id = "signUpForm">
            <form action = "index.php" method = "post" onsubmit = "return validateSignUp(this)">
              <br> <input type = "text" name = "firstName" placeholder = "Enter first name"> </br>
              <br> <input type = "text" name = "lastName" placeholder = "Enter last name"> </br>
              <br> <input type = "text" name = "age" placeholder = "Enter age"> </br>
              <br> <input type = "text" name = "username" placeholder = "Create username"> </br>
              <br> <input type = "email" name = "email" placeholder = "Enter email"> </br>
              <br> <input type = "password" name = "password" placeholder = "Create password"> </br>
              <br> <input type = "submit" name = "signup" value = "Create account"> </br>
            </form>
          </div>
        </div>
_HTML;

  require_once 'login.php';
  require_once 'functions.php';
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die (mysql_fatal_error());

  $_SESSION['check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);

  if (isset($_POST['signup'])){
    $sanitized_first_name = sanitize($conn, $_POST['firstName']);
    $sanitized_last_name = sanitize($conn, $_POST['lastName']);
    $sanitized_age = sanitize($conn, $_POST['age']);
    $sanitized_username = sanitize($conn, $_POST['username']);
    $sanitized_email = sanitize($conn, $_POST['email']);
    $sanitized_password = sanitize($conn, $_POST['password']);

    //check if this username already exists
    $select_stmt = $conn->prepare('SELECT * FROM user WHERE username = ?');
    $select_stmt->bind_param('s', $sanitized_username);
    $select_stmt->execute();
    $result = $select_stmt->get_result();
    $select_stmt->close();

    if (!$result) die (mysql_fatal_error());
    if ($result->num_rows !== 0) {
      echo "<script>alert('username already taken')</script>";
      die ("Please choose a different username");
    }
    $result->close();

    $salt = generate_salt();
    $salted_password = $salt.$sanitized_password;
    $password_hash = hash('ripemd128', $salted_password);

    $insert_cred = $conn->prepare('INSERT INTO credentials VALUES (?, ?, ?)');
    $insert_cred->bind_param('sss', $sanitized_username, $password_hash, $salt);
    $insert_cred->execute();
    $insert_cred->close();

    $insert_user = $conn->prepare('INSERT INTO user VALUES (?, ?, ?, ?, ?)');
    $insert_user->bind_param('sssss', $sanitized_username, $sanitized_first_name, $sanitized_last_name,
                                $sanitized_age, $sanitized_email);
    $insert_user->execute();
    $insert_user->close();

    $_SESSION['username'] = $sanitized_username;
    die (validate());
  }

  if (isset($_POST['signin'])){
    $sanitized_username = sanitize($conn, $_POST['username']);
    $sanitized_password = sanitize($conn, $_POST['password']);

    $find_user = $conn->prepare('SELECT * FROM credentials WHERE username = ?');
    $find_user->bind_param('s', $sanitized_username);
    $find_user->execute();
    $result = $find_user->get_result();
    $find_user->close();


    if (!$result) die (mysql_fatal_error());
    else{
      if ($result->num_rows == 0){
        die ("No username found");
      }

      $stored_pw_hash = '';
      $stored_salt = '';

      for ($j = 0; $j < $result->num_rows; $j++){
  			$result->data_seek($j);
  			$row_data = $result->fetch_array(MYSQLI_NUM);
  			$stored_pw_hash = $row_data[1];
        $stored_salt = $row_data[2];
		  }
      $result->close();

      $token = hash('ripemd128', $stored_salt.$sanitized_password);
      if (strcmp($token, $stored_pw_hash) !== 0) die (invalid_credentials());
      else{
        $_SESSION['username'] = $sanitized_username;
        die (validate());
      }
    }
  }

  $conn->close();
  echo "</body></html>";
?>
