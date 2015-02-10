<span class="ui-select">
<select>
<?php
if(isset($items)){

foreach ($items as $item) {
	echo View::make('html.dropitem',$item);
}

}
?>
</select>
</span>