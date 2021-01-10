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
        <link rel = "stylesheet" type = "text/css" href = "done.css">
       </head>
      <body>
        <div id = "topPanel">
          <h3> Congratulations, you finished your exercise for today </h3>
        </div>
        <div id = "bottomPanel">
          <form action = "index.php" method = "post">
            <input type = "submit" name = "submit" value = "Sign out">
          </form>
          <form action = 'report.php' method = "post">
            <input type = "submit" name = "report" value = "view past entries">
          </form>
        </div>
      </body>
_HTML;


  echo "</html>";
