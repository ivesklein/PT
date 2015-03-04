<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CronTodo extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'cron_todo';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Execute the todo list';

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




	/**
	 * Asynchronously execute/include a PHP file. Does not record the output of the file anywhere. 
	 *
	 * @param string $filename              file to execute, relative to calling script
	 * @param string $options               (optional) arguments to pass to file via the command line
	*
	*  
	*public function asyncInclude($filename, $options = '') {
	*    exec("/home/pt/GIT/ -f {$filename} {$options} >> /dev/null &");
	*}
	*/

	public function fire()
	{
		//get the list from db
		$res = Cron::todo()->get();
			//filter by date

		$i=0;

		if(!$res->isEmpty()){
			foreach ($res as $cron) {
				exec("php /home/pm/GIT/artisan cron_do ".$cron->id." >> /dev/null &");
				$i++;
			}
		}
		Log::info($i." task executed.");
		$this->info($i." task executed.");
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */

}
