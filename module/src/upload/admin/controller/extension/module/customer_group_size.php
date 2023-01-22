<?php

/*
<insertfile>_inc/summary.txt</insertfile>
*/

class ControllerExtensionModuleCustomerGroupSize extends Controller {
	private $module = 'module_customer_group_size';

	private $mstat;
	private $mconf;
	private $mtype;
	private $mname;
	private $route;
	private $model;
	private $ocver;

	private $error = array();

	public function __construct($params) {
		parent::__construct($params);

		if (strcmp(VERSION, '2.2.0.0') >= 0 && strcmp(VERSION, '3.0.0.0') < 0) {
			$this->ocver = 2;
		} elseif (strcmp(VERSION, '3.0.0.0') >= 0) {
			$this->ocver = 3;
		} else {
			exit('Unsupported OpenCart version!');
		}

		$this->mtype = 'module';
		$this->mname = explode('_', $this->module, 2)[1];

		$this->route = 'extension/' . $this->mtype . '/' . $this->mname;
		$this->model = 'model_' . str_replace('/', '_', $this->route);

		$this->mstat = $this->config->get($this->module . '_status');
		$this->mconf = $this->config->get($this->module);
	}

	public function index() {
		$this->load->language($this->route);

		$this->document->setTitle($this->language->get('heading_title'));

		if ($this->ocver == 2) {
			$token = 'token=' . $this->session->data['token'];
			$extension_route = 'extension/extension';

			// load language variables
			foreach ($this->language->all() as $key => $value) {
				$data[$key] = $value;
			}
		} elseif ($this->ocver == 3) {
			$token = 'user_token=' . $this->session->data['user_token'];
			$extension_route = 'marketplace/extension';
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting($this->module, $this->request->post);

			if (!isset($this->request->get['apply'])) {
				$redirect_to = $this->url->link($extension_route, $token . '&type=' . $this->mtype, true);
			} else {
				$redirect_to = $this->url->link($this->route, $token . '&type=' . $this->mtype, true);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($redirect_to);
		}

		if (isset($this->error['permission'])) {
			$data['error_permission'] = $this->error['permission'];
		} else {
			$data['error_permission'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $token, true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link($extension_route, $token . '&type=' . $this->mtype, true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link($this->route, $token, true),
		);

		$data['action'] = $this->url->link($this->route, $token, true);
		$data['cancel'] = $this->url->link($extension_route, $token . '&type=' . $this->mtype, true);

		if (isset($this->request->post[$this->module . '_status'])) {
			$data['status'] = $this->request->post[$this->module . '_status'];
		} else {
			$data['status'] = $this->mstat;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view($this->route, $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', $this->route)) {
			$this->error['permission'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function install() {
		$this->uninstall();

		$this->load->model('user/user_group');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', $this->route);
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', $this->route);

		if ($this->ocver == 2) {
			$event_route = 'extension/event';
			$event_model = 'model_extension_event';
		} elseif ($this->ocver == 3) {
			$event_route = 'setting/event';
			$event_model = 'model_setting_event';
		} else {
			return;
		}

		$this->load->model($event_route);

		// Add events
		$event = $this->mname . '_admin';

		$trigger = 'admin/view/customer/customer_group_list/before';
		$action = $this->route . '/beforeViewCustomerCustomerGroupList';
		$this->{$event_model}->addEvent($event, $trigger, $action);

		$trigger = 'admin/view/design/layout_form/before';
		$action = $this->route . '/beforeViewDesignLayoutForm';
		$this->{$event_model}->addEvent($event, $trigger, $action);

	}

	public function uninstall() {
		if ($this->ocver == 2) {
			$event_route = 'extension/event';
			$event_model = 'model_extension_event';
			$delete_method = 'deleteEvent';
		} elseif ($this->ocver == 3) {
			$event_route = 'setting/event';
			$event_model = 'model_setting_event';
			$delete_method = 'deleteEventByCode';
		} else {
			return;
		}

		$this->load->model($event_route);

		$events = array(
			$this->mname . '_admin',
		);

		// Delete events
		foreach ($events as $event) {
			$this->{$event_model}->{$delete_method}($event);
		}

		$this->load->model('user/user_group');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', $this->route);
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', $this->route);
	}

	// admin/view/customer/customer_group_list/before
	public function beforeViewCustomerCustomerGroupList(&$route, &$data) {
		if ($this->config->get($this->module . '_status')) {
			$data['customer_group_size']  = true;

			$this->load->model($this->route);

			$this->load->language($this->route);
			$data['column_size'] = $this->language->get('column_size');

			foreach ($data['customer_groups'] as &$customer_group) {
				$customer_group_id = $customer_group['customer_group_id'];

				$customer_group['size'] = $this->{$this->model}->getCustomerGroupSize($customer_group_id);
			}
		}
	}

	// https://forum.opencart.com/viewtopic.php?p=799279#p799279
	// admin/view/design/layout_form/before
	public function beforeViewDesignLayoutForm(&$route, &$data) {
		foreach ($data['extensions'] as $key => $extension) {
			if ($extension['code'] == $this->mname) {
				unset($data['extensions'][$key]);
			}
		}

		return null;
	}
}
