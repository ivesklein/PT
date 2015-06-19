<div class="page page-table">

    <div class="panel panel-default error" style="display:none;">
        <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Error</strong></div>
        <div class="panel-body">
            <div class="alert alert-danger"></div>
        </div>
    </div>

    <div class="panel panel-default tabla">
        <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Proyecto</strong></div>
        <div class="panel-body">
            <div class="form-horizontal">
                <div class="form-group"><label for="" class="col-sm-2">Tema</label><div class="col-sm-10" data-table="Subject" data-col="subject"></div></div>
                <div class="form-group"><label for="" class="col-sm-2">Profesor Guía</label><div class="col-sm-4" data-table="Subject" data-col="adviser"></div><label for="" class="col-sm-2">Estado</label><div class="col-sm-4" data-table="Subject" data-col="status"></div></div>
                <div class="form-group"><label for="" class="col-sm-2">Semestre</label><div class="col-sm-10" data-table="Subject" data-col="periodo"></div></div>
            </div>
        </div>
    </div>

    <div class="panel panel-default tabla">
        <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Memoristas</strong></div>
        <div class="panel-body">
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="" class="col-sm-2">Alumno 1</label><div class="col-sm-4"  data-table="Subject" data-col="student1"></div>
                    <label for="" class="col-sm-2">Alumno 2</label><div class="col-sm-4" data-table="Subject" data-col="student2"></div>
                </div>
            </div>
        </div>
    </div>    

    <div class="panel panel-default tabla">
        <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Comisión</strong></div>
        <div class="panel-body">
            <div class="form-horizontal">
                <div class="form-group"><label for="" class="col-sm-2">Presidente</label><div class="col-sm-10" data-col="pr"></div></div>
                <div class="form-group"><label for="" class="col-sm-2">Invitado</label><div class="col-sm-10" data-col="in"></div></div>
                <div class="form-group"><label for="" class="col-sm-2">Fecha Predefensa</label><div class="col-sm-10" data-col="pre"></div></div>
                <div class="form-group"><label for="" class="col-sm-2">Fechas Defensa</label><div class="col-sm-10" data-col="def"></div></div>                
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
                    f:"Reportes_gmtdata",
                    id:id
                },
                ok:function(data) {
                    $('[data-col="subject"]').html(textedit(data.subject))
                    $('[data-col="adviser"]').html(textedit(data.adviser))
                    $('[data-col="status"]').html(textedit(data.status))
                    $('[data-col="periodo"]').html(textedit(data.periodo))
                    $('[data-col="student1"]').html(textedit(data.student1))
                    $('[data-col="student2"]').html(textedit(data.student2))

                    $('[data-col="pr"]').html(textedit(data.pr))
                    $('[data-col="in"]').html(textedit(data.in))

                    $('[data-col="pre"]').html(textedit(data.pre))
                    $('[data-col="def"]').html(textedit(data.def))

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

    </script>

</div>