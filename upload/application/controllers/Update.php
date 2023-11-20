<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		if ($this->config->item('system_installed', 'aspia'))
		{
			$this->load->database();
			$this->load->model('aspia_model');
		}
		else
		{
			show_404();
		}
	}
	
	public function Index()
	{
		parse_str($_SERVER["QUERY_STRING"], $query);

		if (!empty($query['package']) AND is_string($query['package']) AND !empty($query['version']) AND is_string($query['version']))
		{
			$support_os = $this->config->item('support_os', 'aspia');
			$support_arch = $this->config->item('support_arch', 'aspia');
			
			// По-умолчанию выставляем ОС = "windows" для совместимости со старыми версиями
			if (empty($query['os'])) { $query['os'] = 'windows'; }
			// По-умолчанию выставляем архитектуру = "x86" для совместимости со старыми версиями
			if (empty($query['arch'])) { $query['arch'] = 'x86'; }
			
			// Проверяем доступность ОС и архитектуры в списке поддерживаемых
			if (!in_array($query['os'], $this->config->item('support_os', 'aspia')) OR !in_array($query['arch'], $this->config->item('support_arch', 'aspia')))
			{
				$this->_error('Invalid os/arch.');
			}
			else
			{
				// Записываем информацию в статистику
				$stats_data = array(
					'stats_query_ip'			=> $this->input->ip_address(),
					'stats_query_packet'		=> $query['package'],
					'stats_query_version'		=> $query['version'],
					'stats_query_os'			=> $query['os'],
					'stats_query_arch'			=> $query['arch']
				);
				
				// Проверяем, есть ли нужный пакет в базе данных
				$package_info = $this->aspia_model->get_package_info('name', $query['package']);
				
				if (is_array($package_info))
				{
					// Если пакет найден в БД, то продолжаем
					$stats_data['stats_local_packet_id'] = $package_info['package_id'];
					
					// Ищем обновление по запрошенным данным
					$update_info = $this->aspia_model->get_update_check($package_info['package_id'], $query['version'], $query['os'], $query['arch']);
					
					// Начинаем формирование "ответного" XML
					$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><update/>');
					
					// Если обновление нашлось, то добавляем данные об обновлении в XML
					if (is_array($update_info))
					{
						// Добавляем информацию в статистику о предложенном пакете обновления
						$stats_data['stats_proposed_update_id'] = $update_info['update_id'];
						
						// Формируем XML
						$xml->addChild('version', $update_info['update_target_version']);
						$xml->addChild('description', $update_info['update_description']);
						$xml->addChild('url', $this->config->item('storage_url', 'aspia').$update_info['installer_file_name_real']);
					}
					
					// Записываем статистику
					$stats_query = $this->aspia_model->add_statistics($stats_data);
					
					// Устанавливаем Content-type и выводим сформированный XML
					header('Content-Type: application/xml');
					print($xml->asXML());
				}
				else
				{
					// Если пакета нет в базе данных, то выкидываем ошибку
					$this->_error('Requested package not found!');
				}
			}
			
		}
		else
		{
			// Если в запросе не хватает данных, то выкидываем ошибку
			$this->_error('Invalid query.');
		}
	}
	
	private function _error($text)
	{
		echo $text;
	}
}
