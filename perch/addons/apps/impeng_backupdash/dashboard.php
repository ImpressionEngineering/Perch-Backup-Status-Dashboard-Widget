<?php 
	$API  = new PerchAPI(1.0, 'impeng_backupdash');
	$HTML = $API->get('HTML');
	$Lang = $API->get('Lang');
	$DB = PerchDB::fetch();

	// Change the number of backup results that are displayed
	$numRunsDisplayed = 5; 
?>
<div class="widget">
	<div class="dash-content">
		<header>
			<h2>
			<?php echo $Lang->get('Backup Status'); ?>
			</h2>
		</header>
		<div class="body">
			<?php
				$sql = 'SELECT * FROM '.PERCH_DB_PREFIX.'backup_plans';
				$result = $DB->get_count($sql);
				if (($result) == 0) {
					echo $Lang->get('No backup plans exist');
					return;
				}
			?>
			<ul class="dash-list">
				<?php
					$sql = 'SELECT * FROM '.PERCH_DB_PREFIX.'backup_runs';
					$result = $DB->get_count($sql);
					if ( $result == 0) {
						echo $Lang->get('No backup runs');
					} else {
						$sql = 'SELECT *
			                FROM '.PERCH_DB_PREFIX.'backup_runs
			                ORDER BY runDateTime DESC
			                LIMIT '.$numRunsDisplayed ;
						$result = $DB->get_rows($sql);
						foreach ($result as $run) {
							$sql = 'SELECT planTitle
										FROM '.PERCH_DB_PREFIX.'backup_plans
										WHERE planID='.$run['planID'].'
										LIMIT 1 ';
							$planName = $DB->get_rows($sql)[0]['planTitle'];
							echo "<li>";
							switch($run['runResult']) {
			                    case 'OK':
			                        echo PerchUI::icon('core/circle-check', 16, null, 'icon-status-success');
			                        break;

			                    case 'FAILED':
			                        echo PerchUI::icon('core/circle-delete', 16, null, 'icon-status-alert');
			                        break;

			                    case 'WARNING':
			                        echo PerchUI::icon('core/alert', 16, null, 'icon-status-warning');
			                        break;

			                    default:
			                        echo PerchUI::icon('core/info-alt', 16, null, 'icon-status-info');
			                        break;
		                	}
		                 	$link = $HTML->encode(PERCH_LOGINPATH.'/core/settings/backup/?id='.($run['planID']));
		                 	echo '<a href="'.$link.'">'.$planName.'</a>';
							echo '<span class="note">'.strftime(PERCH_DATE_SHORT .' @ '.PERCH_TIME_SHORT, strtotime($run['runDateTime'])).'</span>';
							echo "</li>";
						}
					}
				?>
			</ul>
		</div>
	</div>
</div>


