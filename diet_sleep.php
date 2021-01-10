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
        <link rel = "stylesheet" type = "text/css" href = "diet_sleep.css">
       </head>
      <body>
        <div id = "topPanel">
          <h3> Record your diet and sleep </h3>
        </div>
        <div id = "middlePanel">
          <div id = "formArea">
            <form action = "diet_sleep.php" method = "post">
              <div class = "diet">
                <button class = "square" name = "veg">Veg</button>
                <button class = "square" name = "non veg">Non veg</button>
                <button class = "square" name = "healthy">Healthy</button>
                <button class = "square" name = "junk">Junk</button>
              </div>
              <div class = "sleep">
                <button class = "square" name = "1">0-3</button>
                <button class = "square" name = "2">3-6</button>
                <button class = "square" name = '3'>6-9</button>
                <button class = "square" name = "4">9-12</button>
              </div>
              <input type = "submit" name = "submit" value = "submit">
            </form>
          </div>
        </div>
        <div id = "bottomPanel">
          <form action = "social.php" method = "post">
            <input type = "submit" name = "submit" value = "Previous">
          </form>
          <form action = "feeling.php" method = "post">
            <input type = "submit" name = "submit" value = "Next">
          </form>
        </div>
      </body>
_HTML;
  require_once 'login.php';
  require_once 'functions.php';
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die (mysql_fatal_error());

  if (isset($_POST['veg'])) $_SESSION['diet_type'] = 'veg';
  if (isset($_POST['non veg'])) $_SESSION['diet_type'] = 'non veg';
  if (isset($_POST['healthy'])) $_SESSION['diet_quality'] = 'healthy';
  if (isset($_POST['junk'])) $_SESSION['diet_quality'] = 'junk';

  if (isset($_POST['1'])) $_SESSION['sleep'] = '0 - 3';
  if (isset($_POST['2'])) $_SESSION['sleep'] = '3 - 6';
  if (isset($_POST['3'])) $_SESSION['sleep'] = '6 - 9';
  if (isset($_POST['4'])) $_SESSION['sleep'] = '9 - 12';

  if (isset($_POST['submit'])){
    $insert_physical = $conn->prepare('INSERT INTO diet_sleep VALUES(?, ?, ?, ?)');
    $insert_physical->bind_param('ssss', $_SESSION['username'], $_SESSION['diet_type'], $_SESSION['diet_quality'], $_SESSION['sleep']);
    $insert_physical->execute();
    $insert_physical->close();
    unset($_POST);
  }

  $conn->close();
  echo "</html>";
?>
