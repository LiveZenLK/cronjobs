<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cronjob_model extends BF_Model {

	protected $table		= 'cronjobs';
	protected $key			= 'id';
	protected $set_created	= false;
	protected $set_modified	= false;
	protected $soft_deletes	= false;

	//---------------------------------------------------------------

	public function find_all() 
	{
		$results = parent::find_all();
		
		if ($results)
		{
			foreach ($results as $key => &$result)
			{
				$result->next_run = $this->calc_next_run($result);
			}
		}
		
		return $results;
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !Private Methods
	//--------------------------------------------------------------------
	
	private function calc_next_run($task) 
	{
		$run_time = time();
		$adjust_mins = true;
		$minutes = 0;
	
		$hour		= $task->time_hours == -1	? date('G')		: $task->time_hours;
		$month_day	= $task->time_month_day == 0 ? date('j')	: $task->time_month_day;
		$week_day	= $task->time_week_day;
		
		// Determine the correct day of the month to use.
		if ($task->time_week_day != 0)
		{	
			$days = array('Undefined', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
			$tweek_day = strtotime('next '. $days[$task->time_week_day]);
			$tweek_day = date('j', $task->time_week_day);
		
			// Cannot use both day of the month and day of the week
			// so error on the most often.
			$month_day = $week_day;
			
			$adjust_mins = false;
		}
		
		// Determine correct minutes to use
		if (strpos($task->time_minutes, '*/') !== FALSE)
		{
			$mins = explode('/', $task->time_minutes);
			$div = ceil(date('i', $run_time) / $mins[1]);
			
			if ($adjust_mins === true)
			{
				$minutes = (date('i', $run_time) % $mins[1]) == 0 ? date('i', $run_time) : $mins[1] * $div;
			}
			else
			{
				$minutes = $mins[1];
				$hour = 0;
			}
		}
		else if ($task->time_minutes == 0)
		{
			$minutes = date('i', $run_time);
		}

		return mktime($hour, $minutes, 0, date('n'), $month_day);
	}
	
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
		
	}
	
	//--------------------------------------------------------------------
}