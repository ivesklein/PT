<div class="page page-table">

    <link rel="stylesheet" href="fullcalendar/fullcalendar.css" />
    <script src="fullcalendar/lib/moment.min.js"></script>
    <script src="fullcalendar/fullcalendar.js"></script>
    <script src="fullcalendar/lang/es.js"></script>
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

                if(title){
                    
                    var eventData;
                    ajx({
                        data:{
                            f:'Eventos_nuevo',
                            title: eventdes,
                            detail: title,
                            start: start.format(),
                            end: end.format(),
                            color: eventcolor
                        },
                        ok:function(id) {
                            eventData = {
                                id: id.ok,
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
                        f:'Eventos_editar',
                        id: id,
                        start: start.format(),
                        end: end.format()
                    },
                    ok:function(data){},
                    error:error
                });

            }

            var click = function(event){
                console.log(event.editable);
                if(event.editable!=false){
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
                ajx({
                    data:{
                        f:'Eventos_myevents'
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
                    select: add,
                    eventResize: edit,
                    eventDrop: edit,
                    eventClick: click,
                    editable: true,
                });

                load();

            });

            </script>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Controles</strong></div>
                <div class="panel-body">
                    <dl class='cl-horizontal' id="tipoevento">
                        <dt>Tipo de evento</dt>
                        <dd>
                            <label class="ui-radio"><input type="radio" name="tipo" value="black" checked></input><span style="background:black;padding-right:10px;color:white;font-size:.85em;border-radius:3px;border:1px solid black;">Ocupado</span></label>
                            <label class="ui-radio"><input type="radio" name="tipo" value="green"></input><span style="background:green;padding-right:10px;color:white;font-size:.85em;border-radius:3px;border:1px solid green;">Disponible</span></label>
                        </dd>

                    </dl>

                </div>
            </div>

            <script type="text/javascript">
                $('#tipoevento input[type="radio"]').on("change",function() {
                    eventcolor = $(this).val();
                    if(eventcolor=="black"){
                        eventdes = "Ocupado";
                    }
                    if(eventcolor=="green"){
                        eventdes = "Disponible";
                    }
                })
            </script>
            
        </div>
    </div>
</div>