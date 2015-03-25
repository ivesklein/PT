<?php echo View::make('lti.header'); ?>

<style type="text/css">
	
	.disabled .panel-body{
		background: #f6f6f6;
	}

	.rechazado, .aceptado{
		display: none;
	}

	.panel-success .aceptado{
		display: block;
	}

	.panel-success .waiting{
		display: none;
	}

	.panel-danger .rechazado{
		display: block;
	}

	.panel-danger .waiting{
		display: none;
	}

	.disabled .waiting{
		display: none;
	}


</style>

<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default" style="margin-right: 7px;">
		        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Hoja de ruta</strong></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-6">
		<div class="panel" id="a1">
		        <div class="panel-heading"><strong><span class="glyphicon glyphicon-user"></span> Memorista <font id="a1name"></font></strong></div>
		        <div class="panel-body">
		        <p id="dec-a1"></p>
		        <span class="aceptado pull-right glyphicon glyphicon-ok-circle" style="color: green; font-size: 48px;"></span>
		        <span class="rechazado pull-right glyphicon glyphicon-remove-circle" style="color: red; font-size: 48px;"></span>
		        <span class="waiting pull-right glyphicon glyphicon-edit" style="font-size: 48px;"></span>
		        </div>
		</div>
	</div>
	<div class="col-xs-6">
		<div class="panel" style="margin-right: 7px;" id="a2">
		        <div class="panel-heading"><strong><span class="glyphicon glyphicon-user"></span> Memorista <font id="a2name"></font></strong></div>
		        <div class="panel-body">
				<p id="dec-a2"></p>
		        <span class="aceptado pull-right glyphicon glyphicon-ok-circle" style="color: green; font-size: 48px;"></span>
		        <span class="rechazado pull-right glyphicon glyphicon-remove-circle" style="color: red; font-size: 48px;"></span>
		        <span class="waiting pull-right glyphicon glyphicon-edit" style="font-size: 48px;"></span>
		        </div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="panel" style="margin-right: 7px;" id="prof">
		        <div class="panel-heading"><strong><span class="fa fa-graduation-cap"></span> Profesor Guía <font id="profename"></font></strong></div>
		        <div class="panel-body">
		        <p id="dec-prof"></p>
		        <span class="aceptado pull-right glyphicon glyphicon-ok-circle" style="color: green; font-size: 48px;"></span>
		        <span class="rechazado pull-right glyphicon glyphicon-remove-circle" style="color: red; font-size: 48px;"></span>
		        <span class="waiting pull-right glyphicon glyphicon-edit" style="font-size: 48px;"></span>
		        </div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="panel" style="margin-right: 7px;" id="revisor">
		        <div class="panel-heading"><strong><span class="glyphicon glyphicon-indent-left"></span> Revisor Aleatorio</strong></div>
		        <div class="panel-body">
		        <p id="dec-aleatorio"></p>
		        <span class="aceptado pull-right glyphicon glyphicon-ok-circle" style="color: green; font-size: 48px;"></span>
		        <span class="rechazado pull-right glyphicon glyphicon-remove-circle" style="color: red; font-size: 48px;"></span>
		        <span class="waiting pull-right glyphicon glyphicon-edit" style="font-size: 48px;"></span>
		        </div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-6">
		<div class="panel" id="secre1">
		        <div class="panel-heading"><strong><span class="fa fa-gavel"></span> Secretaría Académica</strong></div>
		        <div class="panel-body">
		        <p id="dec-secre1"></p>
		        <span class="aceptado pull-right glyphicon glyphicon-ok-circle" style="color: green; font-size: 48px;"></span>
		        <span class="rechazado pull-right glyphicon glyphicon-remove-circle" style="color: red; font-size: 48px;"></span>
		        <span class="waiting pull-right glyphicon glyphicon-edit" style="font-size: 48px;"></span>
		        </div>
		</div>
	</div>
	<div class="col-xs-6">
		<div class="panel" style="margin-right: 7px;" id="secre2">
		        <div class="panel-heading"><strong><span class="fa fa-gavel"></span> Secretaría Académica</strong></div>
		        <div class="panel-body">
		        <p id="dec-secre2"></p>
		        <span class="aceptado pull-right glyphicon glyphicon-ok-circle" style="color: green; font-size: 48px;"></span>
		        <span class="rechazado pull-right glyphicon glyphicon-remove-circle" style="color: red; font-size: 48px;"></span>
		        <span class="waiting pull-right glyphicon glyphicon-edit" style="font-size: 48px;"></span>
		        </div>
		</div>
	</div>
</div>


<script src="../scripts/vendor.js"></script>
<script src="../scripts/ui.js"></script>
<script src="../scripts/ajx.js"></script>
<script type="text/javascript">
	<?php 

	function color ($n) {
		switch ($n) {
			case 1:
				return "panel-default";
				break;
			case 2:
				return "panel-success";
				break;
			case -1:
				return "panel-danger";
				break;
			case 0:
				return "panel-default disabled";
				break;

			default:
				return "panel-default disabled";
				break;
		}
	}

	if(isset($alumno1)){
		if(isset($alumno1["name"])){
			echo "$('#a1name').html('".$alumno1["name"]."');";
		}
		echo "$('#a1').addClass('".color($alumno1["status"])."');";
		echo "$('#dec-a1').html('".$alumno1["declaracion"]."');";
	}
	if(isset($alumno2)){
		if(isset($alumno2["name"])){
			echo "$('#a2name').html('".$alumno2["name"]."');";
		}
		echo "$('#a2').addClass('".color($alumno2["status"])."');";
		echo "$('#dec-a2').html('".$alumno2["declaracion"]."');";
	}
	if(isset($profesor)){
		if(isset($profesor["name"])){
			echo "$('#profename').html('".$profesor["name"]."');";
		}
		echo "$('#prof').addClass('".color($profesor["status"])."');";
		echo "$('#dec-prof').html('".$profesor["declaracion"]."');";
	}
	if(isset($aleatorio)){
		if(isset($aleatorio["name"])){
			echo "$('#revisorname').html('".$aleatorio["name"]."');";
		}
		echo "$('#revisor').addClass('".color($aleatorio["status"])."');";
		echo "$('#dec-aleatorio').html('".$aleatorio["declaracion"]."');";
	}
	if(isset($secretaria1)){
		if(isset($secretaria1["name"])){
			echo "$('#secrename1').html('".$secretaria1["name"]."');";
		}
		echo "$('#secre1').addClass('".color($secretaria1["status"])."');";
		echo "$('#dec-secre1').html('".$secretaria1["declaracion"]."');";
	}
	if(isset($secretaria2)){
		if(isset($secretaria2["name"])){
			echo "$('#secrename2').html('".$secretaria2["name"]."');";
		}
		echo "$('#secre2').addClass('".color($secretaria2["status"])."');";
		echo "$('#dec-secre2').html('".$secretaria2["declaracion"]."');";
	}

	?>
</script>

<?php echo View::make('lti.footer'); ?>