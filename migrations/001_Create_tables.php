<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_tables extends Migration {

	public function up() 
	{
		$this->load->dbforge();
		
		if (!$this->db->table_exists('cronjobs'))
		{
		
			// Cronjobs table
			$this->dbforge->add_field('`id` int(11) unsigned NOT NULL AUTO_INCREMENT');
			$this->dbforge->add_field('`title` varchar(255) NOT NULL DEFAULT ""');
			$this->dbforge->add_field('`description` varchar(255) DEFAULT NULL');
			$this->dbforge->add_field('`module` varchar(255) DEFAULT "core" ');
			$this->dbforge->add_field('`time_minutes` varchar(5) NOT NULL DEFAULT "0"');
			$this->dbforge->add_field('`time_hours` tinyint(2) NOT NULL DEFAULT "0"');
			$this->dbforge->add_field("`time_week_day` tinyint(1) NOT NULL DEFAULT '0'");
			$this->dbforge->add_field("`time_month_day` tinyint(2) NOT NULL DEFAULT '0'");
			$this->dbforge->add_field("`enabled` tinyint(1) NOT NULL DEFAULT '0'");
			$this->dbforge->add_field("`method_name` varchar(255) NOT NULL DEFAULT ''");
			$this->dbforge->add_field("`running` tinyint(1) NOT NULL DEFAULT '0'");
			$this->dbforge->add_key('id', true);
			$this->dbforge->create_table('cronjobs');
			
			// Permissions
			$this->db->insert('permissions', array(
					'name'			=> 'Cron.Settings.Manage',
					'description'	=> 'Allow user to view and manage cronjobs.',
					'status'		=> 'active'
				)
			);
			$this->db->insert('role_permissions', array(
					'role_id'		=> 1,
					'permission_id'	=> $this->db->insert_id()
				)
			);
	
			// Default cronjobs
			$this->load->model('cronjobs/cronjob_model');
			$this->cronjob_model->insert(array(
				'title'				=> 'Optimize Database',
				'description'		=> 'Run once per day to optimize your database tables.',
				'module'			=> 'core',
				'time_minutes'		=> 0,
				'time_hours'		=> 4,
				'time_week_day'		=> 0,
				'time_month_day'	=> 0, 
				'enabled'			=> 1,
				'method_name'		=> 'task_db_optimize'
			));
		}
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$this->dbforge->drop_table('cronjobs');
	}
	
	//--------------------------------------------------------------------
	
}