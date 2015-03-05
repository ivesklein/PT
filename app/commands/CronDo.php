<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CronDo extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'cron_do';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Execute a cron task';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$cron = Cron::find($this->argument('id'));

		$vars = json_decode($cron->vars);
		$function = $cron->function;

		if(method_exists("CronRoute", $function)){

			$cron->fired = true;
			$cron->attempts = $cron->attempts + 1;
			$cron->save();
			try {
				CronRoute::$function($vars);
			} catch (Exception $e) {
				Log::info("error ".$e->getMessage());
				$cron->fired = false;
				$cron->save();
			}
			
		}else{
			Log::info("metodo ".$function." no existe");
		}

		//Log::info("fired ".$this->argument('id').":".time());
		//$this->info($this->argument('id'));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('id', InputArgument::REQUIRED, 'cron queue id'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */

}
