<?php

Class Nonce extends Eloquent{

	protected $table = 'nonce';

	public function scopePass($query, $noncei){

		$vnonce = true;
		if($noncei!=""){
			//$exist = Nonce::where('created_at', '>=', Carbon::now()->subMinutes(90))->where('nonce',"=",$nonce)->count();
			
			//$exist = Nonce::where('nonce',"=",$noncei);//->count();

			$nonce = new Nonce;
			$nonce->nonce = $noncei;
			$nonce->save();

			$exist = $query->where('created_at', '>=', Carbon::now()->subMinutes(90))->whereNonce($noncei)->count();
			
			if($exist>1){$vnonce=false;}

		}else{$vnonce=false;}
		//
		return $vnonce?"true":"false";
	}

}