<?php
// Establish connection
$dbpass="ahr5moLzTE0(r6A";
$dbhost="mysql-cdm-51";
$dbuser="mir";
$dbname="mir";
$link = @mysqli_connect($dbhost,$dbuser,$dbpass) or die ("<div class=\"alert alert-danger\">
  <strong>Could not connect to MySQL!</strong> Consider connecting to VPN. 
</div>");
@mysqli_select_db($link, $dbname) or die ("<div class=\"alert alert-danger\">
  <strong>No such database</strong> 
</div>");