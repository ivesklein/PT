<div class="page page-table">

    <link rel="stylesheet" href="fullcalendar/fullcalendar.css" />
    <script src="fullcalendar/lib/moment.min.js"></script>
    <script src="fullcalendar/fullcalendar.js"></script>
    <script src="fullcalendar/lang/es.js"></script>
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Temas de Memoria</strong></div>
                <div class="panel-body">
                    <div id="calendar"></div>
                </div>
            </div>

            <script type="text/javascript">

            $(document).ready(function() {
                $("#calendar").fullCalendar({
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
                    'slotDuration':'00:05:00',
                    'snapDuration':'00:05:00',
                    'lang':'es',

                    'timezone':"-3:00",

                });
            });

            </script>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Controles</strong></div>
                <div class="panel-body">

                </div>
            </div>
            
        </div>
    </div>
</div>