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
	public function fire()
	{
		//get the list from db
		//$res = Cron::whereFired()
			//filter by date

			//execute the list items

		$this->info("all ok");
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */

}
