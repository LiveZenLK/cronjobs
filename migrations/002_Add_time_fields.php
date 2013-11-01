<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Add_time_fields extends Migration {

	public function up()
	{
		$this->db->query("ALTER TABLE `cronjobs` ADD `last_start` INT(11) DEFAULT 0");
		$this->db->query("ALTER TABLE `cronjobs` ADD `last_stop` INT(11) DEFAULT 0");
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->db->query("ALTER TABLE `cronjobs` DROP COLUMN `last_start`");
		$this->db->query("ALTER TABLE `cronjobs` DROP COLUMN `last_stop`");
	}

	//--------------------------------------------------------------------

}