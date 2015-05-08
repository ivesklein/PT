<?php //editar comision ?>
<div class="page page-table" data-ng-controller="TabsDemoCtrl">

	<style>
	.nav-tabs a{
		font-weight: bold;
	}

	.tab-content{
		background: white;
	}

	.nav-tabs > li {
		display: table-cell;
		float: none;
		margin-bottom: -1px;
		width: 1%;
		text-align: center;
	}

	.ui-tab-container .nav-tabs {
   		border-bottom: none;
	}
	</style>

    <div class="ui-tab-container">

    	<div role="tabpanel">

		  <!-- Nav tabs -->
		  <ul class="nav nav-tabs" role="tablist">
		    <li role="presentation" class="active"><a data-target="#tab1" aria-controls="tab1" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-flag"></i> Asignar Presidente</a></li>
		    <li role="presentation"><a data-target="#tab2" aria-controls="tab2" role="tab" data-toggle="tab"><i class="fa fa-user"></i> Asignar Invitado</a></li>
		    <li role="presentation"><a data-target="#tab3" aria-controls="tab3" role="tab" data-toggle="tab"><i class="fa fa-calendar"></i> Fecha Predefensa</a></li>
		    <li role="presentation"><a data-target="#tab4" aria-controls="tab4" role="tab" data-toggle="tab"><i class="fa fa-calendar"></i> Fecha Defensa</a></li>
		  </ul>

		  <!-- Tab panes -->
		  <div class="tab-content">
		    <div role="tabpanel" class="tab-pane active" id="tab1">
		    	<div id="ap" class="row">
            		<div class="col-md-4">
            			<div class="panel panel-default">
			                <div class="panel-heading">
			                    <h3 class="panel-title">Proyecto</h3>
			                </div>
			                <div class="panel-body">
			                    <div class="media">
			                        <div class="media-body">
			                            <ul class="list-unstyled list-info">
			                                <li>
			                                    <span class="icon glyphicon glyphicon-file"></span>
			                                    <label>Tema</label>
			                                    <font class="tema"></font>
			                                </li>
			                                <li>
			                                    <span class="icon glyphicon glyphicon-user"></span>
			                                    <label>Alumno 1</label>
			                                    <font class="a1"></font>
			                                </li>
			                                <li>
			                                    <span class="icon glyphicon glyphicon-user"></span>
			                                    <label>Alumno 2</label>
			                                    <font class="a2"></font>
			                                </li>
			                                <li>
			                                    <span class="icon fa fa-graduation-cap"></span>
			                                    <label>Profesor Guía</label>
			                                    <font class="pg"></font>
			                                </li>
			                                <li>
			                                    <span class="icon glyphicon glyphicon-flag"></span>
			                                    <label>Presidente Comisión</label>
			                                    <font class="pr"></font>
			                                </li>
			                                <li>
			                                    <span class="icon glyphicon glyphicon-list"></span>
			                                    <label>Categoría</label>
			                                    <font class="cat"></font>
			                                </li>
			                            </ul>
			                            
			                        </div>
			                    </div>
			                </div>
			            </div>
            		</div>
            		
            		<div class="col-md-8">
            			<div class="panel panel-default">
			                <div class="panel-heading">
			                    <h3 class="panel-title">Funcionarios</h3>
			                </div>
			                <div class="panel-body">
			                    Panel content
			                </div>
			            </div>
            		</div>
            	</div>
		    </div>
		    <div role="tabpanel" class="tab-pane" id="tab2">2...</div>
		    <div role="tabpanel" class="tab-pane" id="tab3">3...</div>
		    <div role="tabpanel" class="tab-pane" id="tab4">4...</div>
		  </div>

		</div>
        

    </div>

    <script src="js/tab.js"></script>

    <script type="text/javascript">

    	$('#myTab a').click(function (e) {
		  e.preventDefault()
		  $(this).tab('show')
		})

    	$(function () {
			//$('#myTab a:last').tab('show')
		})

    
    </script>
		
</div>