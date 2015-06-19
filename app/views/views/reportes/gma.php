<div class="page page-table">

    <div class="panel panel-default error" style="display:none;">
        <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Error</strong></div>
        <div class="panel-body">
            <div class="alert alert-danger"></div>
        </div>
    </div>

    <div class="panel panel-default tabla">
        <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Alumno</strong></div>
        <div class="panel-body">
            <div class="form-horizontal">
                <div class="form-group"><label for="" class="col-sm-2">Email</label><div class="col-sm-10" data-table="Student" data-col="wc_id"></div></div>
                <div class="form-group"><label for="" class="col-sm-2">RUN</label><div class="col-sm-10" data-table="Student" data-col="run"></div></div>
                <div class="form-group"><label for="" class="col-sm-2">Nombre</label><div class="col-sm-10" data-table="Student" data-col="name"></div></div>
                <div class="form-group"><label for="" class="col-sm-2">Apellido</label><div class="col-sm-10" data-table="Student" data-col="surname"></div></div>
                <div class="form-group"><label for="" class="col-sm-2">Memoria</label><div class="col-sm-10" data-table="Student" data-col="subject_id"></div></div>
                <div class="form-group"><label for="" class="col-sm-2">Estado</label><div class="col-sm-10" data-table="Student" data-col="status"></div></div>
            </div>
        </div>
    </div>

    <div class="panel panel-default tabla">
        <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Notas</strong></div>
        <div class="panel-body">
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="" class="col-sm-2">Entrega 1</label><div class="col-sm-4"></div>
                    <label for="" class="col-sm-2">Entrega 2</label><div class="col-sm-4"></div>
                </div>
            </div>
        </div>
    </div>    

    <div class="panel panel-default tabla">
        <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Hoja de Ruta</strong></div>
        <div class="panel-body">
            <div class="form-horizontal">
              
            </div>
        </div>
    </div>  

    <div class="panel panel-default tabla">
        <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Expediente Rezagado</strong></div>
        <div class="panel-body">
            <div class="form-horizontal">
              
            </div>
        </div>
    </div>   
    
    <link rel="stylesheet" href="jui/jquery-ui.min.css" />
    <script src="jui/jquery-ui.min.js"></script>
    <script src="js/jquery.tablesorter.min.js"></script>
    <script src="js/table.js"></script>
    <script type="text/javascript">

        function jcall (id) {
            console.log(id);
            ajx({
                data:{
                    f:"Reportes_gmadata",
                    id:id
                },
                ok:function(data) {
                    $('[data-col="wc_id"]').html(text(data.wc_id))
                    $('[data-col="run"]').html(textedit(data.run))
                    $('[data-col="name"]').html(textedit(data.name))
                    $('[data-col="surname"]').html(textedit(data.surname))
                    $('[data-col="subject_id"]').html(textedit(data.subject))
                    $('[data-col="status"]').html(textedit(data.status))

                    
                },
                error:function(data) {
                    $(".error .alert").html(data)
                    $(".tabla").hide()
                    $(".error").show()
                }
            })
        }

        function textedit (text) {
            return '<span>'+text+'</span><div class="btn edit"><span class="fa fa-pencil"></span></div>';
        }
        function text (text) {
            return '<span>'+text+'</span>';
        }

    </script>

</div>