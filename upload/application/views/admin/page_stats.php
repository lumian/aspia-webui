<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<h1>Статистика запросов к серверу</h1>

<? if ($pagination_links != ''): ?>
<nav>
	<ul class="pagination pagination justify-content-center mt-3">
		<li class="page-item disabled"><a class="page-link">Страницы</a></li>
		<?=$pagination_links;?>
	</ul>
</nav>
<? endif; ?>

<div class="table-responsive mt-3">
	<table class="table table-sm table-hover table-bordered">
		<thead>
			<tr>
				<th scope="col">Дата и время</th>
				<th scope="col">IP клиента</th>
				<th scope="col">Компонент</th>
				<th scope="col">ОС</th>
				<th scope="col">Арх</th>
				<th scope="col">Исходная версия</th>
				<th scope="col">Предложенное обновление</th>
			</tr>
		</thead>
		<? if ($stats_data != FALSE): ?>
		<tbody>
			<? foreach ($stats_data as $stats_id => $stats_info): ?>
			<tr>
				<td><?=$stats_info['stats_timestamp'];?></td>
				<td>
					<?=$stats_info['stats_query_ip'];?>
					<a href="https://whois7.ru/?q=<?=$stats_info['stats_query_ip'];?>" target="_blank" title="Посмотреть Whois через WhoIs7.Ru"><i class="fa-solid fa-globe"></i></a>
				</td>
				<td>
					<?=$stats_info['stats_query_packet'];?> 
					<? if (!is_numeric($stats_info['stats_local_packet_id'])): ?>
						<span class="text-danger">(не поддерживается)</span>
					<? endif; ?>
				</td>
				<td><?=$stats_info['stats_query_os'];?></td>
				<td><?=$stats_info['stats_query_arch'];?></td>
				<td><?=$stats_info['stats_query_version'];?></td>
				<td>
					<? if (isset($updates_data[$stats_info['stats_proposed_update_id']])) :?>
						<span class="text-success">
							<?=$updates_data[$stats_info['stats_proposed_update_id']]['package_name'].' '.$updates_data[$stats_info['stats_proposed_update_id']]['update_source_version'].' => '.$updates_data[$stats_info['stats_proposed_update_id']]['update_target_version'];?>
						</span>
					<? elseif (is_numeric($stats_info['stats_proposed_update_id'])) : ?>
						<span class="text-danger">Error (ID #<?=$stats_info['stats_proposed_update_id'];?>)</span>
					<? else: ?>
						Нет подходящего обновления
					<? endif; ?>
				</td>
			</tr>
		<? endforeach; ?>
		</tbody>
		<? endif;?>
	</table>
	<? if ($stats_data == FALSE): ?>
		<div class="alert alert-warning" role="alert">
			Статистика отсутствует
		</div>
	<? endif; ?>
</div>

<? if ($pagination_links != ''): ?>
<nav>
	<ul class="pagination pagination justify-content-center mt-3">
		<li class="page-item disabled"><a class="page-link">Страницы</a></li>
		<?=$pagination_links;?>
	</ul>
</nav>
<? endif; ?>