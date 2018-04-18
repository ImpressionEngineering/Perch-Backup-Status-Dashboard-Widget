<?php
   	if ($CurrentUser->logged_in()) {
   		$this->register_app('impeng_backupdash', 'Backups Widget', 1, 'Dashboard Widget to show staus of recent Runway Backups', '1', true);
    	$this->require_version('impeng_backupdash', '0.1');
	}
?>