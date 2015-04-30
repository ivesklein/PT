<?php
class ViewsCron extends BaseController
{

	public function __construct()
	{
		$this->beforeFilter(function(){
			if(!Auth::check()){
				return View::make('views.expired');
			}
		});
	}

	public static function test()
    {
        return true;
    }

	public function getCronlist()
	{
		$ahead = array("id","Funcion","Cuando","Contenido");
		$head = "";
		foreach ($ahead as $value) {
			$head .= View::make('table.head',array('title'=>$value));
		}

		$body="";

		$crons = Cron::todo()->get();
		if(!$crons->isEmpty()){

			foreach ($crons as $cron) {

				$date = CarbonLocale::parse($cron->triggertime);

				$fecha = $date->format('m/d/Y')." ".$date->diffParaHumanos();

				$datos = str_replace("'","\'",$cron->vars);

				$tool = View::make("html.tooltip", array("title"=>$datos));

				$array["content"] = View::make("table.cell",array("content"=>$cron->id));
				$array["content"] .= View::make("table.cell",array("content"=>$cron->function));
				$array["content"] .= View::make("table.cell",array("content"=>$fecha));
				$array["content"] .= View::make("table.cell",array("content"=>$tool));
				$body .= View::make("table.row",$array);
			}

		}else{
			$message = "No hay Cronjobs activos";
			$content = View::make("table.cell",array("content"=>$message));
			$body .= View::make("table.row",array("content"=>$content));

		}
		//print_r($res);
		$table = View::make('table.table', array("head"=>$head,"body"=>$body));
				$script = '
		</script>
		<script src="js/tooltip.js"></script>
		<script src="js/popover.js"></script>
		<script>
		$(function () {
					  $(\'[data-toggle="popover"]\').popover()
					})';
		return View::make('table.tableview', array("title"=>"CronJobs","table"=>$table, "script"=>$script));
	}

	public function getCronfired()
	{
		$ahead = array("id","Funcion","Cuando","Contenido");
		$head = "";
		foreach ($ahead as $value) {
			$head .= View::make('table.head',array('title'=>$value));
		}

		$body="";

		$crons = Cron::whereFired(true)->get();
		if(!$crons->isEmpty()){

			foreach ($crons as $cron) {

				$date = CarbonLocale::parse($cron->triggertime);

				$fecha = $date->format('m/d/Y')." ".$date->diffParaHumanos();

				$datos = str_replace("'","\'",$cron->vars);

				$tool = View::make("html.tooltip", array("title"=>$datos));

				$array["content"] = View::make("table.cell",array("content"=>$cron->id));
				$array["content"] .= View::make("table.cell",array("content"=>$cron->function));
				$array["content"] .= View::make("table.cell",array("content"=>$fecha));
				$array["content"] .= View::make("table.cell",array("content"=>$tool));
				$body .= View::make("table.row",$array);
			}

		}else{
			$message = "No hay Cronjobs Ejecutados";
			$content = View::make("table.cell",array("content"=>$message));
			$body .= View::make("table.row",array("content"=>$content));

		}
		//print_r($res);
		$table = View::make('table.table', array("head"=>$head,"body"=>$body));
				$script = '
		</script>
		<script src="js/tooltip.js"></script>
		<script src="js/popover.js"></script>
		<script>
		$(function () {
					  $(\'[data-toggle="popover"]\').popover()
					})';
		return View::make('table.tableview', array("title"=>"CronJobs","table"=>$table, "script"=>$script));
	}

	public function getCronerror()
	{
		$ahead = array("id","Funcion","Cuando");
		$head = "";
		foreach ($ahead as $value) {
			$head .= View::make('table.head',array('title'=>$value));
		}

		$body="";

		$crons = Cron::whereFired(false)->whereAttempts("3")->get();

		if(!$crons->isEmpty()){

			foreach ($crons as $cron) {

				$date = CarbonLocale::parse($cron->triggertime);

				$fecha = $date->format('m/d/Y')." ".$date->diffParaHumanos();

				$array["content"] = View::make("table.cell",array("content"=>$cron->id));
				$array["content"] .= View::make("table.cell",array("content"=>$cron->function));
				$array["content"] .= View::make("table.cell",array("content"=>$fecha));
				$body .= View::make("table.row",$array);
			}

		}else{
			$message = "No hay Cronjobs activos";
			$content = View::make("table.cell",array("content"=>$message));
			$body .= View::make("table.row",array("content"=>$content));

		}
		//print_r($res);
		$table = View::make('table.table', array("head"=>$head,"body"=>$body));
		return View::make('table.tableview', array("title"=>"CronJobs","table"=>$table));
	}


}
?>