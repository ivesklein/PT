<div class="page page-table">

    <link rel="stylesheet" href="fullcalendar/fullcalendar.css" />
    <link rel="stylesheet" href="jui/jquery-ui.min.css" />
    <script src="fullcalendar/lib/moment.min.js"></script>
    <script src="fullcalendar/fullcalendar.js"></script>
    <script src="fullcalendar/lang/es.js"></script>
    <!--script src="js/bloodhound.min.js"></script>
    <script src="js/typeahead.jquery.min.js"></script-->
    <script src="jui/jquery-ui.min.js"></script>
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Calendario</strong></div>
                <div class="panel-body">
                    <div id="calendar"></div>
                </div>
            </div>

            <script type="text/javascript">

            var eventcolor = "black";
            var eventdes = "Ocupado";

            var add = function(start, end) {
                var title = prompt('Event Title:');
                var eventData;
                if(title){
                    ajx({
                        data:{
                            f:'ajxnewevent',
                            title: eventdes,
                            detail: title,
                            start: start.format(),
                            end: end.format(),
                            color: eventcolor
                        },
                        ok:function(id) {
                            eventData = {
                                //id: id.ok,
                                title: eventdes,
                                detail: title,
                                start: start,
                                end: end,
                                color: eventcolor
                            };
                            console.log(eventData);
                            $('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
                        
                            $('#calendar').fullCalendar('unselect');
                        }
                    });

                }

            }

            var edit = function(event, delta, error){

                var start = event.start;
                var end = event.end;
                var id = event.id;

                ajx({
                    data:{
                        f:'ajxeditevent',
                        id: id,
                        start: start.format(),
                        end: end.format()
                    },
                    ok:function(data){},
                    error:error
                });

            }

            var click = function(event){
                var del = confirm("¿Borrar "+event.detail+"?");
                if(del==true){
                    ajx({
                        data:{
                            f:'ajxdelevent',
                            id: event.id
                        },
                        ok:function(data){
                            $('#calendar').fullCalendar('removeEvents',event.id);
                        }
                    });
                }

            }

            var load = function() {
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
                });
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
                        start:"8:00",
                        end:"20:00",
                        dow:[1,2,3,4,5]
                    },
                    'slotDuration':'00:15:00',
                    'snapDuration':'00:05:00',
                    'lang':'es',

                    'timezone':"-3:00",
                    selectable: true,
                    //selectHelper: true,
                    
                    //select: add,
                    //eventResize: edit,
                    //eventDrop: edit,
                    //eventClick: click,
                    //editable: true,
                });

                load();

            });

            </script>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Controles</strong></div>
                <div class="panel-body form-horizontal">
                    <div class="form-group">
                        
                        <label class="col-sm-4">Tipo de evento</label>

                        <!--dl class='cl-horizontal col-sm-8' id="tipoevento">
                            <dd-->
                            <div class="col-sm-8" id="tipoevento">
                                <label class="ui-radio"><input type="radio" name="tipo" value="darkcyan" checked></input><span style="background:darkcyan;padding-right:10px;color:white;font-size:.85em;border-radius:3px;border:1px solid darkcyan;">Predefensa</span></label>
                                <label class="ui-radio"><input type="radio" name="tipo" value="blue"></input><span style="background:blue;padding-right:10px;color:white;font-size:.85em;border-radius:3px;border:1px solid blue;">Defensa</span></label>
                            </div>
                            <!--/dd>

                        </dl-->
                    </div>
                    <hr></hr>
                    <div class="form-group">
                        <label for="tema" class="col-sm-4">Seleccionar Tema</label>
                        <div class="col-sm-8">
                            <span class="ui-select">
                                <select name="tema" id="temas">
                                    <option>Mustard</option>
                                    <option>Ketchup</option>
                                    <option>Barbecue</option>
                                </select>
                            </span>
                        </div>
                    </div>
                    <hr></hr>
                    <h3>Profesores</h3>
                    <div class="form-group ">
                        <ul class="list-group col-sm-10 col-sm-offset-1" id="list">
       
                        </ul>
                        <div class="input-group col-sm-10 col-sm-offset-1">
                            <span class="input-group-addon">a</span>
                            <input id="buscarprofesor" type="text" class="form-control" placeholder="buscar">
                            <input id="prof" type="hidden">
                            <span class="input-group-btn">
                                <button id="add" class="btn btn-default" type="button">Agregar</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <script type="text/javascript">

            var Eventos = function(listel, calel){

                var yo = this;
                yo.profesores = {};
                yo.listel = listel;
                yo.calel = calel;

                yo.add = function(profesor, eventos, nombre) {
                    if(profesor in yo.profesores){}else{
                        yo.profesores[profesor] = eventos;
                        for(n in eventos){
                            var evento = eventos[n];
                            $(calel).fullCalendar('renderEvent', evento, true);
                        }
                        $(listel).append('<li id="P'+profesor+'" class="list-group-item">'+nombre+'</li>');
                    }
                }

                yo.remove = function(profesor) {
                    $(listel+" #P"+profesor).remove();
                    for(n in yo.profesores[profesor]){
                        var evento = yo.profesores[profesor][n];
                        $(calel).fullCalendar('removeEvents', evento.id);
                    }
                    delete(yo.profesores[profesor]);

                }

                yo.reset = function() {
                    for (i in yo.profesores) {
                        $(listel+" #P"+i).remove();
                    };
                    yo.profesores = {};
                    $(calel).fullCalendar('removeEvents');
                }



            }

            </script>
            <script type="text/javascript">
                $('#tipoevento input[type="radio"]').on("change",function() {
                    eventcolor = $(this).val();
                    $('#temas').html("");
                    if(eventcolor=="darkcyan"){
                        eventdes = "Predefensa";
                        ajx({
                            data:{
                                f:'ajxdefensas',
                                type: 1
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
                    }
                    if(eventcolor=="blue"){
                        eventdes = "Defensa";
                    }
                });
      
                 $(function() {
                    
                    Lista = new Eventos("#list","#calendar");

                    $("#add").on("click",function() {
                        var prof = $("#prof").val();
                        var name = $("#buscarprofesor").val();

                        ajx({
                            data:{
                                f:'ajxprofevents',
                                prof: prof
                            },
                            ok:function(data){

                                Lista.add(prof,data.data,name);

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
                        .append( "<a>" + item.label + "</a>" )
                        .appendTo( ul );
                    };
                });

            </script>
            
        </div>
    </div>
</div>