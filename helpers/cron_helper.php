<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	Function: register_cronjob()
	
	Creates a new cronjob in the system. Intended for use
	during migrations.
	
	Parameters: 
		$title		- The title of the cronjob as appears in the admin.
		$desc		- The description on the task as appears in teh admin.
		$module		- The name of the module this belongs to. (should be lowercase)
		$method		- The method name that runs the job.
		$time		- An (optional) array with the suggested time settings, formatted: 
		
			$time = array(
				'time_minutes'		=> 0,
				'time_hours'		=> 0,
				'time_week_day'		=> 0,
				'time_month_day'	=> 0
			);
			
			All time values are optional within the array.
		
	Returns:
		- NULL if not enough information provided.
		- $id on success.
		- FALSE on failure.
*/
function register_cronjob($title=null, $desc=null, $module=null, $method=null, $time=array())
{
	if (empty($title) || empty($module) || empty($method))
	{
		return null;
	}
	
	$data = array(
		'title'			=> $title,
		'description'	=> $desc,
		'module'		=> $module,
		'enabled'		=> 1,
		'method_name'	=> $method,
	);
	
	$data = array_merge($data, $time);
	
	$ci =& get_instance();
	
	$ci->load->model('cronjobs/cronjob_model');
	
	return $ci->cronjob_model->insert($data);
}

//--------------------------------------------------------------------

/*
	Function: deregister_cronjob()
	
	Removes a cronjob from the database. Intended for use during
	the down() method of a migration.
	
	Parameters:
		$id		- An int with the ID of the task to deregister. If empty,
			 	  will attempt to find it by module and title (if provided.)
		$module	- REQUIRED. The name of the module it's associated with.
		$title	- 
		
		Either $id or $title must be provided. This keeps us safe from 
		removing all cronjobs for that module. If you need to remove all
		cronjobs for a single module, use the deregister_module_cronjobs()
		method, instead.
		
	Returns:
		- NULL if not enough information is provided.
		- TRUE/FALSE on success/failure
*/
function deregister_cronjob($id=null, $module=null, $title=null)
{
	if (empty($module) || ( empty($id) && !is_numeric($id) && empty($title) ))
	{
		return null;
	}

	$ci =& get_instance();
	
	$ci->load->model('cronjobs/cronjob_model');
	
	if (isset($id))
	{
		$ci->cronjob_model->where('id', $id);
	}
	
	if (isset($title))
	{
		$ci->cronjob_model->where('title', $title);
	}
	
	return $ci->cronjob_model->delete();
}

//--------------------------------------------------------------------

/*
	Function: deregister_module_cronjobs()
	
	Removes all cronjobs for a single module from the database.
	
	Parameters:
		$module	- The name of the module.
		
	Returns:
		true/false
*/
function deregister_module_cronjobs($module=null)
{
	if (empty($module))
	{
		return null;
	}

	$ci =& get_instance();
	
	$ci->load->model('cronjobs/cronjob_model');
	
	return $ci->cronjob_model->where('module', strtolower($module))
							 ->delete();
}

//--------------------------------------------------------------------
