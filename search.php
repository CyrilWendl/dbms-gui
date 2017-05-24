<?php
session_start();
//session_unset();
include 'base.php'; // Base template
include 'connect.php'; // MySQL connection

// Get form data
// define variables and set to empty values
$number = 100;
if(!isset($_SESSION['table'])){
    $_SESSION['table'] = "INDICIA_PUBLISHER";
}
$_SESSION['queryfields'] = ((isset($_SESSION['queryfields'])) ? $_SESSION['queryfields'] : 3);

$query_count="SELECT COUNT(*) AS total FROM ".$_SESSION['table'];
$num_rows=mysqli_query($link,$query_count); // number of rows
$data=mysqli_fetch_assoc($num_rows);
$data['total']<100?$number=$data['total']:$number=100; // show at most 100 rows in the beginning

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['add'])){
        $_SESSION['queryfields']++;
    }
    if(isset($_POST['minus'])){
        $_SESSION['queryfields']--;
    }

    if(isset($_POST['query'])){
        $query=$_POST['query'];
    }

    if(isset($_POST["numRows"]))
        $number = $_POST["numRows"];
    if(isset($_POST["table"]))
        $_SESSION['table']= $_POST["table"];
    if(isset($_POST["delete"])){
        $query="DELETE FROM ".$_SESSION['table']." WHERE ID=\"".$_POST["id"]."\"";
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
$query_count="SELECT COUNT(*) AS total FROM ".$_SESSION['table'];
$num_rows=mysqli_query($link,$query_count); // number of rows
$data=mysqli_fetch_assoc($num_rows);

if(!isset($query))
    $query="SELECT * FROM ".$_SESSION['table']." LIMIT ".$number;

$tables_label=array("Stories","Language","Issue", "Indicia Publisher","Letters","Pencils","Publisher","Brand Group","Characters","Colors");

for($i=0;$i<count($tables_label);$i++) {
    $tables[$i]=str_replace(' ','_',strtoupper($tables_label[$i]));
}


?>
<form id="numForm" class="form-horizontal" action="<?php $_SERVER["PHP_SELF"]?>" method="POST">
    <div class="row">
        <div class="form-group has-feedback">
            <h3><label class="col-sm-1 control-label">
                    Table</label></h3>
            <div class="col-sm-offset-1 col-sm-4">
                <select class='form-control' onchange="this.form.submit()" id='table' name='table'>
                    <?php
                    for($i=0;$i<count($tables);$i++){
                        printf("
                            <option value='".$tables[$i]."' ".(($_SESSION['table']==$tables[$i])?'selected="selected"':"").">".$tables_label[$i]."</option>");
                    }?>
                </select>
            </div>
            <label class='control-label col-sm-2' for='numRows'>Number of rows to show:</label>
            <div class="col-sm-2">
                <input type='number' class='form-control'  name='numRows' id='numRows' value='100' max='". $data["total"] ."'>
            </div>

        </div>
    </div>
    <hr>
    <?php
    for($i=0;$i<$_SESSION['queryfields'];$i++){
        printf("<div class=\"row\">
        <div class=\"form-group has-feedback\">".
            (($i>=1)?'<div class="col-sm-1">
<div class="container">
<div class="col-sm-1">
                <input type="checkbox" class="form-control text-center" checked data-toggle="toggle" data-on="AND" data-off="OR" id="andor_'.($i-1).'">
            </div></div></div>':'')."
            <label class=\"".(($i>=1)?'col-sm-1':'col-sm-2')." control-label\">
                Field</label>
            <div class=\"col-sm-4\">
                <select class='form-control fieldselect[".$i."]' name='fieldselect[".$i."]'>");
        $num_rows = mysqli_query($link, $query);
        while ($finfo = $num_rows->fetch_field()) {
            printf("<option value=\"" . $finfo->name . "\">" . $finfo->name . "</option>");
        }
        printf("</select>
            </div>
            <div class=\"col-sm-4 controls form-inline\">
                
                <div class=\"input-group\">
                    <div class=\"input-group-btn\">
                        <select class='selectpicker cond_".$i."' data-width=\"fit\" data-style=\"btn-primary\" >
                            <option>=</option>
                            <option>></option>
                            <option><</option>
                        </select>
                  </div>
                  <input type=\"text\" name=\"userfield[".$i."]\" class=\"form-control sqlInput\" id=\"userfield[".$i."]\">
            </div>
            </div>
            
        </div>
    </div>
    <hr>");
    }
    ?>
</form>
<div class="row">
    <div class="col-sm-offset-8 col-sm-4">
        <?php
        $ncols=mysqli_num_fields(mysqli_query($link,"SELECT * FROM ". $_SESSION['table'] ." LIMIT 10"));
        if($ncols>$_SESSION['queryfields']){
            printf("
        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">
            <button type=\"submit\" class=\"btn btn-default\" name=\"add\" value=\"add\"><span class=\"glyphicon glyphicon-plus\" aria-hidden=\"true\"></span></button>
        </form>");
        }
        if($_SESSION['queryfields']>1){
            printf("
        <form action=\"". $_SERVER['PHP_SELF'] ."\" method=\"POST\">
            <button type=\"submit\" class=\"btn btn-default\" name=\"minus\" value=\"minus\"><span class=\"glyphicon glyphicon-minus\" aria-hidden=\"true\"></span></button>
        </form>");
        }
        ?>
    </div>
</div>

<br>
<div class="col-sm-2">
    <button type="button" class="btn btn-primary" id="showSQL">Generate SQL code</button>
</div>
<div class="col-sm-3 well" id="SQL-select">
    <h4>Query:</h4>
    <span id='sqlcode'></span>
    <form action="<?php $_SERVER['PHP_SELF']?>" method="post">
        <span id='input'><code><?php echo $query?></code></span>
        <br>
        <button class='btn btn-default' type=submit>
            <span class='glyphicon glyphicon-search' aria-hidden='true'></span> Search
        </button>
    </form>
</div>
<?php
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
        <th>". $finfo->name. "</th>
");
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