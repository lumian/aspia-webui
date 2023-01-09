<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<h1>Пакеты Aspia</h1>

<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalAddPackage"><i class="fa-solid fa-plus"></i> Добавить пакет</button>

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
	<? if ($packages_data == FALSE): ?>В базе данных не найдено пакетов Aspia. Пожалуйста, настройте необходимые вам пакеты.<br /><? endif;?>
	По-умолчанию, Aspia использует следующие идентификаторы пакетов:
	<ul>
		<li><strong>host</strong> - Aspia Host
		<li><strong>client</strong> - Aspia Client
		<li><strong>console</strong> - Aspia Console
	</ul>
</div>

<div class="table-responsive mt-3">
	<table class="table table-sm table-hover table-bordered">
		<thead>
			<tr>
				<th scope="col">Название</th>
				<th scope="col">Описание</th>
				<th scope="col">Действия</th>
			</tr>
		</thead>
		<? if ($packages_data != FALSE): ?>
		<tbody>
			<? foreach ($packages_data as $package_id => $package_info): ?>
			<tr>
				<td><?=$package_info['package_name'];?></td>
				<td><?=$package_info['package_description'];?></td>
				<td>
					<a href="#" class="me-2" title="Редактировать" data-bs-toggle="modal" data-bs-target="#ModalEditPackage" data-bs-unitid="<?=$package_info['package_id'];?>"><i class="fa-solid fa-pen-to-square"></i></a>
					<a href="#" class="me-2" title="Удалить" data-bs-toggle="modal" data-bs-target="#ModalDelPackage" data-bs-unitid="<?=$package_info['package_id'];?>"><i class="fa-solid fa-trash"></i></a>
				</td>
			</tr>
		<? endforeach; ?>
		</tbody>
		<? endif;?>
	</table>
	<? if ($packages_data == FALSE): ?>
		<div class="alert alert-warning" role="alert">
			Пакеты отсутствуют
		</div>
	<? endif; ?>
</div>

<div class="modal fade" id="ModalAddPackage" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ModalAddPackageLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="ModalAddPackageLabel">Добавление нового пакета</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="<?=base_url('admin/packages/add');?>" method="post" accept-charset="utf-8" id="ModalAddPackageForm">
					<div class="mb-3">
						<label for="FormName" class="form-label">Название *</label>
						<input type="text" class="form-control" id="FormName" name="package_name" placeholder="console" required>
					</div>
					<div class="mb-3">
						<label for="FormDescription" class="form-label">Описание</label>
						<textarea class="form-control" id="FormDescription" name="package_description" maxlength="250" rows="3" placeholder="Любое описание (отображается только в админке)"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer flex-column border-top-0">
				<button type="submit" class="btn btn-lg btn-success w-100 mx-0 mb-2" form="ModalAddPackageForm">Добавить</button>
				<button type="button" class="btn btn-lg btn-light w-100 mx-0" data-bs-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="ModalEditPackage" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ModalEditPackageLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="ModalEditPackageLabel">Редактирование пакета</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="#" method="post" accept-charset="utf-8" id="ModalEditPackageForm">
					<div class="mb-3">
						<label for="FormName" class="form-label">Название *</label>
						<input type="text" class="form-control" id="FormName" name="package_name" placeholder="console" required>
					</div>
					<div class="mb-3">
						<label for="FormDescription" class="form-label">Описание</label>
						<textarea class="form-control" id="FormDescription" name="package_description" maxlength="250" rows="3" placeholder="Любое описание (отображается только в админке)"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer flex-column border-top-0">
				<button type="submit" class="btn btn-lg btn-success w-100 mx-0 mb-2" form="ModalEditPackageForm">Сохранить</button>
				<button type="button" class="btn btn-lg btn-light w-100 mx-0" data-bs-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>
<script>
	$('#ModalEditPackage').on('show.bs.modal', function (event) {
		var button = event.relatedTarget
		var unitid = button.getAttribute('data-bs-unitid')
		
		$.ajax({
			url: "<?=base_url('admin/ajax/get_package_info/');?>" + unitid,
			type: "GET",
			dataType: 'json',
			success: function(data) {
				$(".modal-body form").attr("action", "<?=base_url('admin/packages/edit/');?>" + unitid)
				$(".modal-body input[name=package_name]").val(data.package_name)
				$(".modal-body textarea[name=package_description]").val(data.package_description)
			},
			error: function() {
				$(".modal-body").html('Ошибка загрузки данных!')
			}
		});
	});
</script>

<div class="modal fade" id="ModalDelPackage" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ModalDelPackageLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="ModalDelPackageLabel">Удаление пакета</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				Вы действительно хотите удалить выбранный пакет?
			</div>
			<div class="modal-footer flex-column border-top-0">
				<a href="#" type="button" class="btn btn-lg btn-danger w-100 mx-0 mb-2">Удалить</button></a>
				<button type="button" class="btn btn-lg btn-light w-100 mx-0" data-bs-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>
<script>
	$('#ModalDelPackage').on('show.bs.modal', function (event) {
		var button = event.relatedTarget
		var unitid = button.getAttribute('data-bs-unitid')
		
		$(".modal-footer a").attr("href", "<?=base_url('admin/packages/del/');?>" + unitid)
	});
</script>