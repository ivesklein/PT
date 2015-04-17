<div class="page page-table">

    <div class="panel panel-default confirmar">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Guias a Confirmar</strong></div>
        <?=$table?>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-check"></span> Guias Confirmadas</strong></div>
        <?=$table2?>
    </div>
    <script type="text/javascript">
        $(".confirmar").on("click", ".yes",function() {
            var id = $(this).parent().parent().attr("n");
            var datos = {
                "f":"Memorias_confirmarguia",
                "res":1,
                "id":id,
                "mes":""
            };
            ajx({
                data:datos,
                ok:function(data) {
                    location.reload();
                }
            });
        });
        $(".confirmar").on("click", ".no",function() {
            var id = $(this).parent().parent().attr("n");
            var mes = prompt("Causa del Rechazo (se enviará a los alumnos)");
            var datos = {
                "f":"Memorias_confirmarguia",
                "res":0,
                "id":id,
                "mes":mes
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