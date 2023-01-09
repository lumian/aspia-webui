<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	
	private $title = '';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	private function _RenderPage()
	{
		$global_page_data = array(
			'title'			=> $this->config->item('site_title', 'aspia').$this->title,
		);
		$this->load->view('auth/page_auth', $global_page_data);
	}
	
	public function index()
	{
		redirect('/auth/login/');
	}
	
	public function login()
	{
		//
		// Страница с вводом логина и пароля и проверкой данных на валидность
		//
		
		$this->session->set_flashdata('notice_error', FALSE);
		$this->session->set_flashdata('notice_success', FALSE);
		
		// Проверяем, вдруг пользователь уже авторизован
		if ($this->session->userdata('login'))
		{
			$this->session->set_flashdata('notice_error', FALSE);
			$this->session->set_flashdata('notice_success', 'Вы уже авторизованы.');
			$this->_auth_true();
		}
		
		// Если прилетели POST-данные, то работаем с ними
		if (count($this->input->post()) > 0)
		{
			// Получаем данные авторизации из POST
			$login_data = array(
				'login'			=> htmlspecialchars($this->input->post('login', TRUE)),
				'password'		=> htmlspecialchars($this->input->post('password', TRUE))
			);
			
			$admin_data = $this->config->item('credentials', 'aspia');
			
			// Если POST данные получены, то проверяем валидность пары login:password
			if (!is_null($login_data['login']) AND $login_data['login'] == $admin_data['login'] AND !is_null($login_data['password']) AND $login_data['password'] == $admin_data['password'])
			{
				$sess_data = array(
					'login'		=> $login_data['login']
				);
				$this->session->set_userdata($sess_data);
				
				// Выводим сообщение об успешности авторизации
				$this->session->set_flashdata('notice_success', 'Вы успешно авторизовались!');
				$this->session->set_flashdata('notice_error', FALSE);
				
				// Редиректим в личный кабинет
				$this->_auth_true();
			}
			else
			{
				// Если авторизация не удалась, то выводим сообщение о неуспешности авторизации
				$this->session->set_flashdata('notice_error', 'Авторизация не удалась.');
				$this->session->set_flashdata('notice_success', FALSE);
			}
		}
		
		$this->_RenderPage();
	}
	
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('/auth/login/');
	}
	
	private function _auth_true()
	{
		redirect('/admin/');
	}
}
