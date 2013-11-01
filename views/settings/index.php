<style>
	table td { vertical-align: middle !important; }
	table tr td span {
		display: block;
		font-style: italic;
		color: #999;
		font-size: 90%;
	}
	tr.disabled td { color: #999; }
	tr.disabled .date {
		text-decoration: line-through;
	}
</style>

<div>
	<a href="<?php echo site_url(SITE_AREA .'/settings/cronjobs/create') ?>" class="">Add New Task</a>
</div>

<br/>

<p class="intro">Last Ran: <?php echo relative_time(strtotime($this->settings_lib->item('cron_last_run'))) ?>
	&nbsp;&nbsp;<span class="small">( <?php echo date('m-d-Y h:i a', strtotime($this->settings_lib->item('cron_last_run'))) ?> )</span>
</p>

<div class="admin-box rounded">
	<h3>Cronjobs</h3>

	<?php if (isset($tasks) && is_array($tasks) && count($tasks)) : ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Title</th>
					<th>Module</th>
					<th>Next Run</th>
					<th class="text-center" style="width: 4em">Min</th>
					<th class="text-center" style="width: 4em">Hour</th>
					<th class="text-center" style="width: 4em">MDay</th>
					<th class="text-center" style="width: 4em">WDay</th>
					<th class="text-center" style="width: 5em">Actions</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="8" class="text-center">
						Current server time is: <?php echo date('D F j Y h:i A T'); ?>
						<br/><i class="icon icon-fire"></i> means task is currently running
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php foreach ($tasks as $task) : ?>
				<tr <?php echo $task->enabled == true ? '' : 'class="disabled"' ?>>
					<td>
						<b>
						<?php
							if ($task->running) echo '<i class="icon icon-fire"></i>&nbsp;&nbsp;';
							echo $task->title
						?>
						</b>
						<span><?php echo $task->description ?></span>
						<?php if (isset($task->last_start) && $task->last_start != 0) : ?>
							<p>Last Ran: <?php echo date('n/j/Y H:i:s', $task->last_start); ?> - Ended: <?php echo date('n/j H:i:s', $task->last_stop); ?>
								(<?php echo timespan($task->last_start, $task->last_stop) ?>)
						<?php endif; ?>
					</td>
					<td><?php echo ucfirst($task->module) ?></td>
					<td class="date"><?php echo date('D F j Y h:i A T', $task->next_run) ?></td>
					<td class="text-center"><?php echo $task->time_minutes == '0' ? '-' : $task->time_minutes; ?></td>
					<td class="text-center"><?php echo $task->time_hours == -1 ? '-' : $task->time_hours; ?></td>
					<td class="text-center"><?php echo $task->time_month_day == '0' ? '-' : $task->time_month_day; ?></td>
					<td class="text-center"><?php echo $task->time_week_day == 0 ? '-' : $task->time_week_day; ?></td>
					<td class="text-center">
						<a href="<?php echo site_url(SITE_AREA .'/settings/cronjobs/edit/'. $task->id) ?>">Edit</a> -
						<a href="<?php echo site_url('cron?run='. $task->id) ?>" target="_blank">Run</a>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<div class="alert alert-warning">
			No cronjobs found.
		</div>
	<?php endif; ?>
</div>