<?php
class ViewsMemorias extends BaseController
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





}
?>