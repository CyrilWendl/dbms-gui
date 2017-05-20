<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Cyril Wendl">
    <meta name="description" content="EPFL Introduction to DBMS Interface">
    <title>DBMS: Home</title>
    <!--Font Awesome for icons-->
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <!--jQuery for animation-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <!--Bootstrap for menu-->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!--Google Fonts-->
    <link href="https://fonts.googleapis.com/css?family=Inconsolata" rel="stylesheet">
    <!--Custom CSS-->
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <!--Custom JavaScript-->
    <script src="./form_handling.js"></script>
    <script type="text/javascript">
        $('#numRows').select(function() {
            var model=$('#house_model').val();
            alert(model);
        });
    </script>
    <script>
       $(document).ready(function(){
           function showValues() {
               var fields = $( "#numForm" ).serializeArray();
               $("#sqlcode").empty();
               var query ="";
               var table = $("#table").attr("value");
               query += "UPDATE " + table + " SET ";
               jQuery.each( fields, function( i, field ) {
                   var fname=field.name;
                   var fstart=fname.indexOf("[")+1;
                   var fend = fname.indexOf("]")-fstart;
                   fname=fname.substr(fstart,fend);
                   if(field.name!="table" && field.name!="numCols"){
                       query += fname+"=\""+field.value+"\", ";
                   }
               });
               query=query.slice(0,-2);
               query += " WHERE ID=\""+fields[0].value+"\"";
               $("#sqlcode").append(query);
           }
           $( ":text, :radio" ).on("input", showValues );
           showValues();

           $("#showSQL").click(function(){
               $("#SQL-panel").slideToggle();
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
            <a class="navbar-brand" href="index.php" style="font-family: 'Inconsolata', monospace;">SQL Project</a>
        </div>

        <!-- Items -->
        <div class="collapse navbar-collapse" id="topNavBar">
            <ul class="nav navbar-nav">
                <li><a href="tables.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Tables</a></li>
                <li><a href="queries.php"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Predefined queries</a></li>

            </ul>

            <form class="navbar-form navbar-left" role="search" method="post" action="#">
                <div class="form-group">
                    <input type="text" class="form-control" name="search_term" value="">
                    <input type="hidden" name="table" value="#"/>
                </div>
                <button type="submit" class="btn btn-default">Search</button>
            </form>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="#"><a href="?Table=Languages"><span class="glyphicon glyphicon-flag" aria-hidden="true"></span> Languages</a></li>
                        <li class="todo"><a href="?Table=Stories"><span class="glyphicon glyphicon-th" aria-hidden="true"></span> Stories</a></li>
                        <li class="todo"><a href="#"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Publisher</a></li>
                    </ul>
                </li>
                <li class="#"><!--TODO implement-->
                    <a href="#">
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
