<?php echo View::make('lti.header'); ?>


<div class="panel panel-default" style="margin-right: 7px;">
        <div class="panel-heading notas"><strong><span class="glyphicon glyphicon-th"></span> Notas</strong></div>
        <div class="panel-body row notas">
        	<div class="col-xs-3">
	        	<div class="panel panel-info">
				        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Entrega 1</strong></div>
				        <div class="panel-body">
				        	<div class="alert alert-success text-center"><h3>6,1</h3></div>
				        	<div n="1" class="btn btn-default feedback">Ver feedback</div>
				        </div>
				</div>
			</div>
        	<div class="col-xs-3">
	        	<div class="panel panel-info">
				        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Entrega 2</strong></div>
				        <div class="panel-body">
				        	<div class="alert alert-danger text-center"><h3>3,3</h3></div>
				        	<div n="2" class="btn btn-default feedback">Ver feedback</div>
				        </div>
				</div>
			</div>
        	<div class="col-xs-3">
	        	<div class="panel panel-info">
				        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Entrega 3</strong></div>
				        <div class="panel-body">
				        	<div class="alert alert-warning text-center"><h3>4,0</h3></div>
				        	<div n="3" class="btn btn-default feedback">Ver feedback</div>
				        </div>
				</div>
			</div>
        	<div class="col-xs-3">
	        	<div class="panel panel-default">
				        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Entrega 4</strong></div>
				        <div class="panel-body">
				        	
				        </div>
				</div>
			</div>
        </div>
        <div class="panel-heading nota" style="display:none;"><strong><span class="glyphicon glyphicon-th"></span> Entrega <font id="numeron"></font></strong></div>
        <div class="panel-body row nota" style="display:none;">
        	<div class="col-xs-4">
        		<div class="alert alert-warning text-center"><h3>4,0</h3></div>
        	</div>
        	<div class="col-xs-8">
        		<p>otras cosas</p>
        	</div>
        	<div class="col-xs-4">
        		<div class="btn btn-warning back">Volver</div>
        	</div>
        </div>
</div>

<script src="../scripts/vendor.js"></script>
<script src="../scripts/ui.js"></script>
<script src="../scripts/ajx.js"></script>
<script type="text/javascript">
$(".feedback").on("click",function() {
	var n = $(this).attr("n");

	ajx({
		data:{
				f:"ajxvernota",
				n:n
			},
		ok: function(data) {
			console.log(data);

			//hacer algo con la data
			$("#numeron").html(n);
			//mostrar
			$(".notas").hide();
			$(".nota").show();

		}
	})

})

$(".back").on("click",function() {
	$(".nota").hide();
	$(".notas").show();
})
</script>

<?php echo View::make('lti.footer'); ?>