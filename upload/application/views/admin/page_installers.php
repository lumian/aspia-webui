<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<h1>Инсталляторы Aspia</h1>

<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalAddInstaller"><i class="fa-solid fa-plus"></i> Добавить инсталлятор</button>

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
	<? if ($installers_data == FALSE): ?>В базе данных не найдено инсталляторов Aspia. Пожалуйста, настройте необходимые вам инсталляторы.<br /><? endif;?>
	Оригинальные пакеты MSI, вы можете скачать в <a href="https://github.com/dchapyshev/aspia/releases/" target="_blank">официальном репозитории Aspia на Github</a>.
</div>

<div class="table-responsive mt-3">
	<table class="table table-sm table-hover table-bordered">
		<thead>
			<tr>
				<th scope="col">Наименование</th>
				<th scope="col">Описание</th>
				<th scope="col">ОС</th>
				<th scope="col">Архитектура</th>
				<th scope="col">Имя загруженного файла</th>
				<th scope="col">Действия</th>
			</tr>
		</thead>
		<? if ($installers_data != FALSE): ?>
		<tbody>
			<? foreach ($installers_data as $installer_id => $installer_info): ?>
			<tr>
				<td><?=$installer_info['installer_name'];?></td>
				<td><?=$installer_info['installer_description'];?></td>
				<td><?=$installer_info['installer_os'];?></td>
				<td><?=$installer_info['installer_arch'];?></td>
				<td><?=$installer_info['installer_file_name_real'];?></td>
				<td>
					<a href="<?=$this->config->item('storage_url', 'aspia').$installer_info['installer_file_name_real'];?>" class="me-2" title="Скачать"><i class="fa-solid fa-download"></i></a>
					<a href="#" class="me-2" title="Редактировать" data-bs-toggle="modal" data-bs-target="#ModalEditInstaller" data-bs-unitid="<?=$installer_info['installer_id'];?>"><i class="fa-solid fa-pen-to-square"></i></a>
					<a href="#" class="me-2" title="Удалить" data-bs-toggle="modal" data-bs-target="#ModalDelInstaller" data-bs-unitid="<?=$installer_info['installer_id'];?>"><i class="fa-solid fa-trash"></i></a>
				</td>
			</tr>
		<? endforeach; ?>
		</tbody>
		<? endif;?>
	</table>
	<? if ($installers_data == FALSE): ?>
		<div class="alert alert-warning" role="alert">
			Инсталляторы отсутствуют
		</div>
	<? endif; ?>
</div>

<div class="modal fade" id="ModalAddInstaller" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ModalAddInstallerLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="ModalAddInstallerLabel">Добавление нового инстяллятора</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="<?=base_url('admin/installers/add');?>" method="post" accept-charset="utf-8" id="ModalAddInstallerForm" enctype="multipart/form-data">
					<div class="mb-3">
						<label for="FormName" class="form-label">Название *</label>
						<input type="text" class="form-control" id="FormName" name="installer_name" placeholder="Aspia Host v.2.5.0" required>
					</div>
					<div class="mb-3">
						<label for="FormDescription" class="form-label">Описание</label>
						<textarea class="form-control" id="FormDescription" name="installer_description" maxlength="250" rows="3" placeholder="Любое описание (отображается только в админке)"></textarea>
					</div>
					<div class="mb-3">
						<label for="FormOS" class="form-label">Операционная система *</label>
						<select class="form-select" id="FormOS" name="installer_os" required>
							<? foreach($support_os as $os): ?>
								<option value="<?=$os;?>"><?=$os;?></option>
							<? endforeach;?>
						</select>
					</div>
					<div class="mb-3">
						<label for="FormArch" class="form-label">Архитектура *</label>
						<select class="form-select" id="FormArch" name="installer_arch" required>
							<? foreach($support_arch as $arch): ?>
								<option value="<?=$arch;?>"><?=$arch;?></option>
							<? endforeach;?>
						</select>
					</div>
					<div class="mb-3">
						<label for="FormFile">Файл инсталлятора</label>
						<input class="form-control" type="file" id="FormFile" name="installer_file" required>
						<small id="FormFileHelp" class="text-muted">Максимальный размер файла: <?=$this->aspia->max_size_upload('MB');?> Mb</small>
					</div>
				</form>
			</div>
			<div class="modal-footer flex-column border-top-0">
				<button type="submit" class="btn btn-lg btn-success w-100 mx-0 mb-2" form="ModalAddInstallerForm">Добавить</button>
				<button type="button" class="btn btn-lg btn-light w-100 mx-0" data-bs-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="ModalEditInstaller" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ModalEditInstallerLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="ModalEditInstallerLabel">Редактирование инстяллятора</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="#" method="post" accept-charset="utf-8" id="ModalEditInstallerForm">
					<div class="mb-3">
						<label for="FormName" class="form-label">Название *</label>
						<input type="text" class="form-control" id="FormName" name="installer_name" placeholder="Aspia Host v.2.5.0" required>
					</div>
					<div class="mb-3">
						<label for="FormDescription" class="form-label">Описание</label>
						<textarea class="form-control" id="FormDescription" name="installer_description" maxlength="250" rows="3" placeholder="Любое описание (отображается только в админке)"></textarea>
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
	$('#ModalEditInstaller').on('show.bs.modal', function (event) {
		var button = event.relatedTarget
		var unitid = button.getAttribute('data-bs-unitid')
		
		$.ajax({
			url: "<?=base_url('admin/ajax/get_installer_info/');?>" + unitid,
			type: "GET",
			dataType: 'json',
			success: function(data) {
				$(".modal-body form").attr("action", "<?=base_url('admin/installers/edit/');?>" + unitid)
				$(".modal-body input[name=installer_name]").val(data.installer_name)
				$(".modal-body input[name=installer_url]").val(data.installer_url)
				$(".modal-body textarea[name=installer_description]").val(data.installer_description)
			},
			error: function() {
				$(".modal-body").html('Ошибка загрузки данных!')
			}
		});
	});
</script>

<div class="modal fade" id="ModalDelInstaller" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ModalDelInstallerLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="ModalDelInstallerLabel">Удаление инсталлятора</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				Вы действительно хотите удалить выбранный инсталлятор?
			</div>
			<div class="modal-footer flex-column border-top-0">
				<a href="#" type="button" class="btn btn-lg btn-danger w-100 mx-0 mb-2">Удалить</a>
				<button type="button" class="btn btn-lg btn-light w-100 mx-0" data-bs-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>
<script>
	$('#ModalDelInstaller').on('show.bs.modal', function (event) {
		var button = event.relatedTarget
		var unitid = button.getAttribute('data-bs-unitid')
		
		$(".modal-footer a").attr("href", "<?=base_url('admin/installers/del/');?>" + unitid)
	});
</script>