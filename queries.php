<?php
include 'connect.php'; // MySQL connection
include 'base.php'; // Base template

// Get form data
// define variables and set to empty values
$NUMQUERIES=9;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    for($i=0;$i<$NUMQUERIES-1;$i++) {
        if(isset($_POST["hiddenvalue" . $i])){
            $querynum=$i;
        }
    }
}



$result=mysqli_query($link,"SELECT COUNT(*) AS total FROM ".$_SESSION['table']); // number of rows
$data=mysqli_fetch_assoc($result);
$data['total']<100?$number=$data['total']:$number=100; // show at most 100 rows in the beginning

$query="SELECT * FROM ".$_SESSION['table']." LIMIT ".$number;



printf("
<h1><span class=\"glyphicon glyphicon-wrench\" aria-hidden=\"true\"></span> Predefined Queries</h1>
<form id=\"numForm\" action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"POST\">
    <div class='btn-group'>");
for($i=0;$i<$NUMQUERIES-1;$i++) {
    printf("   
        <input type=\"Submit\" name=\"hiddenvalue" . $i . "\" class=\"btn btn-primary\" value=\"Question " . ($i + 1) . "\"/>");
}
printf("
    </div>
</form>");
// form variables
$DESCRIPTIONS=array("Print the brand group names with the highest number of Belgian indicia publishers.",//a
    "Print the ids and names of publishers of Danish book series.",//b
    "Print the names of all Swiss series that have been published in magazines.",//c
    "Starting from 1990, print the number of issues published each year.",//d
    "Print the number of series for each indicia publisher whose name resembles DC comics.",//e
    "Print the titles of the 10 most reprinted stories.",//f
    "Print the artists that have scripted, drawn, and colored at least one of the stories they were involved in.",//g
    "Print all non-reprinted stories involving Batman as a non-featured character."//h
);
$QUERIES=array("SELECT BG.ID, BG.NAME,COUNT(*) "//a
    . "FROM BRAND_GROUP BG, PUBLISHER P, INDICIA_PUBLISHER IP, COUNTRY C "
    . "WHERE BG.PUBLISHER_ID=P.ID AND IP.PUBLISHER_ID=P.ID AND IP.COUNTRY_ID=C.ID AND C.NAME='Belgium' "
    . "GROUP BY BG.NAME ORDER BY COUNT(*) DESC LIMIT 10",
    "SELECT P.id, P.name "// b
    ."FROM PUBLISHER P, SERIES S, COUNTRY C "
    ."WHERE S.publisher_id = P.id AND S.COUNTRY_ID=C.ID AND C.NAME='Denmark'",
    "SELECT S.name "// c
    . "FROM SERIES_PUBLICATION_TYPE SPT, `SERIES` S, COUNTRY C "
    . "WHERE S.COUNTRY_ID=C.ID AND C.NAME='Switzerland' AND SPT.name='magazine'",
    "SELECT I.PUBLICATION_YEAR, Count(I.id) "// d
    ."FROM ISSUE I "
    ."WHERE I.PUBLICATION_YEAR >= 1990 "
    ."GROUP BY (I.year) "
    ."ORDER BY I.PUBLICATION_YEAR ASC ",
    "SELECT I.NAME, COUNT(S.ID) "// e
    ."FROM INDICIA_PUBLISHER I, SERIES S, PUBLISHER P "
    ."WHERE I.NAME LIKE('DC%') AND S.PUBLISHER_ID=P.ID AND I.PUBLISHER_ID=P.ID "
    ."GROUP BY I.NAME ORDER BY COUNT(S.ID) DESC ",
    "TODO fix query",//f
    "SELECT SCRIPTS.id, SCRIPTS.name, L.TYPE "//g
    ."FROM SCRIPTS, LETTERS L "
    ."WHERE SCRIPTS.name = L.TYPE "
    ."LIMIT 10 ",
    "TODO fix query"//h
);

if(isset($querynum)){
    $query=$QUERIES[$querynum];
    printf("
    <br>
    <div class=\"col-sm-10 well\" id='slidedown'>
        <blockquote>".$DESCRIPTIONS[$querynum]."</blockquote>.
        <code>".str_replace("%","%%",$query)."</code>
    </div>
      
    <br>");
    printf("\n<div class=\"row\">\n");
    // print table
    $result = mysqli_query($link, $query);
    if ( false===$result ) {
        printf("error: %s\n", mysqli_error($con));
    } else { // print the query results";
        printf("\t<table class=\"table table-hover\">
        <thead>
            <tr>");
        while($finfo=$result->fetch_field()){
            printf("
                <th>". $finfo->name. "</th>");
        }
        printf("
            </tr>
        </thead>
    <tbody>");
        while ($row = mysqli_fetch_array($result)) {
            printf("
        <tr>\n");
            for ($i = 0; $i < count($row)/2; $i++) {
                printf("\t\t\t<td>".max_length($row[$i], 50) . "</td>\n");
            }
        }
        printf("
        <tr>");
        printf("
    </tbody>
  </table>
</div>\n");
    }
}
function max_length($string,$num) {
    if(strlen($string)>$num)
        return substr($string,0,$num)."...";
    else
        return $string;
}
?>
</body>
</html>