<?php

Class Cron extends Eloquent{

	protected $table = 'crons';

	public function scopeTodo($query){

		return $query->whereFired(false)->where("triggertime","<", Carbon::now());
	}

}