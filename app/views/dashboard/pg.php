<div class="page page-table">

    <link rel="stylesheet" href="fullcalendar/fullcalendar.css" />
    <script src="fullcalendar/lib/moment.min.js"></script>
    <script src="fullcalendar/fullcalendar.js"></script>
    <script src="fullcalendar/lang/es.js"></script>
    
    <div class="col-md-4">
            
        <div class="panel panel-default">
            <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Guías</strong></div>
            <ul class="list-unstyled list-info" id="guias"></ul>
        </div>
    
    
        <div class="panel panel-default">
            <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Comisiones</strong></div>
            <ul class="list-unstyled list-info" id="comisiones"></ul>
        </div>
        
    </div>
    <div class="col-md-8">
        
        <div class="panel panel-default">
            <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Calendario</strong></div>
            <div class="panel-body">
                <div id="calendar"></div>
            </div>
        </div>
    
    
        <div class="panel panel-default">
            <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Eventos</strong></div>
            <table class="table" id="lista">
                <thead>
                    <tr>
                        <th>Evento</th>
                        <th>En/Hace</th>
                        <th>Empieza</th>
                        <th>Termina</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        
    </div>

</div>
<script type="text/javascript">
    
    function lidom (subj) {
        return "<li id='"+subj.id+"'>"+subj.grupo+"</li>"
    }

    function lilinkiddom (subj) {
        return "<li id='"+subj.id+"'><a href='#/evaluartarea/"+subj.id+"'>"+subj.grupo+"</a></li>"
    }

    function lilinkdom (subj, link) {
        return "<li id='"+subj.id+"'><a href='#/"+link+"'>"+subj.grupo+" <span class='badge badge-danger main-badge'>!</span></a></li>"
    }

    $(function(){
        ajx({
            data:{
            "f":"Dashboard_myguiascomisiones"
            },
            ok:function(data) {
                if("guias" in data){
                    for(guia in data.guias){
                        $("#guias").prepend(lilinkiddom(data.guias[guia]))
                    }
                }
                if("guiaswait" in data){
                    for(guia in data.guiaswait){
                        $("#guias").append(lilinkdom(data.guiaswait[guia], "confirmarguia"))
                    }
                }
                if("comisiones" in data){
                    for(subj in data.comisiones){
                        $("#comisiones").prepend(lidom(data.comisiones[subj]))
                    }
                }
                if("comisioneswait" in data){
                    for(subj in data.comisioneswait){
                        $("#comisiones").append(lilinkdom(data.comisioneswait[subj], "confirmarcomision"))
                    }
                }
            }
        });
            
        var load = function() {
            ajx({
                data:{
                    f:'Eventos_myevents'
                },
                ok:function(data) {
                    for(n in data.data){
                        var evento = data.data[n];
                        $('#calendar').fullCalendar('renderEvent', evento, true);

                        $("#lista tbody").append("<tr style='color:"+event.color+";'><td>"+evento.title+"</td><td>"+evento.delta+"</td><td>"+evento.start.format()+"</td><td>"+evento.end.format()+"</td></tr>");
                    }
                }
            });
        }

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
            selectable: false,
            //selectHelper: true,
            //select: add,
            //eventResize: edit,
            //eventDrop: edit,
            //eventClick: click,
            editable: false,
        });

        load();



    });
</script>