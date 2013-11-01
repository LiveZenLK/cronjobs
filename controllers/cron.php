<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	Class: Cron

	Runs the various cron tasks enabled in the system.

	Cronjobs are called 'Tasks' and are available both to the Core, the
	application itself, and to each module. Tasks are basically libraries
	that contain methods, one per task. Each method name must be prefixed
	with 'task_'.

	Non-module tasks are stored	in application/libraries. Each module may have
	a tasks.php file within the libraries folder that stores the Tasks class.

	The file/class should be named by the module name, an underscore, then tasks.
	Both the filename and the classname should be lowercase.

	For a blog module, it would be:

		File	- libraries/blog_tasks.php
		Class	- blog_tasks
*/
class Cron extends Base_Controller {

	public function __construct()
	{
		parent::__construct();

		// Make sure we have plenty of time to run large tasks.
		set_time_limit(0);
	}

	//--------------------------------------------------------------------


	/*
		Method: _remap()

		Redirects all traffic to the index method.
	*/
	public function _remap($method=null)
	{
		$this->index();
	}

	//--------------------------------------------------------------------

	/*
		Method: index()

		Handles running all of the tasks in the system.
	*/
	public function index()
	{
		// Make sure we can see the output as it happens for the cronjobs
		while (@ob_end_flush());
		ob_implicit_flush(true);
		// header("Content-Type: text/plain");

		// Turn on error displays always fron CRONJOBS, no matter
		// the environment.
		error_reporting(E_ALL & ~E_DEPRECATED);
		if (!ini_get('display_errors'))
		{
			ini_set('display_errors', 1);
		}

		// Make sure debugging is on for the database for these types of calls
		// so we actually what know what on behind the scenes even when we're
		// on a live server with debugging off.
		$this->db->db_debug = true;

		$run_time = date('Y-M-d H:i');

		$start_mem = memory_get_peak_usage();
		echo "Starting Tasks Current Peak Memory Usage: ". number_format($start_mem /1024 /1024, 2) ." MB<br/><br/>\n\n";

		// If the $_GET var 'run' exists, it
		// means we should force that task id to run
		// right now.
		$forced_id = $this->input->get('run');

		/*
			1. Get a list of all tasks in the system.
		*/
		if (!isset($this->db))
		{
			$this->load->database();
		}

		$this->load->model('cronjobs/cronjob_model');

		$tasks = $this->cronjob_model->select('id, module, time_minutes, time_hours, time_week_day, time_month_day, enabled, method_name, running')
						    		 ->find_all();

		if (!$tasks)
		{
			die('No tasks to perform');
		}

		/*
			2. Perform Enabled tasks
		*/
		foreach ($tasks as $task)
		{
			// Is it already running?
			if (!$forced_id && $task->running == 1)
			{
				echo "{$task->module}:{$task->method_name} already running. <br/>\n";
				continue;
			}

			// If we're not forcing it to run, check if it's time...
			if ($task->id !== $forced_id)
			{
				if (!$task->enabled)
				{
					echo "{$task->module}:{$task->method_name} not enabled. <br/>\n";
					continue;
				}

				// Update our times to remove any cron syntax goodies (like */5)
				$this->check_times($task, strtotime($run_time));

				// Is it the right day of the month?
				if ($task->time_month_day != 0 && $task->time_month_day != date('j'))
				{
					continue;
				}

				// Is it the right day of the week?
				if ($task->time_week_day != 0 && $task->time_week_day != date('N'))
				{
					continue;
				}
			}

			// Calculate the next time this is scheduled to run
			$scheduled_time = date('Y-M-d H:i', mktime($task->time_hours, $task->time_minutes) );
//echo "Task = {$task->method_name} Current Time = {$run_time} Scheduled Time = {$scheduled_time}<br/>";

			/*
				Time to actually run this job!
			 */
			if ($run_time == $scheduled_time || $forced_id === $task->id)
			{
				echo "{$task->module}:{$task->method_name} Starting at ". date('H:i:s') ."<br/>\n";

				// Record we're running.
				$this->cronjob_model->update($task->id, array('running' => 1, 'last_start' => time()));

				$task->module = strtolower($task->module);

				// Load the task library!
				if ($task->module == 'core')
				{
					$this->load->library('core_tasks');
					$task_lib = $this->core_tasks;
				}
				else
				{
					$this->load->library($task->module .'/'. $task->module .'_tasks');
					$name = $task->module .'_tasks';
					$task_lib = $this->$name;

					unset($name);
				}

				// Run the task
				if (method_exists($task_lib, $task->method_name))
				{
					$start_time = Monitor::start_time();

					$task_lib->{$task->method_name}();

					$this->monitor->time($start_time, $task->method_name);
				}
				else
				{
					// Log the error....
					echo 'Error';
				}

				// Record we're not running.
				$this->cronjob_model->update($task->id, array('running' => 0, 'last_stop' => time() ));

				$cur_mem = memory_get_peak_usage();
				$mem = number_format( ($cur_mem - $start_mem) /1024 /1024, 2);
				$start_mem = $cur_mem;

				echo "Tasks Memory Usage: <b>$mem MB</b><br/>";

				echo "{$task->module}:{$task->method_name} Done at ". date('H:i:s') .". <br/>--------------------------------------<br/><br/>\n\n";
			}
			else
			{
				echo "{$task->module}:{$task->method_name} Not Running. Next Scheduled run: $scheduled_time<br/>\n";
			}
		}

		// Record our last run time
		$this->settings_lib->set('cron_last_run', date('Y-m-d H:i:s'), 'cronjobs');

		die();
	}

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !Private Methods
	//--------------------------------------------------------------------

	/*
		Method: check_times()

		Converts cron-based times to real world times.

		Parameters:
			$task		- A pointer to the task object that contains the time values.
			$run_time	- A UNIX timestamp.
	*/
	private function check_times(&$task, $run_time)
	{
		// Every Hour?
		$task->time_hours		= $task->time_hours == '-1'	? date('G')		: $task->time_hours;

		// Running today?
		$task->time_week_day	= $task->time_week_day == 0 ? date('N')		: $task->time_week_day;

		// Running this day this month?
		$task->time_month_day	= $task->time_month_day == 0 ? date('j')	: $task->time_month_day;

		if (strpos($task->time_minutes, '*/') !== FALSE)
		{
			$minutes = explode('/', $task->time_minutes);
			$div = ceil(date('i', $run_time) / $minutes[1]);
			$task->time_minutes = (date('i', $run_time) % $minutes[1]) == 0 ? date('i', $run_time) : $minutes[1] * $div;
		}
		else if ($task->time_minutes == 0)
		{
			$task->time_minutes = 0; date('i', $run_time);
		}
	}

	//--------------------------------------------------------------------

}

// End Cron controller

//--------------------------------------------------------------------

/*
	Class: Base_Task

	Provides simple reporting methods that all task classes should
	extend from.
*/
class Base_Task {

	/*
		Var: $ci
		Stores a pointer to the CI superobject.
	*/
	protected $ci;

	/*
		Var: $format
		Either html or cli
	*/
	protected $format;

	//--------------------------------------------------------------------

	public function __construct()
	{
		$this->ci =& get_instance();

		$this->format = $this->ci->input->is_cli_request() ? 'cli' : 'html';
	}

	//--------------------------------------------------------------------

	/*
		Method: output_header()

		Outputs a header to the screen.

		Parameters:
			$task_name	- A string to be displayed in the header that
						  is the name of the task being ran.
	*/
	protected function output_header($task_name='Unnamed Task')
	{
		if ($this->format == 'cli')
		{
			$tpl =<<<EOL
//--------------------------------------------------------------------
// Starting {task_name} - {time}
//--------------------------------------------------------------------

EOL;
		}
		else
		{
			$tpl =<<<EOL
//--------------------------------------------------------------------<br/>
// Starting {task_name} - {time}<br/>
//--------------------------------------------------------------------<br/>
<br/>
EOL;
		}

		$output = str_replace('{task_name}', $task_name, $tpl);
		$output = str_replace('{time}', date($this->ci->config->item('log_date_format')), $output);

		echo $output;
	}

	//--------------------------------------------------------------------

	/*
		Method: output_footer()

		Outputs a footer to the screen.

		Parameters:
			$task_name	- A string to be displayed in the footer that
						  is the name of the task being ran.
	*/
	protected function output_footer($task_name='Unnamed Task')
	{
		if ($this->format == 'cli')
		{
			$tpl =<<<EOL

// {task_name} Done - {time}
//--------------------------------------------------------------------
EOL;
		}
		else
		{
			$tpl =<<<EOL
<br/>
// {task_name} Done - {time}<br/>
//--------------------------------------------------------------------<br/><br/>
EOL;
		}

		$output = str_replace('{task_name}', $task_name, $tpl);
		$output = str_replace('{time}', date($this->ci->config->item('log_date_format')), $output);

		echo $output;

		// Trigger a save to the database to show that we're complete
	}

	//--------------------------------------------------------------------

}

// End Base_Task class