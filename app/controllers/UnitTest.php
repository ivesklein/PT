<?php
class UnitTest {

	//no funciona
	
	public static function run()
	{

		function ok($text)
		{
			return "<font class='ok'>".$text."</font>";
		}

		function er($text)
		{
			return "<font class='er'>".$text."</font>";
		}


		$res = "Unit Test<br><br><style>.ok{color:green;}.er{color:red;}</style>";

		$res.="PostComision:	";
		try {
			$r = PostComision::data();
			$res.= ok("ok");
		} catch (Exception $e) {
			$res.= er($e->getMessage());
		}



		return $res;

	}


}
?>