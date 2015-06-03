<?php //editar comision ?>
<div class="page page-table">
    <link rel="stylesheet" href="jui/jquery-ui.min.css" />
    <link rel="stylesheet" href="fullcalendar/fullcalendar.css" />
    
    <style>
    #comisionbox .panel-heading, #fechasbox .panel-heading{
        cursor:pointer;
    }
    </style>
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

	.leyenda a{
		margin-bottom: 10px;
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
		    <div role="tabpanel" class="tab-pane active" rol="pr" id="tab1">
		    	<div class="row">
            		<div class="col-md-4">
            			<div class="panel panel-default">
			                <div class="panel-heading">
			                	<div class="btn btn-xs btn-warning save pull-right" style="display:none; top: -3px;position: relative;">Guardar</div>
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
			                    <h3 class="panel-title">Asignar Presidente</h3>
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
		    <div role="tabpanel" class="tab-pane" id="tab2"  rol="in">
		    	<div class="row">
            		<div class="col-md-4">
            			<div class="panel panel-default">
			                <div class="panel-heading">
			                    <div class="btn btn-xs btn-warning save pull-right" style="display:none; top: -3px;position: relative;">Guardar</div>
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
			                    <h3 class="panel-title">Asignar Invitado</h3>
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
		    <div role="tabpanel" class="tab-pane row" id="tab3">
		    	<div class="col-md-4">
		            <div class="panel panel-default" id="fechaprebox">
		                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Fijar Fecha Predefensa</strong></div>
		                <div class="panel-body">

		                        <ul class="list-group" id="eventdetpre">
		                            <li class="list-group-item">Inicio <font class="inicio"></font></li>
		                            <li class="list-group-item">Fin <font class="fin"></font></li>
		                        </ul>
		                    
		                </div>
		            </div>
		            <div class="panel panel-default">
		                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Leyenda</strong></div>
		                <div class="panel-body leyenda">
                        	<a class="fc-day-grid-event fc-event fc-start fc-end  fc-draggable" style="background-color:darkcyan;border-color:darkcyan">
                        		<div class="fc-content">
                        			<span>Predefensa</span>
                        		</div>
                        	</a>
                        	<a class="fc-day-grid-event fc-event fc-start fc-end  fc-draggable" style="background-color:blue;border-color:blue">
                        		<div class="fc-content">
                        			<span>Defensa</span>
                        		</div>
                        	</a>
                        	<a class="fc-day-grid-event fc-event fc-start fc-end  fc-draggable" style="background-color:orange;border-color:orange">
                        		<div class="fc-content">
                        			<span>Entrega</span>
                        		</div>
                        	</a>
                        	<a class="fc-day-grid-event fc-event fc-start fc-end  fc-draggable" style="background-color:black;border-color:black">
                        		<div class="fc-content">
                        			<span>Ocupado</span>
                        		</div>
                        	</a>
                        	<a class="fc-day-grid-event fc-event fc-start fc-end  fc-draggable" style="background-color:green;border-color:green">
                        		<div class="fc-content">
                        			<span>Disponible</span>
                        		</div>
                        	</a>
		                </div>
		            </div>
		    	</div>
	    		<div class="col-md-8">
		            <div class="panel panel-default">
		                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Calendario</strong></div>
		                <div class="panel-body" id="calendar1">
		                    <div class="calendar"></div>
		                </div>
		            </div>
		        </div>
		    </div>
		    <div role="tabpanel" class="tab-pane row" id="tab4">
		    	<div class="col-md-4">
		            <div class="panel panel-default" id="fechadefbox">
		                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Fijar Fecha Defensa</strong></div>
		                <div class="panel-body">

		                        <ul class="list-group" id="eventdetdef">
		                            <li class="list-group-item">Inicio <font class="inicio"></font></li>
		                            <li class="list-group-item">Fin <font class="fin"></font></li>
		                        </ul>
		                    
		                </div>
		            </div>
		            <div class="panel panel-default">
		                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Leyenda</strong></div>
		                <div class="panel-body leyenda">
                        	<a class="fc-day-grid-event fc-event fc-start fc-end  fc-draggable" style="background-color:darkcyan;border-color:darkcyan">
                        		<div class="fc-content">
                        			<span>Predefensa</span>
                        		</div>
                        	</a>
                        	<a class="fc-day-grid-event fc-event fc-start fc-end  fc-draggable" style="background-color:blue;border-color:blue">
                        		<div class="fc-content">
                        			<span>Defensa</span>
                        		</div>
                        	</a>
                        	<a class="fc-day-grid-event fc-event fc-start fc-end  fc-draggable" style="background-color:orange;border-color:orange">
                        		<div class="fc-content">
                        			<span>Entrega</span>
                        		</div>
                        	</a>
                        	<a class="fc-day-grid-event fc-event fc-start fc-end  fc-draggable" style="background-color:black;border-color:black">
                        		<div class="fc-content">
                        			<span>Ocupado</span>
                        		</div>
                        	</a>
                        	<a class="fc-day-grid-event fc-event fc-start fc-end  fc-draggable" style="background-color:green;border-color:green">
                        		<div class="fc-content">
                        			<span>Disponible</span>
                        		</div>
                        	</a>
		                </div>
		            </div>
		    	</div>
	    		<div class="col-md-8">
		            <div class="panel panel-default">
		                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Calendario</strong></div>
		                <div class="panel-body" id="calendar2">
		                    <div></div>
		                </div>
		            </div>
		        </div>
		    </div>
		  </div>

		</div>
        

    </div>
    <script src="fullcalendar/lib/moment.min.js"></script>
    <script src="fullcalendar/fullcalendar.js"></script>
    <script src="fullcalendar/lang/es.js"></script>
    <script src="js/tab.js"></script>

    <script type="text/javascript">

    	var color = "";

    	$('a[data-target="#tab3"], a[data-target="#tab4"]').click(function (e) {
		  	e.preventDefault()
		  	$(this).tab('show')
		  	var view = "";
		  	if($(this).attr("data-target")=="#tab3"){
		  		view = '#calendar1';
		  		color = "darkcyan";
		  	}else if($(this).attr("data-target")=="#tab4"){
		  		view = '#calendar2';
		  		color = "blue";
		  	}
		  	var cal = $(".calendar");

		  	$(view).append(cal);

		  	var typeactual = cal.fullCalendar('getView').type;
		  	var typeother = typeactual=="month"?"agendaDay":"month";

		  	cal.fullCalendar( 'changeView', typeother )
		  	cal.fullCalendar( 'changeView', typeactual )

		  	var now = cal.fullCalendar('getDate')

			cal.fullCalendar( 'gotoDate', now )

		})

    	var dataj = "";

    	var idsubj = "";


    	var jcall = function(idtema) {
    		
    		idsubj = idtema;


    		var labelcom = function(name,id,status) {
    			var message ="";
                if(status=="confirmar"){
                    message = ' <span data-id="'+id+'" class="badge badge-danger delprof">X</span><span class="badge badge-warning">no confirmado</span>';
                	return name+message;
                }
                if(status=="confirmado"){
                    message = ' <span data-id="'+id+'" class="badge badge-danger delprof">X</span><span class="badge badge-success">confirmado</span>';
                    return name+message;
                }
                if(status=="rechazado"){
                    message = ' <span data-id="'+id+'" class="badge badge-danger delprof">X</span><span class="badge badge-danger">rechazado</span>';
                    return name+message;
                }
                if(status=="no guardado"){
                    message = '<span data-id="'+id+'" class="badge badge-danger delprof">X</span><span class="badge badge-danger">'+name+' <b>No Guardado</b></span>';
                    return message;
                }
    		}

    		$(function () {

	    		var Eventos = function(listel, calel){

	                var yo = this;
	                yo.profesores = {};
	                yo.listel = listel;
	                yo.calel = calel;
	                yo.comision = {};

	                yo.addoriginal = function(profesor) {
	                    if(profesor in yo.comision){}else{
	                        yo.comision[profesor] = 1;
	                    }
	                    yo.modified();
	                }

	                yo.add = function(profesor, eventos, nombre, tipo, status) {
	                    if(profesor in yo.profesores){}else{
	                        yo.profesores[profesor] = {"eventos":eventos, "rol":tipo};
	                        for(n in eventos){
	                            var evento = eventos[n];

	                            if(evento.color=="blue" || evento.color=="darkcyan"){
	                                var data = evento.detail.split("|");
	                                var title = data[1];
	                                var id = data[0];

	                                if(id==idtema){
	                                    evento.title = title;
	                                    evento.detail = id;

	                                    if($(yo.calel).fullCalendar( 'clientEvents', evento.id ).length==0){
	                                        $(yo.calel).fullCalendar('renderEvent', evento, true);
	                                        console.log($(yo.calel).fullCalendar( 'clientEvents', evento.id ).length)
	                                        console.log(nombre)
	                                    }
	                                }else{
	                                    $(yo.calel).fullCalendar('renderEvent', evento, true);
	                                }

	                            }else{
	                                $(yo.calel).fullCalendar('renderEvent', evento, true);
	                            }

	                        }

	                        console.log("tipo: "+tipo)
	                        
	                        if(tipo=="pr"){
	                        	$(".pr").html(labelcom(nombre,profesor,status));
	                        }
	                        if(tipo=="in"){
	                        	$(".in").html(labelcom(nombre,profesor,status));
	                        }

	                        /*if(tipo=="guia"){
	                            $(listel).prepend('<li id="P'+profesor+'" class="list-group-item">'+nombre+'<span class="badge badge-info">Profesor Guía</span></li>');    
	                        }
	                        if(tipo=="comision"){
	                            var message = "";
	                            if(status=="confirmar")
	                                message = '<span class="badge badge-warning">no confirmado</span>';
	                            if(status=="confirmado")
	                                message = '<span class="badge badge-success">confirmado</span>';
	                            if(status=="rechazado")
	                                message = '<span class="badge badge-danger">rechazado</span>';

	                            $(listel).append('<li id="P'+profesor+'" class="list-group-item">'+nombre+'<span class="badge badge-danger delprof">X</span>'+message+'</li>');    
	                        }*/



	                    }
	                    yo.modified();
	                }

	                $(listel).on('click', ".delprof", function() {
	                    var profe = $(this).attr('data-id');
	                    var rol = $(this).parent().attr("class");
	                    yo.remove(profe,rol);

	                })


	                yo.remove = function(profesor,rol) {
	                    $("."+rol).html("");
	                    for(n in yo.profesores[profesor]["eventos"]){
	                        var evento = yo.profesores[profesor]["eventos"][n];
	                        $(yo.calel).fullCalendar('removeEvents', evento.id);
	                    }
	                    delete(yo.profesores[profesor]);
	                    yo.modified();

	                }

	                yo.reset = function() {
	                    //for (i in yo.profesores) {
	                    //    $(listel+" #P"+i).remove();
	                    //};
	                    yo.profesores = {};
	                    yo.comision = {};
	                    $(yo.calel).fullCalendar('removeEvents');
	                    yo.modified();
	                }

	                yo.modified = function() {
	                    
	                    var ori = 0;
	                    var now = 0;
	                    var coi = 0;

	                    for (prof in yo.comision) {
	                        ori++;
	                        if(prof in yo.profesores){
	                            coi++;
	                        }
	                    }
	                    for (prof in yo.profesores) {
	                        now++;
	                    }

	                    if(ori==coi && ori==now){
	                        //not modified
	                        $(".save").hide();
	                    }else{
	                        //modified
	                        $(".save").show();
	                    }
	                    //console.log(ori+" "+now+" "+coi)



	                }

	                yo.changes = function() {
	                    
	                    var res = {"news":{},"deleted":{}};

	                    //deleted
	                    for (prof in yo.comision) {
	                        if(prof in yo.profesores){}else{
	                            res.deleted[prof] = 1;
	                        }
	                    }

	                    //news
	                    for (prof in yo.profesores) {
	                        if(prof in yo.comision){}else{
	                            res.news[prof] = yo.profesores[prof].rol;
	                        }
	                    }


	                    return res;
	                }



	            }

	            var add = function(start, end) {
	            	console.log(this)
	                var id = idsubj;
	                var eventcolor = color;
	                //var eventdes="Predefensa";
	                //verificar que se sepa que tipo de evento es
	                
                    //guardarlo
                    var eventData;
                    ajx({
                        data:{
                            f:'Comision_newdate',
                            id: id,
                            start: start.format(),
                            end: end.format(),
                            color: eventcolor
                        },

                        ok:function(data) {
                            eventData = {
                                id: data.ok[0],
                                title: data.ok[1],
                                detail: id,
                                start: start,
                                end: end,
                                color: eventcolor
                            };
                            console.log(eventData);
                            $('.calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
                            $('.calendar').fullCalendar('unselect');

                            $("#eventdetpre .inicio").html(start.format());
                            $("#eventdetpre .fin").html(end.format());
                            
                        },
                        error:function(data){
                            alert(data)
                            $('.calendar').fullCalendar('unselect');
                        }
                    });
	            }

	            var edit = function(event, delta, error){
	                console.log("a");

	                var start = event.start//.format();
	                var end = event.end//.format();
	                var id = event.id;

	                if(event.detail==idsubj){
	                    var res = confirm("¿Realmente desea mover "+event.title+" a "+event.start.format()+" hasta "+event.end.format()+"?")
	                    if(res){
	                        
	                        ajx({
	                            data:{
	                                f:'Eventos_editar',
	                                id: id,
	                                start: event.start.format(),
	                                end: event.end.format()
	                            },
	                            ok:function(data){

	                            	$('.calendar').fullCalendar('updateEvent', event);
	                            },
	                            error:error
	                        });
	                        
	                    }else{
	                        error();
	                    }
	                }else{
	                    error();
	                }

	            }

	            var click = function(event){
	                if(event.detail==idsubj){
	                    var del = confirm("¿Borrar "+event.detail+"?");
	                    if(del==true){
	                        ajx({
	                            data:{
	                                f:'Eventos_borrar',
	                                id: event.id
	                            },
	                            ok:function(data){
	                                $('.calendar').fullCalendar('removeEvents',event.id);
	                            }
	                        });
	                    }
	                }
	            }

	            $(".calendar").fullCalendar({
	                aspectRatio:1.70,
	                'header':{
	                    'left':'prev,today,next',
	                    'center':'title',
	                    'right':'month,agendaWeek,agendaDay'
	                },
	                'firstDay':0,
	                'views':{
	                    'agendaWeek':{},
	                    'agendaDay':{}
	                },
	                'businessHours':{
	                    start:"8:15",
	                    end:"20:40",
	                    dow:[1,2,3,4,5]
	                },
	                'slotDuration':'00:15:00',
	                'snapDuration':'00:05:00',
	                'lang':'es',
	                'defaultView':'month',

	                'timezone':"-3:00",
	                selectable: true,
	                //selectHelper: true,
	                select: add,
	                eventResize: edit,
	                eventDrop: edit,
	                eventClick: click,
	                editable: true,
	            });

	            Lista = new Eventos(".ui-tab-container",".calendar");
			
				//console.log("view "+angular.element($('.page')).scope().idtema);
				var datos = {
	                f:"Comision_data",
	                id:idtema
	            }
	            
	            function cargar () {
	            	ajx({
		                data:datos,
		                ok:function(data) {
		                    console.log(data);
		                    dataj = data;
		                    $(".tema").html(data.data.tema);
		                    $(".s1").html(data.data.s1.nc);
		                    $(".s2").html(data.data.s2.nc);
		                    $(".pg").html(data.data.pg.nc);

		                    /*if('pr' in data.data){
		                    	$(".pr").html(labelcom(
		                    		data.data.pr.nc,
		                    		data.data.pr.id,
		                    		data.data.pr.status
		                    	));
		                    }

		                    if('in' in data.data){
			                    $(".in").html(labelcom(
		                    		data.data.in.nc,
		                    		data.data.in.id,
		                    		data.data.in.status
		                    	));
		                	}*/
		                	if('cat' in data.data){
		                		$(".cat").html(data.data.cat);
		                	}

		                	//calendario

	            	        if("guia" in data.data){
	                            var prof = data.data.guia.id;
	                            var name = data.data.guia.name;

	                            ajx({
	                                data:{
	                                    f:'Eventos_profe',
	                                    prof: prof
	                                },
	                                ok:function(data){

	                                    Lista.add(prof,data.data,name,"guia","");
	                                    Lista.addoriginal(prof);
	                                }
	                            });
	                            
	                        }

	                        if(1 in data.data){
	                            var prof1 = data.data[1].id;
	                            var name1 = data.data[1].name;
	                            var status1 = data.data[1].status;
	                            if(data.data[1].rol=="1"){
	                            	 var rol1 = "pr";
	                            }else if(data.data[1].rol=="2"){
	                            	var rol1 = "in";
	                            }
	                            ajx({
	                                data:{
	                                    f:'Eventos_profe',
	                                    prof: prof1
	                                },
	                                ok:function(data){
	                                    console.log(name1);
	                                    Lista.add(prof1,data.data,name1,rol1,status1);
	                                    Lista.addoriginal(prof1);
	                                }
	                            });
	                        }

	                        if(2 in data.data){
	                            var prof2 = data.data[2].id;
	                        	var name2 = data.data[2].name;
	                            var status2 = data.data[2].status;
	                            if(data.data[2].rol=="1"){
	                            	 var rol2 = "pr";
	                            }else if(data.data[2].rol=="2"){
	                            	var rol2 = "in";
	                            }
	                            ajx({
	                                data:{
	                                    f:'Eventos_profe',
	                                    prof: prof2
	                                },
	                                ok:function(data){
	                                    console.log(name2);
	                                    Lista.add(prof2,data.data,name2,rol2,status2);
	                                    Lista.addoriginal(prof2);
	                                }
	                            });
	                        }


	                        //Lista.add(prof,data.data,name);

	                        //agregar tareas
	                        if("tareas" in data){
	                            for(i in data.tareas){
	                                $('.calendar').fullCalendar('renderEvent', data.tareas[i], true);
	                                console.log(data.tareas[i].title);
	                            }
	                        }

	                        if("pre" in data){
	                            if("start" in data["pre"]){
	                                $("#eventdetpre .inicio").html(data["pre"]['start'])
	                            }
	                            if("end" in data["pre"]){
	                                $("#eventdetpre .fin").html(data["pre"]['end'])   
	                            }
	                        }
	                        if("def" in data){
	                            if("start" in data["def"]){
	                                $("#eventdetdef .inicio").html(data["def"]['start'])
	                            }
	                            if("end" in data["def"]){
	                                $("#eventdetdef .fin").html(data["def"]['end'])   
	                            }      
	                        }


		                }//ok
		            });//ajx
	            }

	            
				cargar();

				$(".save").on("click",function() {
                    var changes = Lista.changes();
                    var snews = "";
                    var snewsrol = "";
                    var sdels = "";
                    
                    for(news in changes.news){
                        snews += news+",";
                        snewsrol += changes.news[news]+",";
                    }
                    for(dels in changes.deleted){
                        sdels += dels+",";   
                    }

                    ajx({
                        data:{
                            f:'Comision_guardar',
                            id: idsubj,
                            news: snews,
                            rols: snewsrol,
                            dels: sdels
                        },
                        ok:function(data) {
                            
                        	Lista.reset();
                        	cargar();

                        }
                    });

                })



			})

			

			// TABLA TAB PRESIDENTE //
			var finder = $('<input type="text" class="form-control find" placeholder="Buscar...">');
    	
	    	var selector = '<select class="user form-control"><option value="nc">Nombre Completo</option><option value="name">Nombre</option><option value="surname">Apellido</option><option value="wc_id">Email</option><option value="run">RUN</option></select>';
			var selector2 = '<select class="user form-control"><option value="nc">Nombre Completo</option><option value="name">Nombre</option><option value="surname">Apellido</option><option value="wc_id">Email</option></select>';

	    	var t11 = $('#tab1 .table:nth-child(1)').addClass('titulos');
	    	t11.find('thead tr').append('<th class="p1-8 cname" data-who="name">Profesores<br>'+selector2+'</th>');
	    	t11.find('thead tr').append('<th class="p1-8 cesp" data-who="esp">Especialidad</th>');
	    	t11.find('thead tr').append('<th class="p1-8 cguia" data-who="guia">Guias</th>');
	    	t11.find('thead tr').append('<th class="p1-8">Comisiones</th>');
	    	t11.find('thead tr').append('<th class="p1-8">Asignar</th>');

	    	var t12 = $('#tab1 .table:nth-child(2)').addClass('finders');
	    	t12.find('thead tr').append('<th class="p1-8 cname" id="fname"></th>');
	    	t12.find('thead tr').append('<th class="p1-8 cesp" id="fesp"></th>');
	    	t12.find('thead tr').append('<th class="p1-8"></th>');
	    	t12.find('thead tr').append('<th class="p1-8"></th>');
	    	t12.find('thead tr').append('<th class="p1-8"></th>');

	    	// TABLA TAB INVITADO //
	    	var t21 = $('#tab2 .table:nth-child(1)').addClass('titulos');
	    	t21.find('thead tr').append('<th class="p1-8 cname" data-who="name">Profesores<br>'+selector2+'</th>');
	    	t21.find('thead tr').append('<th class="p1-8 cesp" data-who="esp">Especialidad</th>');
	    	t21.find('thead tr').append('<th class="p1-8 cguia" data-who="guia">Guias</th>');
	    	t21.find('thead tr').append('<th class="p1-8">Comisiones</th>');
	    	t21.find('thead tr').append('<th class="p1-8">Asignar</th>');

	    	var t22 = $('#tab2 .table:nth-child(2)').addClass('finders');
	    	t22.find('thead tr').append('<th class="p1-8 cname" id="fname"></th>');
	    	t22.find('thead tr').append('<th class="p1-8 cesp" id="fesp"></th>');
	    	t22.find('thead tr').append('<th class="p1-8"></th>');
	    	t22.find('thead tr').append('<th class="p1-8"></th>');
	    	t22.find('thead tr').append('<th class="p1-8"></th>');

	    	//--------------
	    	
	    	//append de buscadores
	    	$('#fname, #fesp').append(finder);


	    	//control que dato mostrar

	    	$('select.user').on('change', function() {

		    	//var who = $(this).parent().attr('data-who');
		    	var toshow = $(this).val();
		    	var tab = $(this).parents(".tab-pane");

		    	tab.find('.rowlista').each( function() {
		    		var id = $(this).attr("data-id");
		    		var val = datostabla[id][toshow];
		    		$(this).find(".dname").html(val);
		    	});

		    });

			datostabla = {};

			//control espera antes de hacer ajax
		        
	        var typewatch = function(callback,ms){
			    var timer = 0;
			    return function(callback, ms){
			        clearTimeout (timer);
			        timer = setTimeout(callback, ms);
			    }  
			}();

			//control hacer ajax para buscar profesores
		
			function findfun (target) {
				var name = $(target+' #fname input').val();
	    		var esp = $(target+' #fesp input').val();

	            datos = {
	                f:"Comision_usuarios"
	            }

	            if(name!=""){datos['name']=name}
	            if(esp!=""){datos['esp']=esp}

	            var mpg = "";
	            if("pg" in dataj.data){
	            	mpg = dataj.data.pg.wc_id;
	            }

	            var mpr = "";
	            if("pr" in dataj.data){
	            	mpr = dataj.data.pr.wc_id;
	            }

	            var minv = "";
	            if("in" in dataj.data){
	            	minv = dataj.data.in.wc_id;
	            }

	            ajx({
	                data:datos,
	                ok:function(data) {
	                    console.log(data);

	                    $(target+' .table:nth-child(3) tbody').html("");

	                    datostabla = data['users'];
	                    var users = data['users'];
	                    var color = {"confirmar":"text-warning","confirmado":"text-success","rechazado":"text-danger","":""};

	                    for(n in data['users']){
	                    	var user = data['users'][n];
	                    	if(user.wc_id!=mpr && user.wc_id!=minv && user.wc_id!=mpg){
		                    	var tr = $("<tr data-id='"+n+"' class='rowlista r"+n+"'></tr>");

		                    	if((target=="#tab1" && ("pr" in dataj.data)) || (target=="#tab2" && ("in" in dataj.data)) ){
		                    		var button = "<div data-user='"+n+"' data-name='"+user.nc+"' class='btn btn-info asignar' style='display:none;'>Agregar</a>";
		                    	}else{
		                    		var button = "<div data-user='"+n+"' data-name=\""+user.nc+"\" class='btn btn-info asignar'>Agregar</a>";
		                    	}
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

	                   	//activar boton agregar
				    	$(target+' .table:nth-child(3) tbody').on('click','.asignar',function() {
				    		var user = $(this).attr('data-user');
				    		var rol = $(this).parents('.tab-pane').attr('rol');
				    		
				    		//agregar a Lista
		                    var name = $(this).attr('data-name');

		                    ajx({
		                        data:{
		                            f:'Eventos_profe',
		                            prof: user
		                        },
		                        ok:function(data){

		                            Lista.add(user,data.data,name,rol,"no guardado");

		                        }
		                    });

				    		//var row = $(this).
				    		//alert(user+" "+rol);

				    		/*datos2 = {
				                f:"Comision_guardar",
				                id:idsubj,
				                news:user,
				                rol:rol,
				                dels:""
				            }

				    		ajx({
				                data:datos2,
				                ok:function(data) {
				                    console.log(data);
				                    //agregar a la info y sacar de la tabla
				                    var username = users[user]['nc'];

				                    $("."+rol).html(labelcom(
			                    		username,
			                    		user,
			                    		"confirmar"
			                    	));

			                    	$("r"+user).hide();

				                }
				            });//ajx
							*/
				    	})



	                }
	            });//ajx
			}

			//activación de buscadores

	    	$('#tab1 .finders').on("keyup",".find",function() {
	    		typewatch(findfun('#tab1'),200);
	    	})
	    	$('#tab2 .finders').on("keyup",".find",function() {
	    		typewatch(findfun('#tab2'),200);
	    	})


	    	//calendario

            var eventcolor = "";



            var load = function() {
                /*
                ajx({
                    data:{
                        f:'ajxmyevents'
                    },
                    ok:function(data) {
                        for(n in data.data){
                            var evento = data.data[n];
                            $('#calendar').fullCalendar('renderEvent', evento, true);

                        }
                    }
                });*/
            }




    	}

    
    </script>
		
</div>