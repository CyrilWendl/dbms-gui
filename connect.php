<?php
// Establish connection
session_start();
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
$tables_label=array("Story","Language","Issues", "Indicia Publisher","Publisher","Brand Group");

for($i=0;$i<count($tables_label);$i++) {
    $tables[$i]=str_replace(' ','_',strtoupper($tables_label[$i]));
}

if(!isset($number)){ // row number
    $number=10;
}

if(!isset($_SESSION['table'])){ //table name
    $_SESSION['table']=$tables[0];
}