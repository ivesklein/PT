<?php echo View::make('lti.header'); ?>


<div class="panel panel-default" style="margin-right: 7px;">
        <div class="panel-heading notas"><strong><span class="glyphicon glyphicon-th"></span> Notas</strong></div>
        <div class="panel-body row notas">
        	<?=$notas?>
        </div>
        <div class="panel-heading nota" style="display:none;"><strong><span class="glyphicon glyphicon-th"></span> <font id="titulo"></font></strong></div>
        <div class="panel-body row nota" style="display:none;">
        	<div class="col-xs-4">
        		<div class="alert alert-success text-center"><h3 id="nota">4,0</h3></div>
        	</div>
        	<div class="col-xs-8">
        		<p id="feedback">otras cosas</p>
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
			$("#titulo").html(data.data.title);
			$("#nota").html(data.data.nota);
			$("#feedback").html(data.data.feedback);
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