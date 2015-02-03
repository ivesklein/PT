<html>
<head>
	<title>Message</title>
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
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Message</strong></div>
    	<div class="panel-body">
				<?php
				if (isset($ok)) {
					if($ok==true){
					?>
			<div class="callout callout-success">
                <h4>Ok</h4>
                <p><a href="login" class="btn btn-success">Ir al inicio</a></p>
            </div>
					<?php
					}else{

					?>
			<div class="callout callout-danger">
                <h4>Error</h4>
                <p><?php if(isset($message)){echo $message;} ?></p>
                <p><a href="login" class="btn btn-danger">Ir al inicio</a></p>
            </div>
					<?php
					}
				}else{
					if(isset($message)){echo $message;}
				}
				?>
		</div>
    </div>

</div>
</body>
</html>