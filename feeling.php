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
        <link rel = "stylesheet" type = "text/css" href = "feeling.css">
       </head>
      <body>
        <div id = "topPanel">
          <h3> Record how you feel </h3>
        </div>
        <div id = "middlePanel">
          <div id = "formArea">
            <form action = "feeling.php" method = "post">
              <div class = "feeling">
                <button class = "square" name = "happy">Happy</button>
                <button class = "square" name = "sad">Sad</button>
                <button class = "square" name = "angry">Angry</button>
                <button class = "square" name = "depressed">Depressed</button>
              </div>
              <input type = "submit" name = "submit" value = "submit">
            </form>
          </div>
        </div>
        <div id = "bottomPanel">
          <form action = "diet_sleep.php" method = "post">
            <input type = "submit" name = "submit" value = "Previous">
          </form>
          <form action = "done.php" method = "post">
            <input type = "submit" name = "submit" value = "Next">
          </form>
        </div>
      </body>
_HTML;
  require_once 'login.php';
  require_once 'functions.php';
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die (mysql_fatal_error());

  //find the feeling that the user selected
  if (isset($_POST['happy'])) $_SESSION['feeling'] = 'happy';
  if (isset($_POST['sad'])) $_SESSION['feeling'] = 'sad';
  if (isset($_POST['angry'])) $_SESSION['feeling'] = 'angry';
  if (isset($_POST['depressed'])) $_SESSION['feeling'] = 'depressed';

  if (isset($_POST['submit'])){
    $insert_physical = $conn->prepare('INSERT INTO feeling VALUES(?, ?)');
    $insert_physical->bind_param('ss', $_SESSION['username'], $_SESSION['feeling']);
    $insert_physical->execute();
    $insert_physical->close();
    unset($_POST);
  }

  $conn->close();
  echo "</html>";
?>
