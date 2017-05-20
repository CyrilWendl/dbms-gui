<?php
include 'base.php'; // Base template
include 'connect.php'; // MySQL connection
// Get form data
// define variables and set to empty values
$number = 100;
$table = "INDICIA_PUBLISHER";

$query_count="SELECT COUNT(*) AS total FROM ".$table;
$num_rows=mysqli_query($link,$query_count); // number of rows
$data=mysqli_fetch_assoc($num_rows);
$data['total']<100?$number=$data['total']:$number=100; // show at most 100 rows in the beginning

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["numRows"]))
        $number = $_POST["numRows"];
        $table= $_POST["table"];
    if(isset($_POST["delete"])){
        $query="DELETE FROM ".$table." WHERE ID=\"".$_POST["id"]."\"";
        $result= mysqli_query($link, $query);
        if ( false===$result) {
            printf("error: %s\n", mysqli_error($con));
        } else { // print the query results
            printf("<div class='row'><div class=\"col-sm-10\">
    <div class=\"alert alert-success\">
        <strong>Success!</strong> Table was updated.
    </div>
</div></div>\n");
        }
    }
}
$query_count="SELECT COUNT(*) AS total FROM ".$table;
$num_rows=mysqli_query($link,$query_count); // number of rows
$data=mysqli_fetch_assoc($num_rows);

$query="SELECT * FROM ".$table." LIMIT ".$number;

$tables_label=array("Stories","Language","Issue", "Indicia Publisher","Letters","Pencils","Publisher","Brand Group","Characters","Colors");

for($i=0;$i<count($tables_label);$i++) {
    $tables[$i]=str_replace(' ','_',strtoupper($tables_label[$i]));
}


// Limit shown number of rows
printf("<!--Limit shown number of rows-->
<h1><span class=\"glyphicon glyphicon-th-list\" aria-hidden=\"true\"></span> ".ucwords(str_replace('_',' ',strtolower($table)))." <span class='badge' data-toggle='tooltip' title=''>".$data['total']."</span></h1>
    <div class='row'>
        <div class='col-sm-6'>
            <form class='form-horizontal' id='numForm' action='".  $_SERVER["PHP_SELF"] ."' method='POST'>
                <div class='form-group'>
                    <label class='control-label col-sm-4' for='numRows'>Number of rows to show:</label>
                    <div class=\"col-sm-8\">
                        <input type='number' class='form-control'  name='numRows' id='numRows' value='" . $number  ."' max='". $data["total"] ."'>
                    </div>
                </div>
                <div class='form-group'>
                    <label class='control-label col-sm-4' for='form-control'>Table:</label>
                    <div class=\"col-sm-8\">
                        <select class='form-control' id='form-control' name='table'>");
                        for($i=0;$i<count($tables);$i++){
                            printf("
                            <option value='".$tables[$i]."' ".(($table==$tables[$i])?'selected="selected"':"").">".$tables_label[$i]."</option>");
                        }
                        printf("
                        </select>
                    </div>
                </div>
                <div class=\"form-group\">        
                    <div class=\"col-sm-offset-4 col-sm-8\">
                        <button type='submit' class='btn btn-default'>Update</button>
                    </div>
                </div>
            </form>
        </div>
        <div class='col-sm-4 well'>
            <h4>SQL code:</h4>
            <code class='code'>SELECT * FROM ".$table. " LIMIT ".$number."</code>
        </div>
    </div>
    
<br>");



printf("<div class='row'>\n");

function max_length($string,$num) {
    if(strlen($string)>$num)
        return substr($string,0,$num)."...";
    else
        return $string;
}

$num_rows = mysqli_query($link, $query);
if ( false===$num_rows ) {
    printf("error: %s\n", mysqli_error($con));
} else { // print the query results
    printf("
<table class='table table-hover'>\n"
        ."<thead>\n"
        ."<tr>");

    while ($finfo = $num_rows->fetch_field()) {
        printf("
        <th>". $finfo->name. "</th>");
    }
    printf("
        <th>Edit</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>");
    while ($row = mysqli_fetch_array($num_rows)) {
        printf("
        <tr>\n");
        for ($i = 0; $i < count($row)/2; $i++) {
            printf("\t\t\t<td>".max_length($row[$i], 50) . "</td>\n");
        }
        require 'fields_edit_delete.php';
    }
    printf("
        </tr>");
    printf("
        </tbody>
  </table></div>");
}
?>
</body>
</html>