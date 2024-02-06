<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!doctype html>
<html lang="en" class="h-100">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Aspia Server">
		<meta name="author" content="lumian, lum@it-35.ru, www.it-35.ru">
		<title><?=$title;?></title>
		<link href="<?=base_url("style/bootstrap/5.2.3/css/bootstrap.min.css");?>" rel="stylesheet">
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

			.b-example-divider {
				height: 3rem;
				background-color: rgba(0, 0, 0, .1);
				border: solid rgba(0, 0, 0, .15);
				border-width: 1px 0;
				box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
			}

			.b-example-vr {
				flex-shrink: 0;
				width: 1.5rem;
				height: 100vh;
			}

			.bi {
				vertical-align: -.125em;
				fill: currentColor;
			}

			.nav-scroller {
				position: relative;
				z-index: 2;
				height: 2.75rem;
				overflow-y: hidden;
			}

			.nav-scroller .nav {
				display: flex;
				flex-wrap: nowrap;
				padding-bottom: 1rem;
				margin-top: -1px;
				overflow-x: auto;
				text-align: center;
				white-space: nowrap;
				-webkit-overflow-scrolling: touch;
			}
		</style>
		<!-- Custom styles for this template -->
		<link href="<?=base_url("style/admin.css");?>" rel="stylesheet">
		<link href="<?=base_url("style/fontawesome/6.2.1/css/all.css");?>" rel="stylesheet">
		<script src="<?=base_url("style/bootstrap/5.2.3/js/bootstrap.bundle.min.js");?>"></script>
		<script src="<?=base_url("style/jquery/jquery-3.6.3.min.js");?>"></script>
	</head>
	<body class="d-flex flex-column h-100">
    
	<header>
		<!-- Fixed navbar -->
		<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
			<div class="container-fluid">
				<a class="navbar-brand" href="<?=base_url();?>"><?=$this->config->item('site_title', 'aspia');?></a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarCollapse">
					<? $current_page1 = $this->uri->segment('1'); $current_page2 = $this->uri->segment('2');?>
					<ul class="navbar-nav me-auto mb-2 mb-md-0">
						<li class="nav-item">
							<a class="nav-link<?=($current_page1 == 'admin' AND $current_page2 == 'updates') ? ' active' : '';?>" href="<?=base_url('admin/updates');?>"><i class="fa-solid fa-box-open"></i> Обновления</a>
						</li>
						<li class="nav-item">
							<a class="nav-link<?=($current_page1 == 'admin' AND $current_page2 == 'installers') ? ' active' : '';?>" href="<?=base_url('admin/installers');?>"><i class="fa-solid fa-download"></i> Инсталляторы</a>
						</li>
						<li class="nav-item">
							<a class="nav-link<?=($current_page1 == 'admin' AND $current_page2 == 'packages') ? ' active' : '';?>" href="<?=base_url('admin/packages');?>"><i class="fa-solid fa-cubes"></i> Компоненты</a>
						</li>
						<li class="nav-item">
							<a class="nav-link<?=($current_page1 == 'admin' AND $current_page2 == 'stats') ? ' active' : '';?>" href="<?=base_url('admin/stats');?>"><i class="fa-solid fa-chart-line"></i> Статистика</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="https://it-35.ru/tags/aspia/" target="_blank"><i class="fa-solid fa-circle-info"></i> Нужна помощь?</a>
						</li>
					</ul>
					<div class="d-flex">
						<button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#ModalAuthLogout">Выход</button>
					</div>
				</div>
			</div>
		</nav>
	</header>

	<!-- Begin page content -->
	<main class="flex-shrink-0">
		<div class="container">
			<div class="mt-3">
				<?=$content;?>
			</div>
		</div>
	</main>

	<footer class="footer mt-auto py-3 bg-light">
		<div class="container">
			<span class="text-muted"><a href="<?=$this->config->item('link_author', 'aspia');?>" target="_blank"><?=$this->config->item('site_title', 'aspia');?></a> &copy; 2022-2024</span>
			
		</div>
	</footer>
	
	<div class="modal fade" id="ModalAuthLogout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ModalAuthLogoutLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="ModalAuthLogoutLabel">Выход из системы</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				Вы действительно хотите выйти из системы?
			</div>
			<div class="modal-footer flex-column border-top-0">
				<a href="<?=base_url('auth/logout');?>" type="button" class="btn btn-lg btn-danger w-100 mx-0 mb-2">Выйти</a>
				<button type="button" class="btn btn-lg btn-light w-100 mx-0" data-bs-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>
	
  </body>
</html>
