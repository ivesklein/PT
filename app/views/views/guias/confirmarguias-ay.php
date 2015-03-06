<div class="page page-table">

    <div class="panel panel-default confirmar">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Temas a Confirmar</strong></div>
        <?=$table?>
    </div>
    <script type="text/javascript">
        $(".confirmar").on("click", ".yes",function() {
            var id = $(this).parent().parent().attr("n");
            var prof = $(this).parent().prev().html();
            var datos = {
                "f":"Memorias_confirmarguias",
                "res":1,
                "id":id,
                "prof":prof,
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
            var prof = $(this).parent().prev().html();
            var mes = prompt("Causa del Rechazo (se enviará a los alumnos)");
            var datos = {
                "f":"Memorias_confirmarguias",
                "res":0,
                "id":id,
                "prof":prof,
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