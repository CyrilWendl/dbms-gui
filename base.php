<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Cyril Wendl">
    <meta name="description" content="EPFL Introduction to DBMS Interface">
    <title>DBMS: Home</title>
    <!--Font Awesome-->
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <!--jQuery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!--Bootstrap-->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <!--Bootstrap toggle library-->
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <!--Bootstrap select library-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

    <!--Google Fonts-->
    <link href="https://fonts.googleapis.com/css?family=Inconsolata" rel="stylesheet">
    <!--Custom CSS-->
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <!--Custom JavaScript-->
    <script type="text/javascript">
        $('#numRows').select(function() {
            var model=$('#house_model').val();
            alert(model);
        });
    </script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();

            function sql_update() {
                var fields = $( "#numForm" ).serializeArray();
                $("#sqlcode").empty();
                var query ="";
                var table = $("#table").attr("value");
                var query_fields = [];
                var query_values = [];
                query += "UPDATE " + table + " SET ";

                jQuery.each( fields, function( i, field ) {
                    var fname=field.name;
                    var fstart=fname.indexOf("[")+1;
                    var fend = fname.indexOf("]")-fstart;
                    fname=fname.substr(fstart,fend);
                    if(field.name!="table" && field.name!="numCols"){
                        query_fields.push(fname);
                        query_values.push(field.value);
                    }
                });
                for(var i=0;i<query_values.length;i++){
                    query+=query_fields[i];
                    query+=" = \"";
                    query+=query_values[i];
                    (i==query_values.length-1)?query+='\"':query+="\", ";
                }
                query += " WHERE ID=\""+fields[0].value+"\"";
                $("#sqlcode").append(query);
            }

            function sql_add() {
                var fields = $( "#numForm" ).serializeArray();
                $("#sqlcode_add").empty();
                var query ="";
                var table = $("#table").attr("value");
                var query_fields = [];
                var query_values = [];
                query += "INSERT INTO " + table + " (";

                jQuery.each( fields, function( i, field ) {
                    var fname=field.name;
                    var fstart=fname.indexOf("[")+1;
                    var fend = fname.indexOf("]")-fstart;
                    fname=fname.substr(fstart,fend);
                    if(field.name!="table" && field.name!="numCols"){
                        query_fields.push(fname);
                        if(field.value=="100"){
                            query_values.push("💯");
                        }else{
                            query_values.push(field.value);
                        }
                    }
                });
                for(var i=0;i<query_fields.length;i++){
                    query+=query_fields[i];
                    (i==query_values.length-1)?query+=') ':query+=", ";
                }
                query+="SET VALUES (";
                for(var i=0;i<query_values.length;i++){
                    query+='\'' + query_values[i];
                    (i==query_values.length-1)?query+='\')':query+="', ";
                }
                $("#sqlcode_add").append(query);
            }


            function query() {
                $("#sqlcode").empty();
                $("#input").empty();
                var query="";
                var queryterms_Fields = [];
                var queryterms_Values = [];
                var conjunctions = []; // array of ANDs and ORs
                var conditions = []; // array of conditinos (=, <, >)
                var table = $("#table option:selected").attr("value");
                query += "SELECT * FROM " + table;
                var fields = $( "#numForm" ).serializeArray();
                jQuery.each( fields, function( i, field ) {
                    var fname_before=field.name;
                    var fname_after=field.name;
                    var fstart=fname_before.indexOf("[")+1;
                    var fend = fname_before.indexOf("]")-fstart;
                    fname_before=fname_before.substr(0,fstart-1); // part before [
                    fname_after=fname_after.substr(fstart,fend); // part inside [ ]
                    if(fname_before=="fieldselect") {
                        // get selected value
                        var selected = $('.fieldselect\\[' + fname_after + "\\] option:selected").text();

                        // assign it to the input text = userfield[selected].
                        var element_to_update = '#userfield\\[' + fname_after + "\\]";
                        $(element_to_update).attr('name', 'userfield['+selected+']');
                        //check which condition was selected
                        var selected_cond = $('.cond_' + fname_after + " option:selected").text();
                        conditions.push(selected_cond);
                    }
                    if(field.name!="table" && field.name!="numRows" && fname_before!="fieldselect" && field.value!=''){
                        queryterms_Fields.push(fname_after);
                        if(field.value==100){
                            queryterms_Values.push("💯");
                        }else{
                            queryterms_Values.push(field.value);
                        }
                    }
                    //check if AND or OR was selected
                    if($('#andor_'+fname_after).exists()){
                        if($('#andor_'+fname_after).parent().hasClass("off")){
                            conjunctions.push(" OR ");
                        } else {
                            conjunctions.push(" AND ");
                        }
                    }
                    // get number of rows
                    if(field.name=="numRows"){
                        var numVal=$("#numRows").val();
                        $('#numRows').attr('value',numVal);
                    }

                });
                // add query terms and connectors (
                if (queryterms_Values.length>0){
                    query+= " WHERE "
                    for(i=0;i<queryterms_Values.length-1;i++){
                        query += queryterms_Fields[i] + " " + conditions[i] + " \"" + queryterms_Values[i] + "\" " + conjunctions[i];
                    }
                    var i= queryterms_Values.length-1;
                    query += queryterms_Fields[i] + " " + conditions[i] + " \"" + queryterms_Values[i]+ "\" ";
                }
                //query=query.slice(0,-4); // remove last AND
                query += " LIMIT " + $("#numRows").val();
                $("#sqlcode").append("<code>"+query+"</code>");
                $("#input").append("<input type='hidden' name='query' value='"+query+"'>");
            }
            jQuery.fn.exists = function(){ return this.length > 0; }

            $( ":text, :radio" ).on("input", sql_update );
            $( ":text, :radio" ).on("input", sql_add );
            $( ".sqlInput, select, option, button, #numRows" ).on("input", query );
            $("select,checkbox,.toggle").change(query);

            $("#showSQL").click(function(){
                $("#SQL-panel").slideToggle();
                $("#SQL-select").slideToggle();
            });
        });
    </script>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Header -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#topNavBar" aria-expanded="false" aria-controls="topNavBar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php" style="font-family: 'Inconsolata', monospace;"><b>SQL Project</b></a>
        </div>

        <!-- Items -->
        <div class="collapse navbar-collapse" id="topNavBar">
            <ul class="nav navbar-nav">
                <li><a href="tables.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Tables</a></li>
                <li><a href="search.php"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Query</a></li>
                <li><a href="queries.php"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Predefined queries</a></li>
                <form class="navbar-form navbar-left" role="search" action="search.php" method="post" >
                    <div class="form-group">
                        <input type="text" class="form-control" name="search_term" value="">
                    </div>
                    <button type="submit" class="btn btn-default">Search</button>

                </form>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">Current Table: <?php echo str_replace('_',' ',ucwords(strtolower($_SESSION['table']),"_"));?></a>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="add.php"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>
                </li>
                <li class="#">
                    <a class="nav-link disabled" href="#">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="row">
    <div class="col-lg-6">
        <blockquote>
            Learn the rules like a pro so you can break them like an artist.
            <footer>Pablo Picasso</footer>
        </blockquote>
    </div>
</div>
