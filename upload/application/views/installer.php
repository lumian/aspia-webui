<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="GRCentral">
	
	<title><?=$title;?></title>
	
	<link href="<?=base_url("style/bootstrap/5.2.3/css/bootstrap.min.css");?>" rel="stylesheet">
	<link href="<?=base_url("style/fontawesome/6.2.1/css/all.css");?>" rel="stylesheet">
	<meta name="theme-color" content="#7952b3">

	<style>
		.bd-placeholder-img {
			font-size: 1.125rem;
			text-anchor: middle;
			-webkit-user-select: none;
			-moz-user-select: none;
			user-select: none;
		}

		@media (min-width: 768px) {
			.bd-placeholder-img-lg {
				font-size: 3.5rem;
			}
		}
	</style>
</head>
<body>
	<div class="col-lg-8 mx-auto p-3 py-md-5">
		<header class="d-flex align-items-center pb-3 mb-3 border-bottom">
			<span class="fs-4">Установщик <?=$title;?></span>
		</header>

		<main>
		
		<? if ($step == 'welcome'): ?>
		
			<h3 class="mb-4">Добро пожаловать в установщик <?=$title;?>!</h3>
			<p class="fs-5 col-md-8">Этот установщик поможет вам установить <?=$title;?> на ваш сервер. Чтобы выполнить установку, выполните действия, используя кнопки ниже.</p>

			<div class="mt-4">
				<hr>
				<a href="<?=base_url('installer/step/1');?>" class="btn btn-success btn-lg px-4">Начать <i class="fa fa-angle-double-right"></i></a>
			</div>
			
		<? elseif ($step == '1'): ?>
		
			<h3 class="mb-4">Проверка доступа к файлам и директориям</h3>
			
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<th>Имя файла/директории</th>
						<th>Доступ на запись</th>
					</tr>
				</thead>
				<tbody>
					<? foreach($content['check_result'] as $file): ?>
						<tr><td><?=$file['name'];?></td><? if ($file['write']): ?><td class="table-success"><i class="fa fa-check"></i> Доступ есть</td><? else: ?><td class="table-danger"><i class="fa fa-times"></i> Доступа нет</td><? endif; ?></tr>
					<? endforeach; ?>
				</tbody>
			</table>
			
			<? if ($content['status_ok']): ?>
				<div class="alert alert-success mt-4" role="alert">
					 <h4 class="alert-heading"><i class="fa fa-check"></i> Отлично!</h4>
					<p>Проверки были успешно пройдены. Вы можете перейти к следующему шагу.</p>
				</div>
			<? else: ?>
				<div class="alert alert-danger mt-4" role="alert">
					<h4 class="alert-heading"><i class="fa fa-times"></i> Ошибка!</h4>
					<p>Не удалось получить доступ на запись к одному или нескольким файлам или каталогам. Установите разрешения на запись для вышеуказанных файлов и каталогов и повторите попытку.</p>
				</div>
			<? endif; ?>
			
			<div class="mt-4">
				<hr>
				<a href="<?=base_url('installer/');?>" class="btn btn-secondary btn-lg px-4"><i class="fa fa-angle-double-left"></i> Вернуться</a>
				<? if (!$content['status_ok']): ?><a href="<?=base_url('installer/step/1');?>" class="btn btn-warning btn-lg px-4"><i class="fa fa-sync"></i> Проверить снова</a><? endif; ?>
				<a href="<?=base_url('installer/step/2');?>" class="btn btn-success btn-lg px-4<? if (!$content['status_ok']): ?> disabled<? endif; ?>">Далее <i class="fa fa-angle-double-right"></i></a>
			</div>
			
		<? elseif ($step == '2'): ?>
		
			<h3 class="mb-4">Настройки базы данных и системы</h3>
			
			<p class="fs-5 col-md-8">Заполните все поля в форме ниже. Некоторые поля были заполнены автоматически на основе текущих файлов конфигурации или переменных среды.<br /><i>Все поля обязательны для заполнения.</i></p>
			
			<form id="Settings" method="post" action="/installer/step/2">
				<div class="row">
					<div class="col-md-6">
						<div class="card">
							<div class="card-header">
								Системные настройки
							</div>
							<div class="card-body">
								<div class="row mt-2">
									<label for="system_domain" class="col-sm-3 col-form-label">Домен сайта</label>
									<div class="col-sm-9">
										<input type="text" name="system_domain" class="form-control" id="system_domain" value="<?=$content['post_data']['system_domain'];?>" required>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="card">
							<div class="card-header">
								Административный доступ (По-умолчанию: admin:admin)
							</div>
							<div class="card-body">
								<div class="row mt-2">
									<label for="admin_login" class="col-sm-3 col-form-label">Логин</label>
									<div class="col-sm-9">
										<input type="text" name="admin_login" class="form-control" id="admin_login" value="<?=$content['post_data']['admin_login'];?>" required>
									</div>
								</div>
								<div class="row mt-2">
									<label for="admin_password" class="col-sm-3 col-form-label">Пароль</label>
									<div class="col-sm-9">
										<input type="password" name="admin_password" class="form-control" id="admin_password" value="<?=$content['post_data']['admin_password'];?>" required>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col-sm-6">
						<div class="card <?if($content['status_db_ok'] === FALSE): ?> border-danger<? endif;?>">
							<div class="card-header">
								Настройки базы данных
							</div>
							<div class="card-body">
								<div class="row">
									<label for="database_hostname" class="col-sm-3 col-form-label">Хост</label>
									<div class="col-sm-9">
										<input type="text" name="database_hostname" class="form-control" id="database_hostname" value="<?=$content['post_data']['database_hostname'];?>" required>
									</div>
								</div>
								<div class="row mt-2">
									<label for="database_username" class="col-sm-3 col-form-label">Логин</label>
									<div class="col-sm-9">
										<input type="text" name="database_username" class="form-control" id="database_username" value="<?=$content['post_data']['database_username'];?>" required>
									</div>
								</div>
								<div class="row mt-2">
									<label for="database_password" class="col-sm-3 col-form-label">Пароль</label>
									<div class="col-sm-9">
										<input type="password" name="database_password" class="form-control" id="database_password" value="<?=$content['post_data']['database_password'];?>" required>
									</div>
								</div>
								<div class="row mt-2">
									<label for="database_name" class="col-sm-3 col-form-label">Название БД</label>
									<div class="col-sm-9">
										<input type="text" name="database_name" class="form-control" id="database_name" value="<?=$content['post_data']['database_name'];?>" required>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col-sm-12">
						<button class="btn btn-success btn-lg px-4"><i class="fa fa-save"></i> Сохранить настройки </a>
					</div>
				</div>
			</form>
			
			<? if ($content['status_ok'] === TRUE): ?>
				<div class="alert alert-success mt-4" role="alert">
					 <h4 class="alert-heading"><i class="fa fa-check"></i> Отлично!</h4>
					<p>Проверки были успешно пройдены. Вы можете перейти к следующему шагу.</p>
				</div>
			<? elseif ($content['status_ok'] === FALSE): ?>
				<div class="alert alert-danger mt-4" role="alert">
					<h4 class="alert-heading"><i class="fa fa-times"></i> Ошибка</h4>
					<p>
					В процессе проверки данных произошли ошибки.
					<? if ($content['status_db_ok'] === FALSE): ?>
						<br />Не удалось подключиться к серверу базы данных с использованием предоставленных параметров.
					<? endif; ?>
					</p>
				</div>
			<? endif; ?>
			
			<div class="mt-4">
				<hr>
				<a href="<?=base_url('installer/step/1');?>" class="btn btn-secondary btn-lg px-4"><i class="fa fa-angle-double-left"></i> Вернуться</a>
				<a href="<?=base_url('installer/step/3');?>" class="btn btn-success btn-lg px-4<? if (!$content['status_ok']): ?> disabled<? endif; ?>">Далее <i class="fa fa-angle-double-right"></i></a>
			</div>
		
		<? elseif ($step == '3'): ?>
		
			<h3 class="mb-4">Установка <?=$title;?> выполнена успешно!</h3>
			<p class="fs-5 col-md-8">Вы выполнили все необходимые шаги для установки <?=$title;?>.<br />Теперь вы можете перейти к веб-интерфейсу и начать использовать этот продукт.</p>
			
			<div class="mb-5 mt-4 ">
				<hr>
				<a href="<?=base_url('');?>" class="btn btn-success btn-lg px-4">Перейти в веб-интерфейс <i class="fa fa-angle-double-right"></i></a>
			</div>
		
		<? else:?>
		
			<h3 class="mb-4">Ошибка!</h3>
			<p class="fs-5 col-md-8">Шаг установки не распознан.<br />Пожалуйста, вернитесь к началу установки и повторите попытку.</p>
			
			<div class="mt-4">
				<hr>
				<a href="<?=base_url('installer/');?>" class="btn btn-secondary btn-lg px-4"><i class="fa fa-angle-double-left"></i> Вернуться в начало</a>
			</div>
			
		<? endif;?>
		
		</main>
		<footer class="pt-2 my-3 text-muted border-top">
			<span class="text-muted"><a href="<?=$this->config->item('link_author', 'aspia');?>" target="_blank"><?=$this->config->item('site_title', 'aspia');?></a> &copy; 2022-2023</span>
		</footer>
	</div>
</body>
</html>