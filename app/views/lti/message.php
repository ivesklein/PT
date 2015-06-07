<?php echo View::make('lti.header'); ?>


<div class="panel panel-default" style="margin-right: 7px;">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-check"></span> <?php if(isset($title)){echo $title;} ?></strong></div>
        <div class="panel-body row">
    	    <div class="panel-body">
                <div class="alert alert-<?php if(isset($color)){echo $color;}else{echo"warning";} ?>">
                    <?php if(isset($contenido)){echo $contenido;} ?>
                </div>
            </div>
        </div>
</div>

<script src="../scripts/vendor.js"></script>
<script src="../scripts/ui.js"></script>
<script type="text/javascript">

</script>

<?php echo View::make('lti.footer'); ?>