<?php
include 'base.php'; // Base template
include 'connect.php'; // MySQL connection
// Get form data
// define variables and set to empty values
$table = "INDICIA_PUBLISHER";
$submitted = FALSE;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $table = $_POST["table"];
    if (isset($_POST["submitted"])) {
        $submitted = TRUE;
        $nCols = $_POST["numCols"];
        $cols = $_POST["row"];
        $colValue = array_values($cols);
        $colName = array_keys($cols);
        $id = $colValue[0];
        $query = "UPDATE " . $table . " SET ";
        for ($i = 1; $i < count($colName); $i++) {
            $query .= $colName[$i] . " = \"" . $colValue[$i] . (($i == count($colName) - 1) ? "\"" : "\",") . " ";
        }
        $query .= " WHERE ID=\"" . $colValue[0] . "\"";
        $result = mysqli_query($link, $query);
        if (false === $result) {
            printf("error: %s\n", mysqli_error($con));
        } else { // print the query results
            printf("<div class='row'>
    <div class=\"col-sm-10\">
        <div class=\"alert alert-success\">
            <strong>Success!</strong> Table was updated.
        </div>
    </div>
</div>\n");
        }
    }

}

switch($table){
    case "INDICIA_PUBLISHER":
        $input=array("number","text","number","number","number","number","number","text","text");
}

    $query = "SELECT * FROM " . $table . " WHERE id=\"" . $id . "\"";
    printf("<h1><i class=\"icon-flag\"></i> Modify tuple from " . ucwords(str_replace('_', ' ', strtolower($table))) . " <span class=\"badge\" data-toggle=\"tooltip\" title=\"\">" . $data['total'] . "</span></h1><br>");
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
            for ($i = 0; $i < count($row) / 2; $i++) {
                printf("\t\t\t<label class=\"control-label col-sm-2\" for=\"row[%s]\">%s</label>\n",$name[$i], $name[$i]);
                printf("\t\t\t<div class=\"col-sm-10\"><input class=\"form-control\" type=\"text\" ".(($i==0)?'readonly':'')." name=\"row[" . $name[$i] . "]\" value=\"" . $row[$i] . "\" id=\"row[" . $name[$i] . "]\"></div>\n");
            }
            $nCols=count($row)/2;
            printf("\t\t\t<br><br><input type='hidden' name='table' id='table' value='".$table."'>
            <br><br><input type='hidden' name='numCols' value='".$nCols."'><br><br>
            <input type=\"submit\" name='submitted' class=\"btn btn-primary\" value='Update'>
            <button type=\"button\" class=\"btn btn-info\" id=\"showSQL\">Toggle SQL command</button>
        </form>\n");
        }
    }
    printf("    </div>
    <div class=\"col-sm-3 well\" id=\"SQL-panel\"><h4>SQL command</h4><code><span id='sqlcode'></span></code></div>
</div>\n");

?>
</body>
</html>