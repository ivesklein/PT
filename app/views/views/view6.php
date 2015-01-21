<div class="page page-table">

    <div class="panel panel-default confirmar">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Temas a Confirmar</strong></div>
        <?=$table?>
    </div>
    <script type="text/javascript">
        $(".confirmar").on("click", ".yes",function() {
            var id = $(this).parent().parent().attr("n");
            alert("confirmar "+id);
        });
        $(".confirmar").on("click", ".no",function() {
            var id = $(this).parent().parent().attr("n");
            alert("rechazar "+id);
        });
    </script>
</div>