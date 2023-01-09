<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!doctype html>
<html lang="en">
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
		<link href="<?=base_url("style/signin.css");?>" rel="stylesheet">
	</head>
	<body class="text-center">
		<main class="form-signin w-100 m-auto">
			<form action="<?=current_url();?>" method="post" accept-charset="utf-8">
				<img class="mb-4" src="<?=base_url("style/img/aspia_logo.png");?>" alt="" width="72" height="57">
				<h1 class="h3 mb-3 fw-normal">Please sign in</h1>
				
				<? if ($this->session->flashdata('notice_success')): ?>
					<div class="alert alert-success" role="alert">
						<?=$this->session->flashdata('notice_success');?>
					</div>
				<? endif;?>
				<? if ($this->session->flashdata('notice_error')): ?>
					<div class="alert alert-danger" role="alert">
						<?=$this->session->flashdata('notice_error');?>
					</div>
				<? endif;?>
				<div class="form-floating">
					<input type="text" class="form-control" id="FloatingLogin" name="login" placeholder="Login">
					<label for="FloatingLogin">Login</label>
				</div>
				<div class="form-floating">
					<input type="password" class="form-control" id="FloatingPassword" name="password" placeholder="Password">
					<label for="FloatingPassword">Password</label>
				</div>
				<button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
			</form>
		</main>
	</body>
</html>
