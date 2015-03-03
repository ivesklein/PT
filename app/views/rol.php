<html>
<head>
	<title>PT-UAI Seleccionar Rol</title>
	    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic" rel="stylesheet" type="text/css">
        <!-- needs images, font... therefore can not be part of ui.css -->
        <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="bower_components/weather-icons/css/weather-icons.min.css">
        <!-- end needs images -->

        <link rel="stylesheet" href="styles/main.css">
</head>
<body>
<div class="page page-table">

<div class="col-md-offset-3 col-md-6">

    <div class="panel panel-default">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Elegir Rol</strong></div>
    	<div class="panel-body">
	    	<form action="" method="post" class="form-horizontal ng-pristine ng-valid">
		        <h2>Ingresar como:</h2>
		        <?php 
		        $i = 0;
		        foreach ($roles as $rol => $name) { ?>
		        <div class="form-group">
		            <label for="" class="col-sm-4"></label>
		            <div class="col-sm-8">
		                <label class="ui-radio"><input name="rol" type="radio" value="<?=$rol?>" <?=($i==0?"checked":"")?>><span><?=$name?></span></label>
		            </div>
		        </div>
		        <?php 
		        $i++;
		    	} ?>

		        <div class="col-sm-offset-4 col-sm-8">
                    <button type="submit" class="btn btn-success">Continuar</button>
                </div>
			</form>
		</div>
    </div>

</div>

</div>
</body>
</html>