<?php
session_start();
//session_unset();
include 'base.php'; // Base template
include 'connect.php'; // MySQL connection

// Get form data
// define variables and set to empty values
$submitted = FALSE;
$tables_label=array("Stories","Language","Issue", "Indicia Publisher","Letters","Pencils","Publisher","Brand Group","Characters","Colors");

for($i=0;$i<count($tables_label);$i++) {
    $tables[$i]=str_replace(' ','_',strtoupper($tables_label[$i]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {// from form on tables.php
    $_SESSION['table'] = $_POST["table"];
    if (isset($_POST["submitted"])) {
        $submitted = TRUE;
        $nCols = $_POST["numCols"];
        $cols = $_POST["row"];
        $colValue = array_values($cols);
        $colName = array_keys($cols);
        $id = $colValue[0];
        // syntax: INSERT INTO table_name (column1, column2, column3, ...)
        //         VALUES (value1, value2, value3, ...);
        $query = "INSERT INTO " . $_SESSION['table'] . " (";
        for ($i = 0; $i < count($colName); $i++) {
            $query .= $colName[$i].(($i == count($colName) - 1) ? "" : ",");
        }
        $query.= ")\n VALUES (";
        for ($i = 0; $i < count($colName); $i++) {
            if($colValue!=''){
                $query .= "'".$colValue[$i]."'";
            }else{
                $query .= '\'\'';
            }
            $query .= (($i == count($colName) - 1) ? "" : ",");
        }
        echo $query .=")";

        $result = mysqli_query($link, $query);
        if (false === $result) {
            printf("error: %s\n", mysqli_error($con));
        } else { // print the query results
            printf("
        <div class='row'>
            <div class=\"col-sm-10\">
                <div class=\"alert alert-success\">
                    <strong>Success!</strong> Table was updated.
                </div>
            </div>
        </div>\n");
        }
    }
}

// Limit shown number of rows
printf("<!--Limit shown number of rows-->
    <div class='row'>
        <div class='col-sm-6'>
            <form class='form-horizontal' action='".  $_SERVER["PHP_SELF"] ."' method='POST'>
                <div class='form-group'>
                    <label class='control-label col-sm-4' for='form-control'>Table:</label>
                    <div class=\"col-sm-8\">
                        <select class='form-control' id='form-control' name='table' onchange='this.form.submit()'>");
for($i=0;$i<count($tables);$i++){
    printf("
                            <option value='".$tables[$i]."' ".(($_SESSION['table']==$tables[$i])?'selected="selected"':"").">".$tables_label[$i]."</option>");
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
    </div>
    
<br>");

$query = "SELECT * FROM " . $_SESSION['table'] ." LIMIT 1";
printf("<h1><span class=\"glyphicon glyphicon-plus\" aria-hidden=\"true\"></span> Insert tuple into " . ucwords(str_replace('_', ' ', strtolower($_SESSION['table']))) . " <span class=\"badge\" data-toggle=\"tooltip\" title=\"\">" . $data['total'] . "</span></h1><br>");
printf("
<div class=\"row\">
    <div class='col-sm-8'>\n");
$result = mysqli_query($link, $query);
if (false === $result) {
    printf("error: %s\n", mysqli_error($con));
} else { // print the query results
    // TODO externalize form
    printf("\t\t<form class=\"form-horizontal\" id=\"numForm\" action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"POST\">\n");
    while ($finfo = $result->fetch_field()) {
        $name[] = $finfo->name;
    }
    while ($row = mysqli_fetch_array($result)) {
        for ($i = 1; $i < count($row) / 2; $i++) {
            printf("\t\t\t<label class=\"control-label col-sm-2\" for=\"row[%s]\">%s</label>\n",$name[$i], $name[$i]);
            printf("\t\t\t<div class=\"col-sm-10\"><input class=\"form-control\" type=\"text\"  name=\"row[" . $name[$i] . "]\" id=\"row[" . $name[$i] . "]\"></div>\n");
        }
        $nCols=count($row)/2;
        printf("\t\t\t<br><br><input type='hidden' name='table' id='table' value='".$_SESSION['table']."'>
            <br><br><input type='hidden' name='numCols' value='".$nCols."'><br><br>
            <input type=\"submit\" name='submitted' class=\"btn btn-primary\" value='Update'>
            <button type=\"button\" class=\"btn btn-info\" id=\"showSQL\">Toggle SQL command</button>
        </form>\n");
    }
}
printf("</div>
    <div class=\"col-sm-3 well\" id=\"SQL-panel\">
        <h4>SQL command</h4>
        <code><span id='sqlcode_add'></span></code>
    </div>
</div>\n");

?>
</body>
</html>
