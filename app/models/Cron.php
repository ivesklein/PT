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

	public function scopeAdddilued($query, $function, $vars, $triggertime){

		$cronew = new Cron;
		$cronew->function = $function ;
		$cronew->vars = json_encode($vars);

		$rand = (rand(1,19)-10)*5;

		// 1  2  3  4  5  6  7  8  9 10 11 12 13 14 15 16 17 18 19
		//-9 -8 -7 -6 -5 -4 -3 -2 -1  0  1  2  3  4  5  6  7  8  9	
		//-45                         0                         45

		$cronew->triggertime = $triggertime->addMinutes($rand);
		
		$cronew->fired = false ;
		$cronew->attempts = "0";
		$cronew->save();
		return $cronew->id;
		
	}

	public function scopeAddafter($query, $function, $vars, $triggertime){

		$cronew = new Cron;
		$cronew->function = $function ;
		$cronew->vars = json_encode($vars);

		$rand = rand(1,12)*5;
	
		
		$cronew->triggertime = $triggertime->addMinutes($rand);
		
		$cronew->fired = false ;
		$cronew->attempts = "0";
		$cronew->save();
		return $cronew->id;
		
	}

}