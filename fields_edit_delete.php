<?php printf("\t\t\t<td>
    <form action='update.php' method='POST'>
        <input type='hidden' name='table' value='" .$_SESSION['table']."'>
        <input type='hidden' name='id' value='".$row[0]."'>
        <button type='Submit' class='btn btn-primary'>
            <span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit
        </button>
    </form></td>\n"
.       "\t\t\t<td>
    <form action='".$_SERVER["PHP_SELF"]."' method='POST'>
        <input type='hidden' name='table' value='".$_SESSION['table']."'>
        <input type='hidden' name='delete' value='TRUE'>
        <input type='hidden' name='id' value='".$row['id']."'>
        <button type='Submit' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to execute the following query? \\nDELETE FROM ".$_SESSION_TABLE." WHERE ID=".$row["id"]."');\">
        <span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span> Delete
        </button>
    </form>
</td>\n");