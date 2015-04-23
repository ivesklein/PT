<div class="page page-table">
    <link rel="stylesheet" href="jui/jquery-ui.min.css" />
    <link rel="stylesheet" href="fullcalendar/fullcalendar.css" />
    
    <style>
    #comisionbox .panel-heading, #fechasbox .panel-heading{
        cursor:pointer;
    }
    </style>
    <script src="jui/jquery-ui.min.js"></script>
    <script src="fullcalendar/lib/moment.min.js"></script>
    <script src="fullcalendar/fullcalendar.js"></script>
    <script src="fullcalendar/lang/es.js"></script>
    <!--script src="js/bloodhound.min.js"></script>
    <script src="js/typeahead.jquery.min.js"></script-->
    
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Tema</strong></div>
                <div class="panel-body form-horizontal">
                    <div class="form-group">
                        <label for="tema" class="col-sm-4">Seleccionar Tema</label>
                        <div class="col-sm-8">
                            <span class="ui-select">
                                <select name="tema" id="temas">
                                </select>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default" style="display:none;" id="comisionbox" data-ng-controller="CollapseCtrl">
                <div class="panel-heading" ng-click="isCollapsed = !isCollapsed"><strong><span class="glyphicon glyphicon-th"></span> Armar Comisión</strong></div>
                <div class="panel-body form-horizontal" collapse="isCollapsed">
                    <div class="form-group ">
                        <ul class="list-group col-sm-10 col-sm-offset-1" id="list">
       
                        </ul>
                        <div class="input-group col-sm-10 col-sm-offset-1">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                            <input id="buscarprofesor" type="text" class="form-control" placeholder="buscar profesor">
                            <input id="prof" type="hidden">
                            <span class="input-group-btn">
                                <button id="add" class="btn btn-default" type="button">Agregar</button>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <button id="save" class="btn btn-warning col-sm-offset-1 col-sm-10" type="button" style="display:none;">Guardar y Notificar</button>
                    </div>
                </div>
            </div>

            <div class="panel panel-default" style="display:none;" id="fechaprebox" data-ng-controller="CollapseCtrl">
                <div class="panel-heading" ng-click="isCollapsed = !isCollapsed"><strong><span class="glyphicon glyphicon-th"></span> Fijar Fecha Predefensa</strong></div>
                <div class="panel-body" collapse="isCollapsed">

                        <ul class="list-group" id="eventdetpre">
                            <li class="list-group-item">Inicio <font class="inicio"></font></li>
                            <li class="list-group-item">Fin <font class="fin"></font></li>
                        </ul>
                    
                </div>
            </div>

            <div class="panel panel-default" style="display:none;" id="fechadefbox" data-ng-controller="CollapseCtrl">
                <div class="panel-heading" ng-click="isCollapsed = !isCollapsed"><strong><span class="glyphicon glyphicon-th"></span> Fijar Fecha Defensa</strong></div>
                <div class="panel-body" collapse="isCollapsed">
                        <ul class="list-group" id="eventdetdef">
                            <li class="list-group-item">Inicio <font class="inicio"></font></li>
                            <li class="list-group-item">Fin <font class="fin"></font></li>
                        </ul>
                </div>
            </div>

            <script type="text/javascript">

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
                        yo.profesores[profesor] = eventos;
                        for(n in eventos){
                            var evento = eventos[n];

                            if(evento.color=="blue" || evento.color=="darkcyan"){
                                var data = evento.detail.split("|");
                                var title = data[1];
                                var id = data[0];

                                if(id==idtema){
                                    evento.title = title;
                                    evento.detail = id;

                                    if($(calel).fullCalendar( 'clientEvents', evento.id ).length==0){
                                        $(calel).fullCalendar('renderEvent', evento, true);
                                        console.log($(calel).fullCalendar( 'clientEvents', evento.id ).length)
                                        console.log(nombre)
                                    }
                                }else{
                                    $(calel).fullCalendar('renderEvent', evento, true);
                                }

                            }else{
                                $(calel).fullCalendar('renderEvent', evento, true);
                            }

                        }
                        if(tipo=="guia"){
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
                        }
                    }
                    yo.modified();
                }

                $(listel).on('click', ".delprof", function() {
                    var prof = $(this).parent().attr('id');
                    var prof2 = prof.split("P");
                    var profe = prof2[1];
                    yo.remove(profe);

                })


                yo.remove = function(profesor) {
                    $(listel+" #P"+profesor).remove();
                    for(n in yo.profesores[profesor]){
                        var evento = yo.profesores[profesor][n];
                        $(calel).fullCalendar('removeEvents', evento.id);
                    }
                    delete(yo.profesores[profesor]);
                    yo.modified();

                }

                yo.reset = function() {
                    for (i in yo.profesores) {
                        $(listel+" #P"+i).remove();
                    };
                    yo.profesores = {};
                    yo.comision = {};
                    $(calel).fullCalendar('removeEvents');
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
                        $("#save").hide();
                    }else{
                        //modified
                        $("#save").show();
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
                            res.news[prof] = 1;
                        }
                    }


                    return res;
                }



            }

            </script>
            <script type="text/javascript">

            var idtema=-1;

            $(function() {

                ajx({
                    data:{
                        f:'Memorias_grupos'
                    },
                    ok:function(data){
                        
                        if(data.data.length==0){
                            $('#temas').append("<option value='sel'>No hay temas</option>");
                        }else{
                            $('#temas').append("<option value='sel'>Selecione Tema</option>");
                            for(i in data.data){
                                var tema = data.data[i];
                                $('#temas').append("<option value='"+tema['id']+"'>"+tema['title']+"</option>");
                            }
                        }
                    }
                });

                $("#comisionbox .panel-heading, #fechaprebox .panel-heading, #fechadefbox .panel-heading").hover(
                function() {//adentro
                    if(!$(this).hasClass("selected"))
                        $(this).parent().removeClass('panel-default').addClass('panel-info');
                },
                function() {//fuera
                    if(!$(this).hasClass("selected"))
                        $(this).parent().removeClass('panel-info').addClass('panel-default');
                });


                $("#fechaprebox, #fechaprebox .panel-heading, #fechaprebox .panel-body").on('click',function() {
                    $("#fechaprebox").removeClass('panel-default').addClass('panel-info').find(".panel-heading").addClass("selected");

                    $("#fechadefbox").removeClass('panel-info').addClass('panel-default').find(".panel-heading").removeClass("selected");
                    
                    if($("#suggestpre").length==0){
                        $("#eventdetpre").prepend("<div class='alert alert-info' id='suggestpre'>Seleccione fecha en el calendario</>");
                    }
                    $("#suggestdef").remove();
                    eventcolor = "darkcyan";
                    eventdes = "Predefensa";
                    type=1;
                })

                $("#fechadefbox, #fechadefbox .panel-heading, #fechadefbox .panel-body").on('click',function() {
                     $("#fechadefbox").removeClass('panel-default').addClass('panel-info').find(".panel-heading").addClass("selected");

                    $("#fechaprebox").removeClass('panel-info').addClass('panel-default').find(".panel-heading").removeClass("selected");
                    
                    if($("#suggestdef").length==0){
                        $("#eventdetdef").prepend("<div class='alert alert-info' id='suggestdef'>Seleccione fecha en el calendario</>");
                    }
                    $("#suggestpre").remove();
                    eventcolor = "blue";
                    eventdes = "Defensa";
                    type=2;
                 })

                $('#tipoevento input[type="radio"]').on("change",function() {
                    eventcolor = $(this).val();
                    //$('#temas').html("");
                    /*
                    if($("#suggest").length==0){
                        $("#eventdet").prepend("<div class='alert alert-info' id='suggest'>Seleccione fecha en el calendario</>");
                    }

                    if(eventcolor=="darkcyan"){
                        eventdes = "Predefensa";
                        type=1;
                        //if(0 in $("#predefensadate"))
                            $("#suggest").after("<li class='list-group-item' id='predefensadate'></li>");
                            $("#predefensadate").append("<ul></ul>");
                            $("#predefensadate ul").append("<li>Predefensa</li>");
                            $("#predefensadate ul").append("<li>Inicio</li>");
                            $("#predefensadate ul").append("<li>Fin</li>");                                                        
                    }
                    if(eventcolor=="blue"){
                        eventdes = "Defensa";
                        type=2;
                        //if(0 in $("#defensadate"))
                            $("#eventdet").append("<li class='list-group-item' id='defensadate'>Defensa</li>");
                    }
                    */
                });

                function loadtema () {
                    idtema = $('#temas').val();
                    var id = $('#temas').val();
                    Lista.reset();
                    console.log("RESET");
                    if(id!="sel"){
                        ajx({
                            data:{
                                f:'Comision_data',
                                id: id
                            },
                            ok:function(data1){

                                if("guia" in data1.data){
                                    var prof = data1.data.guia.id;
                                    var name = data1.data.guia.name;

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

                                if(1 in data1.data){
                                    var prof1 = data1.data[1].id;
                                    var name1 = data1.data[1].name;
                                    var status1 = data1.data[1].status;

                                    ajx({
                                        data:{
                                            f:'Eventos_profe',
                                            prof: prof1
                                        },
                                        ok:function(data){
                                            console.log(name1);
                                            Lista.add(prof1,data.data,name1,"comision",status1);
                                            Lista.addoriginal(prof1);
                                        }
                                    });
                                }

                                if(2 in data1.data){
                                    var prof2 = data1.data[2].id;
                                
                                    ajx({
                                        data:{
                                            f:'Eventos_profe',
                                            prof: prof2
                                        },
                                        ok:function(data){
                                            var name2 = data1.data[2].name;
                                            var status2 = data1.data[2].status;
                                            console.log(name2);
                                            Lista.add(prof2,data.data,name2,"comision",status2);
                                            Lista.addoriginal(prof2);
                                        }
                                    });
                                }

                                if(3 in data1.data){
                                    var prof3 = data1.data[3].id;
                                    ajx({
                                        data:{
                                            f:'Eventos_profe',
                                            prof: prof3
                                        },
                                        ok:function(data){
                                            var name3 = data1.data[3].name;
                                            var status3 = data1.data[3].status;
                                            console.log(name3);
                                            Lista.add(prof3,data.data,name3,"comision",status3);
                                            Lista.addoriginal(prof3);
                                        }
                                    });
                                }

                                //Lista.add(prof,data.data,name);

                                //agregar tareas
                                if("tareas" in data1){
                                    for(i in data1.tareas){
                                        $('#calendar').fullCalendar('renderEvent', data1.tareas[i], true);
                                        console.log(data1.tareas[i].title);
                                    }
                                }

                            }
                        });
                    } 
                }
                
                $('#temas').on("change",function() {


                    $("#comisionbox, #fechaprebox, #fechadefbox").show();
                    loadtema();

                })
                
      
                 
                    
                Lista = new Eventos("#list","#calendar");

                $("#add").on("click",function() {
                    var prof = $("#prof").val();
                    var name = $("#buscarprofesor").val();

                    ajx({
                        data:{
                            f:'Eventos_profe',
                            prof: prof
                        },
                        ok:function(data){

                            Lista.add(prof,data.data,name,"comision");

                        }
                    });

                    
                })

                $( "#buscarprofesor" ).autocomplete({
                    minLength: 2,
                    source: "th/profesores",
                    focus: function( event, ui ) {
                    //$( "#buscarprofesor" ).val( ui.item.label );
                    return false;
                    },
                    select: function( event, ui ) {
                    $( "#buscarprofesor" ).val( ui.item.label );
                    $( "#prof" ).val( ui.item.value );
                    return false;
                }
                })
                .autocomplete( "instance" )._renderItem = function( ul, item ) {
                    return $( "<li>" )
                    .append( "<a>" + item.label +" ("+item.comisions+" comisiones)</a>" )
                    .appendTo( ul );
                };

                $("#save").on("click",function() {
                    var changes = Lista.changes();
                    var snews = "";
                    var sdels = "";
                    var id = $('#temas').val();
                    for(news in changes.news){
                        snews += news+",";
                    }
                    for(dels in changes.deleted){
                        sdels += dels+",";   
                    }

                    ajx({
                        data:{
                            f:'Comision_guardar',
                            id: id,
                            news: snews,
                            dels: sdels
                        },
                        ok:function(data) {
                            loadtema();
                        }
                    });

                })

            });

            

            </script>
            
        </div>

                <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Calendario</strong></div>
                <div class="panel-body">
                    <div id="calendar"></div>
                </div>
            </div>

            <script type="text/javascript">



            var eventcolor = "";

            var add = function(start, end) {
                var id = $('#temas').val();
                //verificar que se sepa que tipo de evento es
                if(id!="sel" && eventcolor!=""){
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
                            $('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
            
                            $('#calendar').fullCalendar('unselect');

                            if(eventdes=="Predefensa"){
                                $("#eventdetpre .inicio").html(start.format());
                                $("#eventdetpre .fin").html(end.format());
                            }
                            if(eventdes=="Defensa"){
                                $("#eventdetdef .inicio").html(start.format());
                                $("#eventdetdef .fin").html(end.format());
                            }
                        },
                        error:function(data){
                            alert(data)
                            $('#calendar').fullCalendar('unselect');
                        }
                    });

                }

            }

            var edit = function(event, delta, error){
                console.log("a");

                var start = event.start;
                var end = event.end;
                var id = event.id;

                if(event.detail==idtema){
                    var res = confirm("¿Realmente desea mover "+event.title+" a "+event.start.format()+" hasta "+event.end.format()+"?")
                    if(res){
                        
                        ajx({
                            data:{
                                f:'Eventos_editar',
                                id: id,
                                start: start.format(),
                                end: end.format()
                            },
                            ok:function(data){},
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
                if(event.detail==idtema){
                    var del = confirm("¿Borrar "+event.detail+"?");
                    if(del==true){
                        ajx({
                            data:{
                                f:'Eventos_borrar',
                                id: event.id
                            },
                            ok:function(data){
                                $('#calendar').fullCalendar('removeEvents',event.id);
                            }
                        });
                    }
                }
            }

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

            $(document).ready(function() {
            
                $("#calendar").fullCalendar({
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
                    //'defaultView':'agendaWeek',

                    'timezone':"-3:00",
                    selectable: true,
                    //selectHelper: true,
                    select: add,
                    eventResize: edit,
                    eventDrop: edit,
                    eventClick: click,
                    editable: true,
                });

                

            });

            </script>
        </div>
    </div>
</div>