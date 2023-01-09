<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<h1>Обновления пакетов Aspia</h1>

<? if (is_array($packages_data) AND count($packages_data) > 0 AND is_array($installers_data) AND count($installers_data) > 0): ?>
	<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalAddUpdate"><i class="fa-solid fa-plus"></i> Добавить обновление</button>
<? else: ?>
	<div class="alert alert-danger" role="alert">
		Добавление обновлений недоступно по следующим причинам:
		<ul>
			<? if (!is_array($packages_data) OR count($packages_data) == 0): ?>
			<li>В базе данных не найдено пакетов. <a href="<?=base_url('admin/packages')?>">Перейти к настройке пакетов?</a></li>
			<? endif;?>
			<? if (!is_array($installers_data) OR count($installers_data) == 0): ?>
			<li>В базе данных не найдено инсталляторов. <a href="<?=base_url('admin/installers')?>">Перейти к настройке инсталляторов?</a></li>
			<? endif;?>
		</ul>
	</div>
<? endif; ?>

<? if ($this->session->flashdata('notice_success')): ?>
	<div class="alert alert-success mt-3" role="alert">
		<?=$this->session->flashdata('notice_success');?>
	</div>
<? endif;?>
<? if ($this->session->flashdata('notice_error')): ?>
	<div class="alert alert-danger mt-3" role="alert">
		<?=$this->session->flashdata('notice_error');?>
	</div>
<? endif;?>

<div class="alert alert-primary mt-2" role="alert">
	При настройке сервера обновлений Aspia в компонентах Aspia Host, Client и Console используйте следующий URL:
	<br><?=base_url();?>
</div>

<div class="table-responsive mt-3">
	<table class="table table-sm table-hover table-bordered">
		<thead>
			<tr>
				<th scope="col">Пакет</th>
				<th scope="col">Версия обновления</th>
				<th scope="col">Исходная версия</th>
				<th scope="col">Описание</th>
				<th scope="col">Действия</th>
			</tr>
		</thead>
		<? if ($updates_data != FALSE): ?>
		<tbody>
			<? foreach ($updates_data as $update_id => $update_info): ?>
			<tr>
				<td><?=$update_info['package_name'];?> <?=($update_info['package_description'] != '') ? '('.$update_info['package_description'].')' : '';?></td>
				<td><?=$update_info['update_target_version'];?></td>
				<td><?=$update_info['update_source_version'];?></td>
				<td><?=$update_info['update_description'];?></td>
				<td>
					<a href="<?=$update_info['installer_url'];?>" class="me-2" title="Скачать"><i class="fa-solid fa-download"></i></a>
					<a href="#" class="me-2" title="Редактировать" data-bs-toggle="modal" data-bs-target="#ModalEditUpdate" data-bs-unitid="<?=$update_info['update_id'];?>"><i class="fa-solid fa-pen-to-square"></i></a>
					<a href="#" class="me-2" title="Удалить" data-bs-toggle="modal" data-bs-target="#ModalDelUpdate" data-bs-unitid="<?=$update_info['update_id'];?>"><i class="fa-solid fa-trash"></i></a>
				</td>
			</tr>
		<? endforeach; ?>
		</tbody>
		<? endif;?>
	</table>
	<? if ($updates_data == FALSE): ?>
		<div class="alert alert-warning" role="alert">
			Обновления отсутствуют
		</div>
	<? endif; ?>
</div>

<div class="modal fade" id="ModalAddUpdate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ModalAddUpdateLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="ModalAddUpdateLabel">Добавление нового обновления</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="<?=base_url('admin/updates/add');?>" method="post" accept-charset="utf-8" id="ModalAddUpdateForm">
					<div class="mb-3">
						<label for="FormSourceVersion" class="form-label">Пакет *</label>
						<select class="form-select" id="FormPackageId" name="package_id" required>
							<? if (is_array($packages_data)): ?>
								<? foreach($packages_data as $package_info): ?>
									<option value="<?=$package_info['package_id'];?>"><?=$package_info['package_name'];?></option>
								<? endforeach; ?>
							<? endif; ?>
						</select>
					</div>
					<div class="mb-3">
						<label for="FormTargetVersion" class="form-label">Версия обновления *</label>
						<input type="text" class="form-control" id="FormTargetVersion" name="update_target_version" placeholder="2.5.2" required>
					</div>
					<div class="mb-3">
						<label for="FormSourceVersion" class="form-label">Исходная версия *</label>
						<input type="text" class="form-control" id="FormSourceVersion" name="update_source_version" placeholder="2.4.0" required>
					</div>
					<div class="mb-3">
						<label for="FormSourceVersion" class="form-label">Инсталлятор *</label>
						<select class="form-select" id="FormPackageId" name="installer_id" required>
							<? if (is_array($installers_data)): ?>
								<? foreach($installers_data as $installer_info): ?>
									<option value="<?=$installer_info['installer_id'];?>"><?=$installer_info['installer_name'];?></option>
								<? endforeach; ?>
							<? endif; ?>
						</select>
					</div>
					<div class="mb-3">
						<label for="FormDescription" class="form-label">Описание *</label>
						<textarea class="form-control" id="FormDescription" name="update_description" maxlength="250" rows="3" placeholder="Описание обновления (отображается на клиентской стороне и в админке)" required></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer flex-column border-top-0">
				<button type="submit" class="btn btn-lg btn-success w-100 mx-0 mb-2" form="ModalAddUpdateForm">Добавить</button>
				<button type="button" class="btn btn-lg btn-light w-100 mx-0" data-bs-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="ModalEditUpdate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ModalEditUpdateLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="ModalEditUpdateLabel">Редактирование обновления</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="#" method="post" accept-charset="utf-8" id="ModalEditInstallerForm">
					<div class="mb-3">
						<label for="FormSourceVersion" class="form-label">Пакет *</label>
						<select class="form-select" id="FormPackageId" name="package_id" required>
							<? if (is_array($packages_data)): ?>
								<? foreach($packages_data as $package_info): ?>
									<option value="<?=$package_info['package_id'];?>"><?=$package_info['package_name'];?></option>
								<? endforeach; ?>
							<? endif; ?>
						</select>
					</div>
					<div class="mb-3">
						<label for="FormTargetVersion" class="form-label">Версия обновления *</label>
						<input type="text" class="form-control" id="FormTargetVersion" name="update_target_version" placeholder="2.5.2" required>
					</div>
					<div class="mb-3">
						<label for="FormSourceVersion" class="form-label">Исходная версия *</label>
						<input type="text" class="form-control" id="FormSourceVersion" name="update_source_version" placeholder="2.4.0" required>
					</div>
					<div class="mb-3">
						<label for="FormSourceVersion" class="form-label">Инсталлятор *</label>
						<select class="form-select" id="FormPackageId" name="installer_id" required>
							<? if (is_array($installers_data)): ?>
								<? foreach($installers_data as $installer_info): ?>
									<option value="<?=$installer_info['installer_id'];?>"><?=$installer_info['installer_name'];?></option>
								<? endforeach; ?>
							<? endif; ?>
						</select>
					</div>
					<div class="mb-3">
						<label for="FormDescription" class="form-label">Описание *</label>
						<textarea class="form-control" id="FormDescription" name="update_description" maxlength="250" rows="3" placeholder="Описание обновления (отображается на клиентской стороне и в админке)" required></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer flex-column border-top-0">
				<button type="submit" class="btn btn-lg btn-success w-100 mx-0 mb-2" form="ModalEditInstallerForm">Сохранить</button>
				<button type="button" class="btn btn-lg btn-light w-100 mx-0" data-bs-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>
<script>
	$('#ModalEditUpdate').on('show.bs.modal', function (event) {
		var button = event.relatedTarget
		var unitid = button.getAttribute('data-bs-unitid')
		
		$.ajax({
			url: "<?=base_url('admin/ajax/get_update_info/');?>" + unitid,
			type: "GET",
			dataType: 'json',
			success: function(data) {
				$(".modal-body form").attr("action", "<?=base_url('admin/updates/edit/');?>" + unitid)
				$(".modal-body select[name=package_id]").val(data.package_id)
				$(".modal-body input[name=update_source_version]").val(data.update_source_version)
				$(".modal-body input[name=update_target_version]").val(data.update_target_version)
				$(".modal-body select[name=installer_id]").val(data.installer_id)
				$(".modal-body textarea[name=update_description]").val(data.update_description)
			},
			error: function() {
				$(".modal-body").html('Ошибка загрузки данных!')
			}
		});
	});
</script>

<div class="modal fade" id="ModalDelUpdate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ModalDelUpdateLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="ModalDelUpdateLabel">Удаление обновления</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				Вы действительно хотите удалить выбранное обновление?
			</div>
			<div class="modal-footer flex-column border-top-0">
				<a href="#" type="button" class="btn btn-lg btn-danger w-100 mx-0 mb-2">Удалить</button></a>
				<button type="button" class="btn btn-lg btn-light w-100 mx-0" data-bs-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>
<script>
	$('#ModalDelUpdate').on('show.bs.modal', function (event) {
		var button = event.relatedTarget
		var unitid = button.getAttribute('data-bs-unitid')
		
		$(".modal-footer a").attr("href", "<?=base_url('admin/updates/del/');?>" + unitid)
	});
</script>