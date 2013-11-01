<div class="admin-box">
	<h3><?php echo $toolbar_title ?></h3>

	<?php echo form_open(current_url(), 'class="form-horizontal"') ?>

		<div class="control-group <?php if (form_error('title')) echo 'error'; ?>">
			<label for="title" class="control-label">Title</label>
			<div class="controls">
				<input type="text" name="title" class="input-xlarge" value="<?php echo isset($task) ? $task->title : set_value('title') ?>" />
				<?php if (form_error('title')) echo '<span class="help-inline">'. form_error('title') .'</span>'; ?>
			</div>
		</div>

		<div class="control-group <?php if (form_error('description')) echo 'error'; ?>">
			<label for="description" class="control-label">Short Description</label>
			<div class="controls">
				<input type="text" name="description" class="input-xlarge" value="<?php echo isset($task) ? $task->description : set_value('description') ?>" />
				<?php if (form_error('title')) echo '<span class="help-inline">'. form_error('description') .'</span>'; ?>
			</div>
		</div>

		<div class="control-group">
			<label for="module" class="control-label">Module</label>
			<div class="controls">
				<select name="module">
					<option value="core" <?php echo isset($task) && $task->module == 'core' ? 'selected="selected"' : ''; ?>>Core</option>
					<option value="app" <?php echo isset($task) && $task->module == 'app' ? 'selected="selected"' : ''; ?>>Application</option>
					<?php foreach ($modules as $module) :?>
						<option value="<?php echo $module ?>" <?php echo isset($task) && $task->module == $module ? 'selected="selected"' : ''; ?>><?php echo ucfirst($module) ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>

		<div class="control-group">
			<label for="method_name" class="control-label">Method To Run</label>
			<div class="controls">
				<input type="text" name="method_name" class="input-xlarge" value="<?php echo isset($task) ? $task->method_name : set_value('method_name'); ?>" />
				<?php if (form_error('method_name')) : ?>
					<span class="help-inline"><?php echo form_error('method_name'); ?></span>
				<?php else: ?>
					<span class="help-inline">This is the name of the method inside of the task class that will be run.</span>
				<?php endif; ?>
			</div>
		</div>

	<fieldset>
		<legend>Time Options</legend>

		<div class="control-group">
			<label for="time_minutes" class="control-label">Time: Minutes</label>
			<div class="controls">
				<select name="time_minutes">
					<option value="0">0</option>
					<option <?php echo isset($task) && $task->time_minutes == '*/1' ? 'selected="selected"' : set_select('time_minutes', '*/1') ?>  value="*/1">Every Minute</option>
					<option <?php echo isset($task) && $task->time_minutes == '*/5' ? 'selected="selected"' : set_select('time_minutes', '*/5') ?> value="*/5">Every 5 Minutes</option>
					<option <?php echo isset($task) && $task->time_minutes == '*/10' ? 'selected="selected"' : set_select('time_minutes', '*/10') ?> value="*/10">Every 10 Minutes</option>
					<option <?php echo isset($task) && $task->time_minutes == '*/15' ? 'selected="selected"' : set_select('time_minutes', '*/15') ?> value="*/15">Every 15 Minutes</option>
					<option <?php echo isset($task) && $task->time_minutes == '*/20' ? 'selected="selected"' : set_select('time_minutes', '*/20') ?> value="*/20">Every 20 Minutes</option>
					<option <?php echo isset($task) && $task->time_minutes == '*/30' ? 'selected="selected"' : set_select('time_minutes', '*/30') ?> value="*/30">Every 30 Minutes</option>
					<?php for ($i=1; $i < 60; $i ++) :?>
						<option value="<?php echo $i; ?>" <?php echo isset($task) && $task->time_minutes == $i ? 'selected="selected"' :set_select('time_minutes', $i) ?>>00:<?php echo $i; ?></option>
					<?php endfor; ?>
				</select>
			</div>
		</div>

		<div class="control-group">
			<label for="time_hours" class="control-label">Time: Hours</label>
			<div class="controls">
				<select name="time_hours">
					<option <?php echo isset($task) && $task->time_hours == '-1' ? 'selected="selected"' : set_select('time_hours', -1) ?> value="-1">Every Hour</option>
					<option <?php echo isset($task) && $task->time_hours == '0' ? 'selected="selected"' : set_select('time_hours', 0) ?> value="0">0 - Midnight</option>
					<option <?php echo isset($task) && $task->time_hours == '1' ? 'selected="selected"' : set_select('time_hours', 1) ?> value="1">1 AM</option>
					<option <?php echo isset($task) && $task->time_hours == '2' ? 'selected="selected"' : set_select('time_hours', 2) ?> value="2">2 AM</option>
					<option <?php echo isset($task) && $task->time_hours == '3' ? 'selected="selected"' : set_select('time_hours', 3) ?> value="3">3 AM</option>
					<option <?php echo isset($task) && $task->time_hours == '4' ? 'selected="selected"' : set_select('time_hours', 4) ?> value="4">4 AM</option>
					<option <?php echo isset($task) && $task->time_hours == '5' ? 'selected="selected"' : set_select('time_hours', 5) ?> value="5">5 AM</option>
					<option <?php echo isset($task) && $task->time_hours == '6' ? 'selected="selected"' : set_select('time_hours', 6) ?> value="6">6 AM</option>
					<option <?php echo isset($task) && $task->time_hours == '7' ? 'selected="selected"' : set_select('time_hours', 7) ?> value="7">7 AM</option>
					<option <?php echo isset($task) && $task->time_hours == '8' ? 'selected="selected"' : set_select('time_hours', 8) ?> value="8">8 AM</option>
					<option <?php echo isset($task) && $task->time_hours == '9' ? 'selected="selected"' : set_select('time_hours', 9) ?> value="9">9 AM</option>
					<option <?php echo isset($task) && $task->time_hours == '10' ? 'selected="selected"' : set_select('time_hours', 10) ?> value="10">10 AM</option>
					<option <?php echo isset($task) && $task->time_hours == '11' ? 'selected="selected"' : set_select('time_hours', 11) ?> value="11">11 AM</option>
					<option <?php echo isset($task) && $task->time_hours == '12' ? 'selected="selected"' : set_select('time_hours', 12) ?> value="12">12 PM</option>
					<option <?php echo isset($task) && $task->time_hours == '13' ? 'selected="selected"' : set_select('time_hours', 13) ?> value="13">1 PM</option>
					<option <?php echo isset($task) && $task->time_hours == '14' ? 'selected="selected"' : set_select('time_hours', 14) ?> value="14">2 PM</option>
					<option <?php echo isset($task) && $task->time_hours == '15' ? 'selected="selected"' : set_select('time_hours', 15) ?> value="15">3 PM</option>
					<option <?php echo isset($task) && $task->time_hours == '16' ? 'selected="selected"' : set_select('time_hours', 16) ?> value="16">4 PM</option>
					<option <?php echo isset($task) && $task->time_hours == '17' ? 'selected="selected"' : set_select('time_hours', 17) ?> value="17">5 PM</option>
					<option <?php echo isset($task) && $task->time_hours == '18' ? 'selected="selected"' : set_select('time_hours', 18) ?> value="18">6 PM</option>
					<option <?php echo isset($task) && $task->time_hours == '19' ? 'selected="selected"' : set_select('time_hours', 19) ?> value="19">7 PM</option>
					<option <?php echo isset($task) && $task->time_hours == '20' ? 'selected="selected"' : set_select('time_hours', 20) ?> value="20">8 PM</option>
					<option <?php echo isset($task) && $task->time_hours == '21' ? 'selected="selected"' : set_select('time_hours', 21) ?> value="21">9 PM</option>
					<option <?php echo isset($task) && $task->time_hours == '22' ? 'selected="selected"' : set_select('time_hours', 22) ?> value="22">10 PM</option>
					<option <?php echo isset($task) && $task->time_hours == '23' ? 'selected="selected"' : set_select('time_hours', 23) ?> value="23">11 PM</option>
				</select>
			</div>
		</div>

		<div class="control-group">
			<label for="time_week_day" class="control-label">Time: Week Day</label>
			<div class="controls">
				<select name="time_week_day">
					<option <?php echo isset($task) && $task->time_week_day == '0' ? 'selected="selected"' : set_select('time_week_day', 0) ?> value="0">Every Day</option>
					<option <?php echo isset($task) && $task->time_week_day == '1' ? 'selected="selected"' : set_select('time_week_day', 1) ?> value="1">Monday</option>
					<option <?php echo isset($task) && $task->time_week_day == '2' ? 'selected="selected"' : set_select('time_week_day', 2) ?> value="2">Tuesday</option>
					<option <?php echo isset($task) && $task->time_week_day == '3' ? 'selected="selected"' : set_select('time_week_day', 3) ?> value="3">Wednesday</option>
					<option <?php echo isset($task) && $task->time_week_day == '4' ? 'selected="selected"' : set_select('time_week_day', 4) ?> value="4">Thursday</option>
					<option <?php echo isset($task) && $task->time_week_day == '5' ? 'selected="selected"' : set_select('time_week_day', 5) ?> value="5">Friday</option>
					<option <?php echo isset($task) && $task->time_week_day == '6' ? 'selected="selected"' : set_select('time_week_day', 6) ?> value="6">Saturday</option>
					<option <?php echo isset($task) && $task->time_week_day == '7' ? 'selected="selected"' : set_select('time_week_day', 7) ?> value="7">Sunday</option>
				</select>
			</div>
		</div>

		<div class="control-group">
			<label for="time_month_day" class="control-label">Time: Month</label>
			<div class="controls">
				<select name="time_month_day">
					<option <?php echo isset($task) && $task->time_month_day == '0' ? 'selected="selected"' : set_select('time_month_day', 0); ?> value="0">Every Day of the Month</option>
					<option <?php echo isset($task) && $task->time_month_day == '1' ? 'selected="selected"' : set_select('time_month_day', 1); ?> value="1">1st</option>
					<option <?php echo isset($task) && $task->time_month_day == '2' ? 'selected="selected"' : set_select('time_month_day', 2); ?> value="2">2nd</option>
					<option <?php echo isset($task) && $task->time_month_day == '3' ? 'selected="selected"' : set_select('time_month_day', 3); ?> value="3">3rd</option>
					<option <?php echo isset($task) && $task->time_month_day == '4' ? 'selected="selected"' : set_select('time_month_day', 4); ?> value="4">4th</option>
					<option <?php echo isset($task) && $task->time_month_day == '5' ? 'selected="selected"' : set_select('time_month_day', 5); ?> value="5">5th</option>
					<option <?php echo isset($task) && $task->time_month_day == '6' ? 'selected="selected"' : set_select('time_month_day', 6); ?> value="6">6th</option>
					<option <?php echo isset($task) && $task->time_month_day == '7' ? 'selected="selected"' : set_select('time_month_day', 7); ?> value="7">7th</option>
					<option <?php echo isset($task) && $task->time_month_day == '8' ? 'selected="selected"' : set_select('time_month_day', 8); ?> value="8">8th</option>
					<option <?php echo isset($task) && $task->time_month_day == '9' ? 'selected="selected"' : set_select('time_month_day', 9); ?> value="9">9th</option>
					<option <?php echo isset($task) && $task->time_month_day == '10' ? 'selected="selected"' : set_select('time_month_day', 10); ?> value="10">10th</option>
					<option <?php echo isset($task) && $task->time_month_day == '11' ? 'selected="selected"' : set_select('time_month_day', 11); ?> value="12">11th</option>
					<option <?php echo isset($task) && $task->time_month_day == '12' ? 'selected="selected"' : set_select('time_month_day', 12); ?> value="12">12th</option>
					<option <?php echo isset($task) && $task->time_month_day == '13' ? 'selected="selected"' : set_select('time_month_day', 13); ?> value="13">13th</option>
					<option <?php echo isset($task) && $task->time_month_day == '14' ? 'selected="selected"' : set_select('time_month_day', 14); ?> value="14">14th</option>
					<option <?php echo isset($task) && $task->time_month_day == '15' ? 'selected="selected"' : set_select('time_month_day', 15); ?> value="15">15th</option>
					<option <?php echo isset($task) && $task->time_month_day == '16' ? 'selected="selected"' : set_select('time_month_day', 16); ?> value="16">16th</option>
					<option <?php echo isset($task) && $task->time_month_day == '17' ? 'selected="selected"' : set_select('time_month_day', 17); ?> value="17">17th</option>
					<option <?php echo isset($task) && $task->time_month_day == '18' ? 'selected="selected"' : set_select('time_month_day', 18); ?> value="18">18th</option>
					<option <?php echo isset($task) && $task->time_month_day == '19' ? 'selected="selected"' : set_select('time_month_day', 19); ?> value="19">19th</option>
					<option <?php echo isset($task) && $task->time_month_day == '20' ? 'selected="selected"' : set_select('time_month_day', 20); ?> value="20">20th</option>
					<option <?php echo isset($task) && $task->time_month_day == '21' ? 'selected="selected"' : set_select('time_month_day', 21); ?> value="21">21st</option>
					<option <?php echo isset($task) && $task->time_month_day == '22' ? 'selected="selected"' : set_select('time_month_day', 22); ?> value="22">22nd</option>
					<option <?php echo isset($task) && $task->time_month_day == '23' ? 'selected="selected"' : set_select('time_month_day', 23); ?> value="23">23rd</option>
					<option <?php echo isset($task) && $task->time_month_day == '24' ? 'selected="selected"' : set_select('time_month_day', 24); ?> value="24">24th</option>
					<option <?php echo isset($task) && $task->time_month_day == '25' ? 'selected="selected"' : set_select('time_month_day', 25); ?> value="25">25th</option>
					<option <?php echo isset($task) && $task->time_month_day == '26' ? 'selected="selected"' : set_select('time_month_day', 26); ?> value="26">26th</option>
					<option <?php echo isset($task) && $task->time_month_day == '27' ? 'selected="selected"' : set_select('time_month_day', 27); ?> value="27">27th</option>
					<option <?php echo isset($task) && $task->time_month_day == '28' ? 'selected="selected"' : set_select('time_month_day', 28); ?> value="28">28th</option>
					<option <?php echo isset($task) && $task->time_month_day == '29' ? 'selected="selected"' : set_select('time_month_day', 29); ?> value="29">29th</option>
					<option <?php echo isset($task) && $task->time_month_day == '30' ? 'selected="selected"' : set_select('time_month_day', 30); ?> value="30">30th</option>
				</select>
			</div>
		</div>

		<div class="control-group">
			<div class="controls">
				<label class="checkbox">
					<input type="checkbox" name="enabled" value="1" <?php echo isset($task) && $task->enabled == 1 ? 'checked="checked"' : set_checkbox('enabled', 1) ?> />
					Enabled?
				</label>
			</div>
		</div>
	</fieldset>

	<?php if ($task->running) : ?>
	<fieldset>
		<legend>Task Is Running</legend>

		<div class="control-group">
			<div class="controls">
				<p>This task appears to be currently running. If you are confident that it is not running currently, you may <a href="<?php echo site_url(SITE_AREA .'/settings/cronjobs/clear_running_flag/'. $task->id) ?>">Clear The Running Flag</a></p>
				<p><b>Warning: DO NOT USE if you are not familiar with the dangers of this command.</b></p>
			</div>
		</div>
	</fieldset>
<?php endif; ?>

	<div class="form-actions">
		<input type="submit" name="submit" value="Save Task" class="btn btn-primary" />
		&nbsp; or &nbsp; <a href="<?php echo site_url(SITE_AREA .'/settings/cronjobs') ?>">Cancel</a>
	</div>

	<?php echo form_close(); ?>
</div>
