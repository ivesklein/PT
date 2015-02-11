<?php echo View::make('lti.header'); ?>


<div class="panel panel-default">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Notas</strong></div>
        <div class="panel-body row">
        	<div class="col-md-3">
	        	<div class="panel panel-info">
				        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Entrega 1</strong></div>
				        <div class="panel-body">
				        	<div class="alert alert-success text-center"><h3>6,1</h3></div>
				        	<div n="1" class="btn btn-default">Ver feedback</div>
				        </div>
				</div>
			</div>
        	<div class="col-md-3">
	        	<div class="panel panel-info">
				        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Entrega 2</strong></div>
				        <div class="panel-body">
				        	<div class="alert alert-danger text-center"><h3>3,3</h3></div>
				        	<div n="2" class="btn btn-default">Ver feedback</div>
				        </div>
				</div>
			</div>
        	<div class="col-md-3">
	        	<div class="panel panel-info">
				        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Entrega 3</strong></div>
				        <div class="panel-body">
				        	<div class="alert alert-warning text-center"><h3>4,0</h3></div>
				        	<div n="3" class="btn btn-default">Ver feedback</div>
				        </div>
				</div>
			</div>
        	<div class="col-md-3">
	        	<div class="panel panel-default">
				        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Entrega 4</strong></div>
				        <div class="panel-body">
				        	
				        </div>
				</div>
			</div>
        </div>
</div>

<script src="../scripts/vendor.js"></script>
<script src="../scripts/ui.js"></script>
<script type="text/javascript">
$(".btn").on("click",function() {
	var n = $(this).attr("n");
	location = "nota/"+n;
})
</script>

<?php echo View::make('lti.footer'); ?>