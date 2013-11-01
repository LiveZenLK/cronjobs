<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	Reference:
		Hours	-2 = Not Hourly
				-1 = Every Hour
		Days:	 0 = Every Day of the Week
		Months:  0 = Every Day of the Month
*/

class Settings extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('Cronjobs.Settings.View');

		Template::set('toolbar_title', 'Manage Cronjobs');
		Template::set_block('sub_nav', 'settings/sub_nav');

		$this->load->model('cronjob_model');
	}

	//--------------------------------------------------------------------

	public function index()
	{
		$this->load->helper('date');

		Template::set('tasks', $this->cronjob_model->order_by('module', 'asc')->find_all());
		Template::render();
	}

	//--------------------------------------------------------------------

	public function create()
	{
		if ($this->input->post('submit'))
		{
			if ($this->save_task() !== false)
			{
				Template::set_message('Task created successfully.', 'success');
				redirect(SITE_AREA .'/settings/cronjobs');
			}
		}

		Template::set('modules', module_list());

		Template::set('toolbar_title', 'Add New Task');
		Template::set_view('settings/task_form');
		Template::render();
	}

	//--------------------------------------------------------------------

	public function edit($id=null)
	{
		if ($this->input->post('submit'))
		{
			if ($this->save_task('update', $id) !== false)
			{
				Template::set_message('Task saved successfully.', 'success');
				redirect(SITE_AREA .'/settings/cronjobs');
			}
		}

		Template::set('task', $this->cronjob_model->find($id));

		Template::set('modules', module_list());

		Template::set_view('settings/task_form');
		Template::render();
	}

	//--------------------------------------------------------------------

	public function clear_running_flag($task_id=0)
	{
		if (empty($task_id))
		{
			Template::set_message('No Task ID was provided.', 'error');
			Template::redirect($this->previous_page);
		}

		// Grab it so we can give a better message
		$task = $this->cronjob_model->find($task_id);

		if (!$task)
		{
			Template::set_message('Unable to find a task with ID '. $task_id, 'warning');
			Template::redirect($this->previous_page);
		}

		if (!$task->running)
		{
			Template::set_message('Task was not running. Nothing to do.', 'warning');
			Template::redirect($this->previous_page);
		}

		if ($this->cronjob_model->update($task_id, array('running' => 0)))
		{
			Template::set_message('Task '. $task->id .' had its running flag cleared.', 'success');
		}
		else
		{
			Template::set_message('Database Error trying to clear running flag. '. $this->cronjob_model->error, 'error');
		}

		Template::redirect($this->previous_page);
	}

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------
	// !Private Methods
	//--------------------------------------------------------------------

	private function save_task($type='insert', $id=null)
	{
		$this->form_validation->set_rules('title', 'Title', 'required|trim|strip_tags|max_length[255]|xss_clean');
		$this->form_validation->set_rules('description', 'Short Description', 'trim|strip_tags|max_length[255]|xss_clean');
		$this->form_validation->set_rules('method_name', 'Method To Run', 'required|trim|strip_tags|max_length[255]|alpha_dash|xss_clean');

		if ($this->form_validation->run() === false)
		{
			return false;
		}

		$data = $this->input->post();

		$data['enabled'] = isset($data['enabled']) ? 1 : 0;

		if ($type == 'insert')
		{
			$id = $this->cronjob_model->insert($data);
			return $id;
		}
		else if ($type == 'update')
		{
			return $this->cronjob_model->update($id, $data);
		}

		return false;
	}

	//--------------------------------------------------------------------

}