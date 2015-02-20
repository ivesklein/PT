        	<div class="col-xs-3">
	        	<div class="panel <?php 
	        	if(isset($active)){
	        		if($active==1){ 
	        			echo "panel-info";
	        			$n = isset($tarea)?$tarea:"";
	        			$boton = '<div n="'.$n.'" class="btn btn-default feedback">Ver feedback</div>';
	        		}else{
	        			echo "panel-default";
	        			$boton = "";
	        		}
	        	}else{
	        		echo "panel-default";
	        		$boton = "";
	        	} 

	        	?>">
				        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> <?php echo isset($title)?$title:""; ?></strong></div>
				        <div class="panel-body">
				        	<div class="alert alert-success text-center"><h3><?php echo isset($nota)?$nota:""; ?></h3></div>
				        	<?=$boton?>
				        </div>
				</div>
			</div>