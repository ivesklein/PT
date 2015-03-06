<div class="page page-table">

    <div class="panel panel-default asignar">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span> Asignar Profesor Guía</strong></div>
        <?=$table?>
    </div>
    <script type="text/javascript">
        $(".asignar").on("click", ".add",function() {
            var id = $(this).parent().parent().attr("n");
            var prof = $(this).parent().prev().find("select").val();
            if(prof==0){
                alert("Seleccione Profesor");
            }else{
                var datos = {
                    "f":"Memorias_asignarguia",
                    "prof":prof,
                    "id":id
                };
                ajx({
                    data:datos,
                    ok:function(data) {
                        location.reload();
                    }
                });
            }
        });
    </script>
</div>