<?php //continue.php
  require_once 'login.php';
  require_once 'functions.php';
  session_start();

  echo <<<_HTML
    <html>
      <head>
        <title> Mood Modelling </title>
        <link rel = "stylesheet" type = "text/css" href = "continue.css">
       </head>
      <body>
        <div id = "topPanel">
          <h3>
_HTML;

  if (isset($_SESSION['username'])){
    if ($_SESSION['check'] != hash('ripemd128', $_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'])){
      die (revalidate());
      destroy_data_and_session();
    }

    $username = $_SESSION['username'];
    echo "Welcome, $username";
  }
  else{
    die (revalidate());
    destroy_data_and_session();
  }

  echo<<<_HTML
          </h3>
        </div>
        <div>
          <form action = "physical.php" method = "post">
            <input id = "beginBtn" type = "submit" name = "begin" value = "Begin">
          </form>
        </div>
      </body>
    </html>
_HTML;
?>
