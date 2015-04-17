<?php //lista periodos ?>
<div class="page page-table">

    <div class="panel panel-default periodos">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Semestres</strong></div>
        <?=$table?>
    </div>

    <script type="text/javascript">

        $(".periodos").on("click", ".activate",function() {
            var id = $(this).parent().parent().attr("n");
            
            var datos = {
                "f":"Periodos_activar",
                "id":id
            };
            ajx({
                data:datos,
                ok:function(data) {
                    location.reload();
                }
            });
        
        });

        $(".periodos").on("click", ".closeper",function() {
            var id = $(this).parent().parent().attr("n");
            
            var datos = {
                "f":"Periodos_cerrar",
                "id":id
            };
            ajx({
                data:datos,
                ok:function(data) {
                    location.reload();
                }
            });
        
        });

        $(function(){
            $('.periodos tbody tr').each(function(){$('.periodos tbody').prepend(this)})
        })

    </script>

    <section class="panel panel-default">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-plus"></span> Crear Semestre</strong></div>
        <div class="panel-body" data-ng-controller="DatepickerDemoCtrl">
            
            <form class="form-horizontal" method="POST" action="#/periodos">
                <input type="hidden" name="f" value="Periodos_crear"></input>
                <div class="form-group">
                    <label for="" class="col-sm-2">Nombre Semestre</label>
                    <div class="col-sm-8">
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="col-sm-2">
                        <input type="submit" class="btn btn-success" value="Agregar">
                    </div>
                </div>
            </form>
        </div>
    </section>

</div>