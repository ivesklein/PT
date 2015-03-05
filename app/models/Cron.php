<?php

Class Cron extends Eloquent{

	protected $table = 'crons';

	public function scopeTodo($query){

		return $query->whereFired(false)->where("triggertime","<", Carbon::now())->where("attempts","<", 3);
	}

	public function scopeAdd($query, $function, $vars, $triggertime){

		$cronew = new Cron;
		$cronew->function = $function ;
		$cronew->vars = json_encode($vars);
		$cronew->triggertime = $triggertime ;
		$cronew->fired = false ;
		$cronew->attempts = "0";
		$cronew->save();
		return $cronew->id;
		
	}

}