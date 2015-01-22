<span class="ui-select">
<select>
<option value="0">Seleccione</option>
<?php 

	$users = Permission::wherePermission("P");
	
	foreach ($users->with("staff")->get() as $value) {
		
		$name = $value->staff->name." ".$value->staff->surname;

		$value = $value->staff->pm_uid;

		echo "<option value='$value'>$name</option>";
	
	}



?>
</select>
</span>