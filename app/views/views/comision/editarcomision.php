<?php //editar comision ?>
<div class="page page-table">

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

	.p30{
		width: 30%;
	}
	.p10{
		width: 10%;
	}
	.p15{
		width: 15%;
	}
	.p1-8{
		width: 12.5%;
	}
	.p1-16{
		width: 6.25%;
	}
	.checkbox{
		margin-left: 15px;
	}

	.table{
		margin-bottom: 0px;
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
			                                    <span class="icon glyphicon glyphicon-list"></span>
			                                    <label>Categoría</label>
			                                    <font class="cat"></font>
			                                </li>
			                                <li>
			                                    <span class="icon glyphicon glyphicon-user"></span>
			                                    <label>Alumno 1</label>
			                                    <font class="s1"></font>
			                                </li>
			                                <li>
			                                    <span class="icon glyphicon glyphicon-user"></span>
			                                    <label>Alumno 2</label>
			                                    <font class="s2"></font>
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
			                                    <span class="icon glyphicon glyphicon-user"></span>
			                                    <label>Invitado Comisión</label>
			                                    <font class="in"></font>
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
			                    <?=View::make('table.table',array('body'=>'','head'=>''))?>
						        <?=View::make('table.table',array('body'=>'','head'=>''))?>
						        <?=View::make('table.table',array('body'=>'','head'=>''))?>
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

    	var dataj = "";


    	var jcall = function(idtema) {
    		
    		$(function () {
			
				//console.log("view "+angular.element($('.page')).scope().idtema);
				var datos = {
	                f:"Comision_data",
	                id:idtema
	            }
	            ajx({
	                data:datos,
	                ok:function(data) {
	                    console.log(data);
	                    dataj = data;
	                    $(".tema").html(data.data.tema);
	                    $(".s1").html(data.data.s1.nc);
	                    $(".s2").html(data.data.s2.nc);
	                    $(".pg").html(data.data.pg.nc);

	                    if('pr' in data.data){
	                    var message ="";
		                    if(data.data.pr.status=="confirmar")
	                            message = ' <span class="badge badge-warning">no confirmado</span>';
	                        if(data.data.pr.status=="confirmado")
	                            message = ' <span class="badge badge-success">confirmado</span>';
	                        if(data.data.pr.status=="rechazado")
	                            message = ' <span class="badge badge-danger">rechazado</span>';
		                    $(".pr").html(data.data.pr.nc+message);
	                    }

	                    if('in' in data.data){
		                    message ="";
		                    if(data.data.in.status=="confirmar")
	                            message = ' <span class="badge badge-warning">no confirmado</span>';
	                        if(data.data.in.status=="confirmado")
	                            message = ' <span class="badge badge-success">confirmado</span>';
	                        if(data.data.in.status=="rechazado")
	                            message = ' <span class="badge badge-danger">rechazado</span>';
		                    $(".in").html(data.data.in.nc+message);
	                	}
	                	if('cat' in data.data){
	                		$(".cat").html(data.data.cat);
	                	}
	                }//ok
	            });//ajx
			})

			

			// TAB PRESIDENTE //
			var finder = $('<input type="text" class="form-control find" placeholder="Buscar...">');
    	
	    	var selector = '<select class="user form-control"><option value="nc">Nombre Completo</option><option value="name">Nombre</option><option value="surname">Apellido</option><option value="wc_id">Email</option><option value="run">RUN</option></select>';
			var selector2 = '<select class="user form-control"><option value="nc">Nombre Completo</option><option value="name">Nombre</option><option value="surname">Apellido</option><option value="wc_id">Email</option></select>';

	    	$('#tab1 .table:nth-child(1)').addClass('titulos');
	    	$('.titulos thead tr').append('<th class="p1-8 cname" data-who="name">Profesores<br>'+selector2+'</th>');
	    	$('.titulos thead tr').append('<th class="p1-8 cesp" data-who="esp">Especialidad</th>');
	    	$('.titulos thead tr').append('<th class="p1-8 cguia" data-who="guia">Guias</th>');
	    	$('.titulos thead tr').append('<th class="p1-8">Comisiones</th>');
	    	$('.titulos thead tr').append('<th class="p1-8">Asignar</th>');

	    	$('#tab1 .table:nth-child(2)').addClass('finders');
	    	$('.finders thead tr').append('<th class="p1-8 cname" id="fname"></th>');
	    	$('.finders thead tr').append('<th class="p1-8 cesp" id="fesp"></th>');
	    	$('.finders thead tr').append('<th class="p1-8"></th>');
	    	$('.finders thead tr').append('<th class="p1-8"></th>');
	    	$('.finders thead tr').append('<th class="p1-8"></th>');

	    	$('#fname, #fesp').append(finder);


	    	$('select.user').on('change', function() {

		    	//var who = $(this).parent().attr('data-who');
		    	var toshow = $(this).val();
		    	var tab = $(this).parents(".tab-pane");

		    	tab.find('.rowlista').each( function() {
		    		var id = $(this).attr("id");
		    		var val = datostabla[id][toshow];
		    		$(this).find(".dname").html(val);
		    	});

		    });

			datostabla = {};

		        
	        var typewatch = function(callback,ms){
			    var timer = 0;
			    return function(callback, ms){
			        clearTimeout (timer);
			        timer = setTimeout(callback, ms);
			    }  
			}();

		
			function findfun (target) {
				var name = $('#fname input').val();
	    		var esp = $('#fesp input').val();

	            datos = {
	                f:"Comision_usuarios"
	            }

	            if(name!=""){datos['name']=name}
	            if(esp!=""){datos['esp']=esp}

	            ajx({
	                data:datos,
	                ok:function(data) {
	                    console.log(data);

	                    

	                    $(target+' .table:nth-child(3) tbody').html("");

	                    datostabla = data['users'];
	                    var color = {"confirmar":"text-warning","confirmado":"text-success","rechazado":"text-danger","":""};

	                    for(n in data['users']){
	                    	var user = data['users'][n];
	                    	var tr = $("<tr id='"+n+"' class='rowlista'></tr>");

	                    	var button = "<div data-user='"+n+"' class='btn btn-info ver'>Agregar</a>";

	                    	//tr.append("<td class='p1-8'>"+tema.grupo+"</td>");
	                    	//tr.append("<td class='p1-8'>"+'<button type="button" class="btn btn-default" data-placement="top" data-toggle="popover" data-content="'+tema.tema+'"><div class="fa fa-eye"></div></button>'+"</td>");
	                    	//tr.append("<td class='p1-8'>"+tema.tema.substring(0,20)+'... <button type="button" class="btn btn-default" data-placement="top" data-toggle="popover" data-content="'+tema.tema+'"><div class="fa fa-eye"></div></button></td>');
	                    	//abbr

	                    	//tr.append("<td class='p1-8'><abbr title='"+tema.tema+"'>"+tema.tema.substring(0,20)+'...</abbr></td>');
	                    	
	                    	//what to show
	                    	var toshow = $(target+' .cname select').val();
	                    	tr.append("<td class='p1-8 cname dname'>"+user[toshow]+"</td>");

	                    	tr.append("<td class='p1-8'>"+user.esp+"</td>");
	                    	tr.append("<td class='p1-8'>"+user.guias+"</td>");
	                    	tr.append("<td class='p1-8'>"+user.comisiones+"</td>");
	                    	tr.append("<td class='p1-8'>"+button+"</td>");

	                    	$(target+' .table:nth-child(3) tbody').append(tr);

	                    }


	                }
	            });//ajx
			}

	    	$('.finders').on("keyup",".find",function() {
	    		typewatch(findfun('#tab1'),200);
	    	})














    	}

    
    </script>
		
</div>