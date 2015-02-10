<?php //lista profesores ?>
<div class="page page-table">

    <div class="panel panel-default" id="profesorlist">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Usuarios</strong></div>
        <?=$table?>
    </div>

    <script type="text/javascript">

        var prevval = 0;

        $("#profesorlist").on("focus", "select", function() {
            prevval = $(this).val();
        });

        $("#profesorlist").on("change", "select", function() {

            var val = $(this).val();
            var id = $(this).parent().parent().parent().attr("n");
            var name = $($(this).parent().parent().parent().children()[0]).html()+" "+$($(this).parent().parent().parent().children()[1]).html();
            //ar name = "yo";
            var res = confirm("¿Realmente desea atribuir permisos de "+val+" a "+name+"?");
            if(res==true){
                ajx({
                    data:{
                        f:'ajxeditrol',
                        id: id,
                        rol: val
                    },
                    ok:function(data){
                        
                    }
                });
            }else{
                $(this).val(prevval);
            }

        })

    </script>
</div>