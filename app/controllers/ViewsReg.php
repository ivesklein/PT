<?php
class ViewsReg extends BaseController
{

	public function __construct()
	{
		$this->beforeFilter(function(){
			if(!Auth::check()){
				return View::make('views.expired');
			}
		});
	}

	public function getActions()
	{
		$ahead = array("Quien","Que","Donde","Relacionado con", "Extra", "IP","UA", "Cuando");
		$head = "";
		foreach ($ahead as $value) {
			$head .= View::make('table.head',array('title'=>$value));
		}

		$body="";

		$actions = Action::all();
		if(!$actions->isEmpty()){

			foreach ($actions as $action) {

				$date = CarbonLocale::parse($action->created_at);

				$fecha = $date->format('m/d/Y');

				$array["content"] = View::make("table.cell",array("content"=>$action->who));
				
				if(strlen($action->what)>30){
					$what = View::make("html.tooltip",array("title"=>$action->what));
				}else{
					$what = $action->what;
				}

				$array["content"] .= View::make("table.cell",array("content"=>$what));

				if(strlen($action->where)>30){
					$where = View::make("html.tooltip",array("title"=>$action->where));
				}else{
					$where = $action->where;
				}

				$array["content"] .= View::make("table.cell",array("content"=>$where));
				$array["content"] .= View::make("table.cell",array("content"=>$action->related_to));
				if(strlen($action->data)>30){
					$data = View::make("html.tooltip",array("title"=>$action->data));
				}else{
					$data = $action->data;
				}
				$array["content"] .= View::make("table.cell",array("content"=>$data));
				$array["content"] .= View::make("table.cell",array("content"=>$action->ip));

				$tool = View::make("html.tooltip",array("title"=>$action->user_agent));
				$array["content"] .= View::make("table.cell",array("content"=>$tool));
				
				$array["content"] .= View::make("table.cell",array("content"=>$date));
				
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