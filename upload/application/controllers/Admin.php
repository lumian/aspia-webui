<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	private $title = '';
	private $content = '';
	private $user_info = NULL;
	
	public function __construct()
	{
		parent::__construct();
		
		if ($this->config->item('system_installed', 'aspia'))
		{
			$this->load->database();
			$this->load->library('session');
		}
		else
		{
			redirect('installer');
		}
		
		if ($this->session->userdata('login'))
		{
			$this->user_info = $this->session->userdata();
			$this->load->model('aspia_model');
			$this->load->library('aspia');
		}
		else
		{
			redirect('auth/login');
		}
	}
	
	private function _RenderPage()
	{
		$global_page_data = array(
			'title'			=> $this->config->item('site_title', 'aspia').$this->title,
			'content'		=> $this->content
		);
		$this->load->view('base_template', $global_page_data);
	}
	
	public function ajax($type=NULL, $unit=NULL)
	{
		if (!$this->input->is_ajax_request())
		{
			show_404();
		}
		
		$output_json_data = NULL;
		
		if (!is_null($type) AND !is_null($unit))
		{
			if ($type == 'get_update_info' AND is_numeric($unit))
			{
				$update_info = $this->aspia_model->get_update_info($unit);
				
				if ($update_info != FALSE)
				{
					$output_json_data = array(
						'package_id'				=> htmlspecialchars_decode($update_info['package_id']),
						'update_source_version'		=> htmlspecialchars_decode($update_info['update_source_version']),
						'update_target_version'		=> htmlspecialchars_decode($update_info['update_target_version']),
						'update_description'		=> htmlspecialchars_decode($update_info['update_description']),
						'installer_id'				=> htmlspecialchars_decode($update_info['installer_id'])
					);
				}
				else
				{
					show_404();
				}
			}
			elseif ($type == 'get_package_info' AND is_numeric($unit))
			{
				$package_info = $this->aspia_model->get_package_info('id', $unit);
				
				if ($package_info != FALSE)
				{
					$output_json_data = array(
						'package_id'				=> htmlspecialchars_decode($package_info['package_id']),
						'package_name'				=> htmlspecialchars_decode($package_info['package_name']),
						'package_description'		=> htmlspecialchars_decode($package_info['package_description'])
					);
				}
				else
				{
					show_404();
				}
			}
			elseif ($type == 'get_installer_info' AND is_numeric($unit))
			{
				$installer_info = $this->aspia_model->get_installer_info('id', $unit);
				
				if ($installer_info != FALSE)
				{
					$output_json_data = array(
						'installer_id'				=> htmlspecialchars_decode($installer_info['installer_id']),
						'installer_name'			=> htmlspecialchars_decode($installer_info['installer_name']),
						'installer_description'		=> htmlspecialchars_decode($installer_info['installer_description']),
						'installer_url'				=> htmlspecialchars_decode($installer_info['installer_url'])
					);
				}
				else
				{
					show_404();
				}
			}
			else
			{
				show_404();
			}
			
			if (!is_null($output_json_data))
			{
				$this->output
						->set_content_type('application/json')
						->set_output(json_encode($output_json_data, JSON_UNESCAPED_UNICODE));
			}
			else
			{
				//show_404();
			}
		}
		else
		{
			show_404();
		}
	}
	
	public function index()
	{
		redirect('admin/updates');
	}
	
	public function updates($type=NULL, $unit=NULL)
	{
		$this->title = ' - Обновления';
		
		if (is_null($type))
		{
			$page_data = array(
				'updates_data'		=> $this->aspia_model->get_update_list(),
				'installers_data'	=> $this->aspia_model->get_installer_list(),
				'packages_data'		=> $this->aspia_model->get_package_list()
			);
			$this->content = $this->load->view('admin/page_updates', $page_data, TRUE);
		}
		elseif ($type == 'add' AND is_null($unit))
		{
			if (count($this->input->post()) > 0)
			{
				$post_data = array(
					'package_id'					=> htmlspecialchars($this->input->post('package_id', TRUE)),
					'update_source_version'			=> htmlspecialchars($this->input->post('update_source_version', TRUE)),
					'update_target_version'			=> htmlspecialchars($this->input->post('update_target_version', TRUE)),
					'update_description'			=> htmlspecialchars($this->input->post('update_description', TRUE)),
					'installer_id'					=> htmlspecialchars($this->input->post('installer_id', TRUE))
				);
				
				if (!is_null($post_data['package_id']) AND !is_null($post_data['update_source_version']) AND !is_null($post_data['update_target_version']) AND !is_null($post_data['update_description']) AND !is_null($post_data['installer_id']))
				{
					$query = $this->aspia_model->add_update($post_data);
					
					if ($query != FALSE)
					{
						$this->session->set_flashdata('notice_success', 'Обновление успешно добавлено в базу данных.');
						$this->session->set_flashdata('notice_error', FALSE);
					}
					else
					{
						$this->session->set_flashdata('notice_success', FALSE);
						$this->session->set_flashdata('notice_error', 'Произошла ошибка. Обновление не добавлено в базу данных.');
					}
				}
				else
				{
					$this->session->set_flashdata('notice_success', FALSE);
					$this->session->set_flashdata('notice_error', 'Произошла ошибка. Обновление не добавлено в базу данных. Не переданы обязательные поля.');
				}
				redirect('admin/updates');
			}
			else
			{
				show_404();
			}
		}
		elseif ($type == 'edit' AND !is_null($unit))
		{
			if (count($this->input->post()) > 0)
			{
				$post_data = array(
					'package_id'					=> htmlspecialchars($this->input->post('package_id', TRUE)),
					'update_source_version'			=> htmlspecialchars($this->input->post('update_source_version', TRUE)),
					'update_target_version'			=> htmlspecialchars($this->input->post('update_target_version', TRUE)),
					'update_description'			=> htmlspecialchars($this->input->post('update_description', TRUE)),
					'installer_id'					=> htmlspecialchars($this->input->post('installer_id', TRUE))
				);
				
				if (!is_null($post_data['package_id']) AND !is_null($post_data['update_source_version']) AND !is_null($post_data['update_target_version']) AND !is_null($post_data['update_description']) AND !is_null($post_data['installer_id']))
				{
					$query = $this->aspia_model->edit_update($unit, $post_data);
					
					if ($query != FALSE)
					{
						$this->session->set_flashdata('notice_success', 'Обновление успешно отредактировано.');
						$this->session->set_flashdata('notice_error', FALSE);
					}
					else
					{
						$this->session->set_flashdata('notice_success', FALSE);
						$this->session->set_flashdata('notice_error', 'Произошла ошибка. Обновление не отредактировано.');
					}
				}
				else
				{
					$this->session->set_flashdata('notice_success', FALSE);
					$this->session->set_flashdata('notice_error', 'Произошла ошибка. Обновление не отредактировано. Не переданы обязательные поля.');
				}
				redirect('admin/updates');
			}
			else
			{
				show_404();
			}
		}
		elseif ($type == 'del' AND !is_null($unit))
		{
			$update_info = $this->aspia_model->get_update_info($unit);
			
			if ($update_info != FALSE AND is_array($update_info))
			{
				$result = $this->aspia_model->del_update($unit);
				if ($result)
				{
					$this->session->set_flashdata('notice_success', 'Обновление успешно удалено.');
					$this->session->set_flashdata('notice_error', FALSE);
				}
				else
				{
					$this->session->set_flashdata('notice_success', FALSE);
					$this->session->set_flashdata('notice_error', 'Произошла ошибка. Обновление не удалено из базы данных.');
				}
			}
			else
			{
				$this->session->set_flashdata('notice_success', FALSE);
				$this->session->set_flashdata('notice_error', 'Произошла ошибка. Запрошенное обновление не найдено в базе данных.');
			}
			redirect('admin/updates');
		}
		else
		{
			show_404();
		}
		$this->_RenderPage();
	}
	
	public function installers($type=NULL, $unit=NULL)
	{
		$this->title = ' - Инсталяторы';
		
		if (is_null($type))
		{
			$page_data = array(
				'installers_data'		=> $this->aspia_model->get_installer_list()
			);
			$this->content = $this->load->view('admin/page_installers', $page_data, TRUE);
		}
		elseif ($type == 'add' AND is_null($unit))
		{
			if (count($this->input->post()) > 0)
			{
				$upload_config = array(
					'upload_path'		=> $this->config->item('storage_path', 'aspia'),
					'allowed_types'		=> 'msi',
					'max_size'			=> $this->aspia->max_size_upload(),
					'encrypt_name'		=> TRUE
				);
				
				$this->load->library('upload', $upload_config);
				
				if ($this->upload->do_upload('installer_file'))
				{
					$upload_data = $this->upload->data();
					
					$post_data = array(
						'installer_name'			=> htmlspecialchars($this->input->post('installer_name', TRUE)),
						'installer_description'		=> htmlspecialchars($this->input->post('installer_description', TRUE)),
						'installer_file_name'		=> $upload_data['orig_name'],
						'installer_file_name_real'	=> $upload_data['file_name']
					);
					
					if (!is_null($post_data['installer_name']) AND !is_null($post_data['installer_file_name']))
					{
						if (is_array($this->aspia_model->get_installer_info('name', $post_data['installer_name'])))
						{
							$this->session->set_flashdata('notice_success', FALSE);
							$this->session->set_flashdata('notice_error', 'Произошла ошибка. Инсталлятор не добавлен в базу данных. Такое имя инсталлятора уже есть в базе. Проверьте ввод и повторите попытку.');
						}
						else
						{
							$query = $this->aspia_model->add_installer($post_data);
							
							if ($query != FALSE)
							{
								$this->session->set_flashdata('notice_success', 'Инсталлятор успешно добавлен в базу данных.');
								$this->session->set_flashdata('notice_error', FALSE);
							}
							else
							{
								$this->session->set_flashdata('notice_success', FALSE);
								$this->session->set_flashdata('notice_error', 'Произошла ошибка. Инсталлятор не добавлен в базу данных.');
							}
						}
					}
					else
					{
						$this->session->set_flashdata('notice_success', FALSE);
						$this->session->set_flashdata('notice_error', 'Произошла ошибка. Инсталлятор не добавлен в базу данных. Не переданы обязательные поля.');
					}
				}
				else
				{
					$this->session->set_flashdata('notice_success', FALSE);
					$this->session->set_flashdata('notice_error', 'Произошла ошибка. Инсталлятор не добавлен в базу данных.'.$this->upload->display_errors());
				}
				redirect('admin/installers');
			}
			else
			{
				show_404();
			}
		}
		elseif ($type == 'edit' AND !is_null($unit))
		{
			if (count($this->input->post()) > 0)
			{
				$post_data = array(
					'installer_name'			=> htmlspecialchars($this->input->post('installer_name', TRUE)),
					'installer_description'		=> htmlspecialchars($this->input->post('installer_description', TRUE)),
				);
				
				if (!is_null($post_data['installer_name']))
				{
					$check = $this->aspia_model->get_installer_info('name', $post_data['installer_name']);

					if (is_array($check) AND $check['installer_id'] != $unit)
					{
						$this->session->set_flashdata('notice_success', FALSE);
						$this->session->set_flashdata('notice_error', 'Произошла ошибка. Инсталлятор не отредактирован. Такое имя инсталлятора уже есть в базе. Проверьте ввод и повторите попытку.');
					}
					else
					{
						$query = $this->aspia_model->edit_installer($unit, $post_data);
						
						if ($query != FALSE)
						{
							$this->session->set_flashdata('notice_success', 'Инсталлятор успешно отредактирован.');
							$this->session->set_flashdata('notice_error', FALSE);
						}
						else
						{
							$this->session->set_flashdata('notice_success', FALSE);
							$this->session->set_flashdata('notice_error', 'Произошла ошибка. Инсталлятор не отредактирован.');
						}
					}
				}
				else
				{
					$this->session->set_flashdata('notice_success', FALSE);
					$this->session->set_flashdata('notice_error', 'Произошла ошибка. Инсталлятор не отредактирован. Не переданы обязательные поля.');
				}
				redirect('admin/installers');
			}
			else
			{
				show_404();
			}
		}
		elseif ($type == 'del' AND !is_null($unit))
		{
			$installer_info = $this->aspia_model->get_installer_info('id', $unit);
			
			if ($installer_info != FALSE AND is_array($installer_info))
			{
				$updates_list = $this->aspia_model->get_update_per_installer($unit);
				if (is_array($updates_list) AND count($updates_list) > 0)
				{
					$this->session->set_flashdata('notice_success', FALSE);
					$this->session->set_flashdata('notice_error', 'Произошла ошибка. Запрошенный инсталлятор не может быть удален, т.к. есть обновления, ссылающиеся на этот инсталлятор. Удалите обновления и попробуйте снова.');
				}
				else
				{
					$result = $this->aspia_model->del_installer($unit);
					
					if ($result)
					{
						unlink($this->config->item('storage_path', 'aspia').$installer_info['installer_file_name_real']);
						$this->session->set_flashdata('notice_success', 'Инсталлятор успешно удален.');
						$this->session->set_flashdata('notice_error', FALSE);
					}
					else
					{
						$this->session->set_flashdata('notice_success', FALSE);
						$this->session->set_flashdata('notice_error', 'Произошла ошибка. Инсталлятор не удален из базы данных.');
					}
				}
			}
			else
			{
				$this->session->set_flashdata('notice_success', FALSE);
				$this->session->set_flashdata('notice_error', 'Произошла ошибка. Запрошенный инсталлятор не найден в базе данных.');
			}
			redirect('admin/installers');
		}
		else
		{
			show_404();
		}
		$this->_RenderPage();
	}
	
	public function packages($type=NULL, $unit=NULL)
	{
		$this->title = ' - Компоненты';
		
		if (is_null($type))
		{
			$page_data = array(
				'packages_data'		=> $this->aspia_model->get_package_list()
			);
			$this->content = $this->load->view('admin/page_packages', $page_data, TRUE);
		}
		elseif ($type == 'add' AND is_null($unit))
		{
			if (count($this->input->post()) > 0)
			{
				$post_data = array(
					'package_name'				=> htmlspecialchars($this->input->post('package_name', TRUE)),
					'package_description'		=> htmlspecialchars($this->input->post('package_description', TRUE))
				);
				
				if (!is_null($post_data['package_name']))
				{
					if (is_array($this->aspia_model->get_package_info('name', $post_data['package_name'])))
					{
						$this->session->set_flashdata('notice_success', FALSE);
						$this->session->set_flashdata('notice_error', 'Произошла ошибка. Компонент не добавлен в базу данных. Такое имя компонента уже есть в базе. Проверьте ввод и повторите попытку.');
					}
					else
					{
						$query = $this->aspia_model->add_package($post_data);
						if ($query != FALSE)
						{
							$this->session->set_flashdata('notice_success', 'Компонент успешно добавлен в базу данных.');
							$this->session->set_flashdata('notice_error', FALSE);
						}
						else
						{
							$this->session->set_flashdata('notice_success', FALSE);
							$this->session->set_flashdata('notice_error', 'Произошла ошибка. Компонент не добавлен в базу данных.');
						}
					}
				}
				else
				{
					$this->session->set_flashdata('notice_success', FALSE);
					$this->session->set_flashdata('notice_error', 'Произошла ошибка. Компонент не добавлен в базу данных. Не переданы обязательные поля.');
				}
				redirect('admin/packages');
			}
			else
			{
				show_404();
			}
		}
		elseif ($type == 'edit' AND !is_null($unit))
		{
			if (count($this->input->post()) > 0)
			{
				$post_data = array(
					'package_name'				=> htmlspecialchars($this->input->post('package_name', TRUE)),
					'package_description'		=> htmlspecialchars($this->input->post('package_description', TRUE))
				);
				
				if (!is_null($post_data['package_name']))
				{
					$check = $this->aspia_model->get_package_info('name', $post_data['package_name']);
					
					if (is_array($check) AND $check['package_id'] != $unit)
					{
						$this->session->set_flashdata('notice_success', FALSE);
						$this->session->set_flashdata('notice_error', 'Произошла ошибка. Компонент не отредактирован. Такое имя компонента уже есть в базе. Проверьте ввод и повторите попытку.');
					}
					else
					{
						$query = $this->aspia_model->edit_package($unit, $post_data);
						
						if ($query != FALSE)
						{
							$this->session->set_flashdata('notice_success', 'Компонент успешно отредактирован.');
							$this->session->set_flashdata('notice_error', FALSE);
						}
						else
						{
							$this->session->set_flashdata('notice_success', FALSE);
							$this->session->set_flashdata('notice_error', 'Произошла ошибка. Компонент не отредактирован.');
						}
					}
				}
				else
				{
					$this->session->set_flashdata('notice_success', FALSE);
					$this->session->set_flashdata('notice_error', 'Произошла ошибка. Компонент не обновлен. Не переданы обязательные поля.');
				}
				redirect('admin/packages');
			}
			else
			{
				show_404();
			}
		}
		elseif ($type == 'del' AND !is_null($unit))
		{
			$package_info = $this->aspia_model->get_package_info('id', $unit);
			
			if ($package_info != FALSE AND is_array($package_info))
			{
				$updates_list = $this->aspia_model->get_update_per_package($unit);
				if (is_array($updates_list) AND count($updates_list) > 0)
				{
					$this->session->set_flashdata('notice_success', FALSE);
					$this->session->set_flashdata('notice_error', 'Произошла ошибка. Запрошенный компонент не может быть удален, т.к. есть обновления, ссылающиеся на этот компонент. Удалите обновления и попробуйте снова.');
				}
				else
				{
					$result = $this->aspia_model->del_package($unit);
					if ($result)
					{
						$this->session->set_flashdata('notice_success', 'Компонент успешно удален.');
						$this->session->set_flashdata('notice_error', FALSE);
					}
					else
					{
						$this->session->set_flashdata('notice_success', FALSE);
						$this->session->set_flashdata('notice_error', 'Произошла ошибка. Компонент не удален из базы данных.');
					}
				}
			}
			else
			{
				$this->session->set_flashdata('notice_success', FALSE);
				$this->session->set_flashdata('notice_error', 'Произошла ошибка. Запрошенный компонент не найден в базе данных.');
			}
			redirect('admin/packages');
		}
		else
		{
			show_404();
		}
		$this->_RenderPage();
	}
	
	public function stats()
	{
		$this->title = ' - Статистика';
		
		$page = $this->uri->segment('3') ? $this->uri->segment('3') : 0;
		
		$this->load->library('pagination');
		$pagination = $this->config->item('pagination');
		$pagination['base_url']			= base_url('admin/stats');
		$pagination['total_rows']		= $this->aspia_model->get_statistics_count();
		$pagination['per_page']			= '30';
		$pagination['uri_segment']		= '3';
		$this->pagination->initialize($pagination);
		
		$page_data = array(
			'stats_data'		=> $this->aspia_model->get_statistics(array('limit' => $pagination['per_page'], 'start' => $page)),
			'updates_data'		=> $this->aspia_model->get_update_list(),
			'pagination_links'	=> $this->pagination->create_links()
		);
		
		$this->content = $this->load->view('admin/page_stats', $page_data, TRUE);
		$this->_RenderPage();
	}
}
