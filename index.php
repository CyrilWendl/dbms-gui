<?php
include 'connect.php'; // MySQL connection
include 'base.php'; // Base template
session_unset();
session_destroy();

printf("<div class=\"container\">
    <h2>Tables</h2>");
for($i=0;$i<count($tables);$i++){
    $query_count="SELECT COUNT(*) AS total FROM ".$tables[$i];
    $num_rows=mysqli_query($link,$query_count); // number of rows
    $data=mysqli_fetch_assoc($num_rows);
    $nRows[$i]=$data['total']."<br>";
    printf("<form action=\"tables.php\" method='POST'>
        <input class='hidden' name='table' value='".$tables[$i]."'>
        <button type=\"submit\" class=\"btn btn - primary\">
            <span class=\"glyphicon glyphicon-th-list\" aria-hidden=\"true\"></span> ".$tables_label[$i]." <span class=\"badge\">".$nRows[$i]."</span >
        </button >
</form ><br >\n");
}



?>

</div>
</body>
</html>