<div class="page page-table">

    <div class="panel panel-default confirmar">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Comisiones a Confirmar</strong></div>
        <?=$table?>
    </div>
    <script type="text/javascript">
        $(".confirmar").on("click", ".yes",function() {
            var id = $(this).parent().parent().attr("n");
            var datos = {
                "f":"ajxconfirmarcomision",
                "res":1,
                "id":id
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
            var datos = {
                "f":"ajxconfirmarcomision",
                "res":0,
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