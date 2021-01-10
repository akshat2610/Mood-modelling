<?php //done.php
  session_start();
  if (!isset($_SESSION['initiated'])){
    session_regenerate_id();
    $_SESSION['initiated'] = 1;
  }

  echo <<<_HTML
    <html>
      <head>
        <title> Mood Modelling </title>
        <link rel = "stylesheet" type = "text/css" href = "report.css">
       </head>
      <body>
        <div id = "topPanel">
          <h3> Here are your past entries </h3>
        </div>
        <div>
          <form action = "index.php" method = "post">
            <input type = "submit" name = "submit" value = "Sign out">
          </form>
        <div>
        <div id = "report">
_HTML;
  require_once 'login.php';
  require_once 'functions.php';
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die (mysql_fatal_error());

  if (isset($_SESSION['username'])){
    //Physical environment entries
    $select_stmt = $conn->prepare('SELECT * FROM physical WHERE username = ?');
    $select_stmt->bind_param('s', $_SESSION['username']);
    $select_stmt->execute();
    $result = $select_stmt->get_result();
    $select_stmt->close();

    echo "<br class = 'heading'>|LOCATION|\t|WEATHER|\t|TEMPERATURE|</br>";
    for ($j = 0; $j < $result->num_rows; $j++){
      echo "<br>|";
      $result->data_seek($j);
      $row_data = $result->fetch_array(MYSQLI_NUM);
      echo $row_data[1];
      echo "|\t|";
      echo $row_data[2];
      echo "|\t|";
      echo $row_data[3];
      echo "|</br>";
    }
    $result->close();

    //social environment entries
    $select_stmt = $conn->prepare('SELECT * FROM social WHERE username = ?');
    $select_stmt->bind_param('s', $_SESSION['username']);
    $select_stmt->execute();
    $result = $select_stmt->get_result();
    $select_stmt->close();

    echo "<br class = 'heading'>|SOCIAL ENVIRONMENT|</br>";
    for ($j = 0; $j < $result->num_rows; $j++){
      echo "<br>|";
      $result->data_seek($j);
      $row_data = $result->fetch_array(MYSQLI_NUM);
      echo $row_data[1];
      echo "|</br>";
    }
    $result->close();

    //diet and sleep entries
    $select_stmt = $conn->prepare('SELECT * FROM diet_sleep WHERE username = ?');
    $select_stmt->bind_param('s', $_SESSION['username']);
    $select_stmt->execute();
    $result = $select_stmt->get_result();
    $select_stmt->close();

    echo "<br class = 'heading'>|DIET TYPE|\t|DIET QUALITY|\t|SLEEP IN HRS|</br>";
    for ($j = 0; $j < $result->num_rows; $j++){
      echo "<br>|";
      $result->data_seek($j);
      $row_data = $result->fetch_array(MYSQLI_NUM);
      echo $row_data[1];
      echo "|\t|";
      echo $row_data[2];
      echo "|\t|";
      echo $row_data[3];
      echo "|</br>";
    }
    $result->close();

    //feeling entries
    $select_stmt = $conn->prepare('SELECT * FROM feeling WHERE username = ?');
    $select_stmt->bind_param('s', $_SESSION['username']);
    $select_stmt->execute();
    $result = $select_stmt->get_result();
    $select_stmt->close();

    echo "<br class = 'heading'>|FEELING|</br>";
    for ($j = 0; $j < $result->num_rows; $j++){
      echo "<br>|";
      $result->data_seek($j);
      $row_data = $result->fetch_array(MYSQLI_NUM);
      echo $row_data[1];
      echo "|</br>";
    }
    $result->close();
  }
  else{
    destroy_data_and_session();
    echo "<p><a href = 'index.php'>Click here to authenticate yourself</a>";
  }

  $conn->close();
  echo "</div></body></html>";
?>
