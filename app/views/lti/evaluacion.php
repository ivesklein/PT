<?php echo View::make('lti.header'); ?>


<div class="panel panel-default" style="margin-right: 7px;">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-check"></span> Evaluar Profesor Guía <?=$name?></strong></div>
        <div class="panel-body">
    	    <h4>Califique las siguientes afirmaciones con nota entre 1 (totalmente en desacuerdo) y 7 (totalmente de acuerdo)</h4>
            <div class="panel-body">
                <form action="../lti" method="POST" class="form-horizontal" id="preguntas">
                    <input type="hidden" name="f" value="feedback">
                    <div class="form-group">
                        <label for="" class="col-sm-2">Comentarios</label>
                        <div class="col-sm-10">
                            <textarea class="form-control feedback" name="coments"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Enviar" class="btn btn-success submit">
                        </div>
                    </div>
                </form>
            </div>
        </div>


</div>

<script src="../scripts/vendor.js"></script>
<script src="../scripts/ui.js"></script>
<script>
    function add(name, title){
        return '<div class="form-group"><label for="" class="col-sm-6">'+title+'</label><div class="col-sm-6"><label class="radio-inline"><input type="radio" name="'+name+'" value="1">1</label><label class="radio-inline"><input type="radio" name="'+name+'" value="2">2</label><label class="radio-inline"><input type="radio" name="'+name+'" value="3">3</label><label class="radio-inline"><input type="radio" name="'+name+'" value="4">4</label><label class="radio-inline"><input type="radio" name="'+name+'" value="5">5</label><label class="radio-inline"><input type="radio" name="'+name+'" value="6">6</label><label class="radio-inline"><input type="radio" name="'+name+'" value="7">7</label></div></div>';
    }

    $(function(){
        $("#preguntas").prepend(add("p8","En general mi profesor guía cumplió con los compromisos acordados"));  
        $("#preguntas").prepend(add("p7","El trato de mi profesor guía fue cordial"));  
        $("#preguntas").prepend(add("p6","Recibí las notas y el feedback de mis entregas oportunamente"));  
        $("#preguntas").prepend(add("p5","Recibí ayuda para preparar la defensa de mi memoria"));  
        $("#preguntas").prepend(add("p4","Recibí ayuda en términos del formato de la memoria"));  
        $("#preguntas").prepend(add("p3","Recibí ayuda en términos de los contenidos esperados de la memoria"));  
        $("#preguntas").prepend(add("p2","Las reuniones tuvieron una duración adecuada"));    
        $("#preguntas").prepend(add("p1","Tuve reuniones periódicamente (entre 1 y 2 veces al mes)"));    
    })
    
</script>
<?php echo View::make('lti.footer'); ?>