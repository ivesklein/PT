<?php //crear periodo ?>
<div class="page page-table">

    <section class="panel panel-default">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-plus"></span> Crear Semestre</strong></div>
        <div class="panel-body" data-ng-controller="DatepickerDemoCtrl">
            
            <form class="form-horizontal" method="POST" action="#/vista4">
                <input type="hidden" name="f" value="periodos"></input>
                <div class="form-group">
                    <label for="" class="col-sm-2">Nombre Semestre</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control">
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