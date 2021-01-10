<?php //physical.php
  session_start();
  if (!isset($_SESSION['initiated'])){
    session_regenerate_id();
    $_SESSION['initiated'] = 1;
  }

  echo <<<_HTML
    <html>
      <head>
        <title> Mood Modelling </title>
        <link rel = "stylesheet" type = "text/css" href = "social.css">
      </head>
      <body>
        <div id = "topPanel">
          <h3> Record your social environment </h3>
        </div>
        <div id = "middlePanel">
          <div id = "formArea">
            <form action = "social.php" method = "post">
              <div class = "social">
                <button class = "square" name = "alone">Alone</button>
                <button class = "square" name = "partner">Better half</button>
                <button class = "square" name = "friends">Friends</button>
                <button class = "square" name = "party">Party</button>
              </div>
              <input type = "submit" name = "submit" value = "submit">
            </form>
          </div>
        </div>
        <div id = "bottomPanel">
          <form action = "physical.php" method = "post">
            <input type = "submit" name = "submit" value = "Previous">
          </form>
          <form action = "diet_sleep.php" method = "post">
            <input type = "submit" name = "submit" value = "Next">
          </form>
        </div>
      </body>
_HTML;
  require_once 'login.php';
  require_once 'functions.php';
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die (mysql_fatal_error());


  //find he social environment the user selected
  if (isset($_POST['alone'])) $_SESSION['social'] = 'alone';
  if (isset($_POST['partner'])) $_SESSION['social'] = 'partner';
  if (isset($_POST['friends'])) $_SESSION['social'] = 'friends';
  if (isset($_POST['party'])) $_SESSION['social'] = 'party';


  if (isset($_POST['submit'])){
    $insert_physical = $conn->prepare('INSERT INTO social VALUES(?, ?)');
    $insert_physical->bind_param('ss', $_SESSION['username'], $_SESSION['social']);
    $insert_physical->execute();
    $insert_physical->close();
    unset($_POST);
  }

  $conn->close();
  echo "</html>";
?>
