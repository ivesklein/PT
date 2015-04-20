<span class="ui-select">
<select>
<option value="0">Seleccione</option>
<?php 

	$users = Permission::wherePermission("P");
	
	foreach ($users->with("staff")->get() as $value) {
		if(!empty($value->staff)){
			$name = $value->staff->name." ".$value->staff->surname;

			$value = $value->staff->wc_id;

			echo "<option value='$value'>$name</option>";
		}
	}



?>
</select>
</span>