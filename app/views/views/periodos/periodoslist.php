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

    </script>

</div>