<div class="page page-table">

    <section class="panel panel-default">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-plus"></span> Agregar Periodo</strong></div>
        <div class="panel-body" data-ng-controller="DatepickerDemoCtrl">
            
            <form class="form-horizontal" method="POST" action="#/vista2">
                
                <div class="form-group">
                    <label for="" class="col-sm-2">Nombre Periodo</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control">
                    </div>
                </div>



                <div class="form-group">
                    <label for="" class="col-sm-2">Fecha uno</label>
                    <div class="col-sm-4">
                        <div class="input-group ui-datepicker">
                            <input type="text" 
                                   name="fecha1"
                                   class="form-control"
                                   datepicker-popup="{{format}}"
                                   ng-model="dt"
                                   is-open="opened"
                                   min="minDate"
                                   max="'2015-06-22'"
                                   datepicker-options="dateOptions" 
                                   date-disabled="disabled(date, mode)"
                                   ng-required="true" 
                                   close-text="Close">
                            <span class="input-group-addon" ng-click="open($event)"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2">Fecha dos</label>
                    <div class="col-sm-4">
                        <div class="input-group ui-datepicker">
                            <input type="text" 
                                   name="fecha1"
                                   class="form-control"
                                   datepicker-popup="{{format}}"
                                   ng-model="dt2"
                                   is-open="opened"
                                   min="minDate"
                                   max="'2015-06-22'"
                                   datepicker-options="dateOptions" 
                                   date-disabled="disabled(date, mode)"
                                   ng-required="true" 
                                   close-text="Close">
                            <span class="input-group-addon" ng-click="open($event)"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" class="btn btn-success" value="Agregar">
                    </div>
                </div>



        </div>
    </section>
    <!-- end Datepicker -->

</div>