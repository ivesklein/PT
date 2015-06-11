<style>
table{
border-collapse: collapse;
}

th, td {
border: 1px solid black;border-collapse: collapse;padding: 5px;
}
th, td {
    padding: 5px;
}
</style>
Estimado profesor <?=isset($pg)?$pg:"*";?>,<br>
<br>
Junto con agradecer su participación como profesor guía de alumnos memoristas de las carreras de Ingeniería Civil de nuestra Facultad, remito a usted las evaluaciones que ellos hicieron al terminar el proceso de titulación del periodo <?=isset($periodo)?$periodo:"*";?>.<br>
<br>
Espero que estas evaluaciones representen una retroalimentación valiosa para usted.<br>
<br>
N° de evaluadores: <?=isset($n)?$n:"*";?><br>
<br>
<table style="border-collapse: collapse;">
	<tr>
		<td style="border: 0px solid black;border-collapse: collapse;padding: 5px;"></td>
		<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;">Nota Promedio</td>
	</tr>
	<tr>
		<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;">Tuve reuniones periódicamente (entre 1 y 2 veces al mes)</td>
		<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;"><?=isset($table->p1)?$table->p1:"*";?></td>
	</tr>
	<tr>
		<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;">Las reuniones tuvieron una duración adecuada</td>
		<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;"><?=isset($table->p2)?$table->p2:"*";?></td>
	</tr>
	<tr>
		<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;">Recibí ayuda en términos de los contenidos esperados de la memoria</td>
		<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;"><?=isset($table->p3)?$table->p3:"*";?></td>
	</tr>
	<tr>
		<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;">Recibí ayuda en términos del formato de la memoria</td>
		<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;"><?=isset($table->p4)?$table->p4:"*";?></td>
	</tr>
	<tr>
		<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;">Recibí ayuda para preparar la defensa de mi memoria</td>
		<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;"><?=isset($table->p5)?$table->p5:"*";?></td>
	</tr>
	<tr>
		<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;">Recibí las notas y el feedback de mis entregas oportunamente</td>
		<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;"><?=isset($table->p6)?$table->p6:"*";?></td>
	</tr>
	<tr>
		<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;">El trato de mi profesor guía fue cordial</td>
		<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;"><?=isset($table->p7)?$table->p7:"*";?></td>
	</tr>
	<tr>
		<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;">En general mi profesor guía cumplió con los compromisos acordados</td>
		<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;"><?=isset($table->p8)?$table->p8:"*";?></td>
	</tr>
	<tr>
		<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;"><b>Promedio General</b></td>
		<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;"><?=isset($table->tot)?$table->tot:"*";?></td>
	</tr>
</table>
<br>
<br>Comentarios:
<br>
<table style="border-collapse: collapse;"><?php
	if(isset($comentarios)){
		foreach ($comentarios as $key => $value) {
			echo "<tr>";
				echo '<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;">';
					echo $key+1;
				echo "</td>";
				echo '<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;">';
					echo $value;
				echo "</td>";
			echo "</tr>";
		}
	}
?></table>
<br>
Atte,<br>
<br>
Equipo de titulación de la FIC<br>
<br>
Favor no reenviar este mail. 