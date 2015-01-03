<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo (!empty($page_title))?$page_title:"Compteur de diffusion"; ?></title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
<!-- Custom Styles -->
<link href="<?php echo $root_path; ?>css/styles.css" rel="stylesheet">

</head>

<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo $root_path; ?>">Animation Stats</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="<?php echo $root_path; ?>">Home</a></li>
            <!--
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
            -->
          </ul>
          	<?php
				
				if($template == 'home'){
			?>
            <form class="navbar-form navbar-right">
	            <input type="text" class="form-control" placeholder="Recherche" id="filter">
            </form>
            <?php
				}
				
			?>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    
    <div class="container">