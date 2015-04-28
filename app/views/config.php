<html>
<head>
	<title>Config</title>
	    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic" rel="stylesheet" type="text/css">
        <!-- needs images, font... therefore can not be part of ui.css -->
        <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="bower_components/weather-icons/css/weather-icons.min.css">
        <!-- end needs images -->

        <link rel="stylesheet" href="styles/main.css">
</head>
<body>
<div class="page page-table">

    <div class="panel panel-default periodos">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Configuración</strong></div>
    	<div class="panel-body">
	    	<form action="" method="post" class="form-horizontal ng-pristine ng-valid">
	    		<?php if(false){//con pm?>
	    		<h2>Administrador ProcessMaker</h2>
		        <div class="form-group">
		            <label for="" class="col-sm-4">Usuario</label>
		            <div class="col-sm-8">
		                <input class="form-control" type="text" name="upm">
		            </div>
		        </div>
		        <div class="form-group">
		            <label for="" class="col-sm-4">Contraseña</label>
		            <div class="col-sm-8">
		                <input class="form-control" type="text" name="ppm">
		            </div>
		        </div>
		        <?php } ?>
		        <h2>Nuevo Usuario Secretario Académico</h2>
		        <div class="form-group">
		            <label for="" class="col-sm-4">Nombre</label>
		            <div class="col-sm-8">
		                <input class="form-control" type="text" name="nameu">
		            </div>
		        </div>
		       	<div class="form-group">
		            <label for="" class="col-sm-4">Apellido</label>
		            <div class="col-sm-8">
		                <input class="form-control" type="text" name="surnameu">
		            </div>
		        </div>
		        <div class="form-group">
		            <label for="" class="col-sm-4">Contraseña nueva</label>
		            <div class="col-sm-8">
		                <input class="form-control" type="password" name="passu">
		            </div>
		        </div>
		        <div class="form-group">
		            <label for="" class="col-sm-4">Mail UAI</label>
		            <div class="col-sm-8">
		                <input class="form-control" type="text" name="mailu">
		            </div>
		        </div>

		        <div class="col-sm-offset-4 col-sm-8">
                    <button type="submit" class="btn btn-success">Continuar</button>
                </div>
			</form>
		</div>
    </div>

</div>
</body>
</html>