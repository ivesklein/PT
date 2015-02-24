<?php echo View::make('lti.header'); ?>


<div class="panel panel-default" style="margin-right: 7px;">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-check"></span> Evaluar Profesor Guía <?=$name?></strong></div>
        <div class="panel-body row">
    	    <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="" class="col-sm-2">Nota</label>
                        <div class="col-sm-10">
                            <input class="form-control nota" type="number" min="1" max="7" step="0.1" value="4"></input>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2">Feedback</label>
                        <div class="col-sm-10">
                            <textarea class="form-control feedback"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="btn btn-success submit">Enviar</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

<script src="../scripts/vendor.js"></script>
<script src="../scripts/ui.js"></script>
<script type="text/javascript">

</script>

<?php echo View::make('lti.footer'); ?>