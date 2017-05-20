<?php
printf("\t\t<form id=\"numForm\" action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"POST\">\n");
while ($finfo = $result->fetch_field()) {
$name[] = $finfo->name;
}
while ($row = mysqli_fetch_array($result)) {
for ($i = 0; $i < count($row) / 2; $i++) {
printf("\t\t\t<label for='%s'>%s</label>\n", $i, $name[$i]);
printf("\t\t\t<input type=\"hidden\" name=\"label_" . $i . "\" value=\"" . $name[$i] . "\"><br>\n");
printf("\t\t\t<input class=\"form-control\" type=\"text\" ".(($i==0)?'readonly':'')." name=\"" . $i . "\" value=\"" . $row[$i] . "\"><br>\n");
}
$nCols=count($row)/2;
printf("\t\t\t<input type='hidden' name='table' id='table' value='".$table."'>
<input type='hidden' name='numCols' value='".$nCols."'>
<div class=\"well\" id=\"SQL-panel\"></div>
<input type=\"submit\" name='submitted' class=\"btn btn-primary\" value='Update'>
<button type=\"button\" class=\"btn btn-info\" id=\"showSQL\">Show SQL command</button>
</form>\n");

?>