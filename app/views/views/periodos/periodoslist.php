<?php //lista periodos ?>
<div class="page page-table">

    <div class="panel panel-default periodos">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Periodos</strong></div>
        <?=$table?>
    </div>

    <script type="text/javascript">

        $(".periodos").on("click", ".activate",function() {
            var id = $(this).parent().parent().attr("n");
            
            var datos = {
                "f":"ajxactivateperiod",
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
                "f":"ajxcloseperiod",
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