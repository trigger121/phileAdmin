<?php
/**
 * The Admin Pages Listing Interface
 */
namespace Phile\Plugin\Phile\AdminPanel;
/**
 * Class Pages
 *
 * @author  James Doyle
 * @link    https://philecms.com
 * @license http://opensource.org/licenses/MIT
 * @package Phile\Plugin\Phile\AdminPanel\Pages
 */
class Pages {
	/** @var array $config the configuration for this parser */
	private $config;
	private $pages;
	private $settings;
	private $pageRespository;

	/**
	 * the constructor
	 *
	 * @param array $config
	 */
	public function __construct($settings) {
		$this->settings = $settings;
		$this->pageRespository = new \Phile\Repository\Page();
		$this->pages  = $this->pageRespository->findAll();
		// make some nice slugs for element IDs
		foreach ($this->pages as $page) {
			$page->slug = Utilities::slugify($page->getURL());
			$page->status = ($page->getMeta()->status == 'draft') ? array('red', 'Draft'): array('green', 'Live');
		}
		// page wide data, this can be anything you need for any page
		$this->settings['nav'] = Utilities::array_to_object($this->settings['nav']);
		$this->config = \Phile\Registry::get('Phile_Settings');
	}

	public function login()
	{
		\Phile\Session::set('PhileAdmin_logged', null);
		Utilities::render('login.php', array_merge(array('title' => 'Login', 'body_class' => 'templates'), $this->settings));
	}

	public function pages()
	{
		$data = array_merge(array(
			'title' => 'Pages',
			'body_class' => 'pages',
			'pages' => $this->pages,
			), $this->settings);
		Utilities::render('pages.php', $data);
	}

	public function fourofour()
	{
		$data = array_merge(array(
			'title' => '404',
			'body_class' => 'not-found',
			), $this->settings);
		Utilities::render('404.php', $data, true, '404');
	}

	public function templates()
	{
		$templates = \Phile\Utility::getFiles(THEMES_DIR . $this->config['theme'],'\Phile\FilterIterator\GeneralFileFilterIterator' , '/^.*\.(html)$/');
		$template_obj;
		// new objects for each template
		foreach ($templates as $key => $value) {
			
			if(is_dir($value) == false){
				$template_obj[$key] = new \stdClass();
				$template_obj[$key]->name = basename($value);
				$template_obj[$key]->slug = str_replace('.html', '', basename($value));
				$template_obj[$key]->path = str_replace(ROOT_DIR, '', $value);
			}
		}

		$data = array_merge(array(
			'title' => 'Templates',
			'body_class' => 'templates',
			'templates' => $template_obj
			), $this->settings);
		Utilities::render('templates.php', $data);
	}

	public function plugins()
	{	
		$plugin_obj = array();
		$plugins = \Phile\Utility::getFiles(PLUGINS_DIR,'\Phile\FilterIterator\GeneralFileFilterIterator' , '/^.*(config.php)$/');
		$current_config = $this->config['plugins'];
		
		foreach($plugins as $plugin_path) {
			$plugin_name = str_replace(PLUGINS_DIR.'phile\\', '', $plugin_path);
			$bool = file_exists($plugin_name);			
			if($bool == false && strpos($plugin_name,'\\') == false){			
				if(substr_count($plugin_name, DIRECTORY_SEPARATOR) > 2) continue;				
				$plugin_name = str_replace(DIRECTORY_SEPARATOR . 'config.php', '', $plugin_name);				
				$plugin_name = str_replace('/', '\\', $plugin_name);				
				$pluginConfiguration = \Phile\Utility::load($plugin_path.'\\config.php');				
				$plugin_obj[$plugin_name] = new \stdClass();
				$plugin_obj[$plugin_name]->id = str_replace(DIRECTORY_SEPARATOR, "\\", $plugin_name);
				$plugin_obj[$plugin_name]->name = $plugin_name;
				$plugin_obj[$plugin_name]->active = isset($current_config[$plugin_name]['active']) ? $current_config[$plugin_name]['active'] : false;
				$plugin_obj[$plugin_name]->slug = Utilities::slugify($plugin_name);
				$plugin_obj[$plugin_name]->path = DIRECTORY_SEPARATOR . str_replace(ROOT_DIR, '', PLUGINS_DIR . $plugin_name);				
				foreach (array('author', 'namespace', 'url', 'version') as $item) {
					$new_key = strtolower($item);					
					$plugin_obj[$plugin_name]->{$new_key} = isset($pluginConfiguration['info'][$item]) ? $pluginConfiguration['info'][$item]: null;
				}				
			}			
		}		
		$data = array_merge(array(
			'title' => 'Plugins',
			'body_class' => 'plugins',
			'plugins_list' => $plugin_obj,
			'unsafe_plugins' => $this->config['plugins']['phile\adminPanel']['settings']['unsafe_plugins']
			), $this->settings);
		Utilities::render('plugins.php', $data);
	}

	public function photos()
	{
		$photos = \Phile\Utility::getFiles(CONTENT_DIR . 'uploads/images','\Phile\FilterIterator\GeneralFileFilterIterator' , '/^.*\.('. $this->settings['image_types'] .')$/');

		$image_obj = array();
		// new objects for each image
		foreach ($photos as $key => $value) {
			if(is_dir($value) == false){
				$image_obj[$key] = Utilities::photo_info($value, $this->config['base_url']);
			}
		}		
		if(count($image_obj) > 0) {
			$data = array_merge(array(
				'title' => 'Photos',
				'body_class' => 'photos',
				'photos' => $image_obj
				), $this->settings);
		} else {
			$data = array_merge(array(
				'title' => 'Photos',
				'body_class' => 'photos',
				'photos' => false
				), $this->settings);
		}
		
		Utilities::render('photos.php', $data);
	}

	public function files()
	{
		$files = \Phile\Utility::getFiles(CONTENT_DIR . 'uploads/files');

		$file_obj = array();

		// new objects for each file
		foreach ($files as $key => $value) {
			// ignore dotfiles
			if(strpos(basename($value), '.') !== (int)0) {
				if(is_dir($value) == false){
					$file_obj[$key] = Utilities::file_info($value, $this->config['base_url']);
				}
			}
		}

		if(count($file_obj) > 0) {
			$data = array_merge(array(
				'title' => 'Files',
				'body_class' => 'files',
				'files' => $file_obj
				), $this->settings);
		} else {
			$data = array_merge(array(
				'title' => 'Files',
				'body_class' => 'files',
				'files' => false
				), $this->settings);
		}

		Utilities::render('files.php', $data);
	}

	public function config() {
		$safe_config = array();
		// lets generate a config that is safe for the frontend to edit and display
		foreach ($this->config as $key => $value) {
			// skip items in the unsafe array
			if (is_array($value) || is_object($value) || in_array($key, $this->settings['unsafe_config'])) {
				continue;
			} else {
				$safe_config[$key] = $value;
			}
		}
		$data = array_merge(array(
			'title' => 'Config',
			'body_class' => 'config',
			'config' => $safe_config,
			), $this->settings);
		Utilities::render('config.php', $data);
	}

	public function edit()
	{
		$get = Utilities::filter($_GET);
		$url = $get['url'];
		$body_class = '';
		if ($get['type'] == 'page') {
			$url = str_ireplace(CONTENT_EXT, '', $url);
			// load the page we want to edit
			$page = $this->pageRespository->findByPath($url);
			// raw file contents
			$page->markdown = file_get_contents($page->getFilePath());
			$get['type'] = 'page';
			$body_class = 'pages';
		} else {
			// load the page we want to edit
			$page = new \stdClass;
			$page->content = 'This page has no content';
			$page->title = basename($url);
			// raw file contents
			$page->markdown = file_get_contents(ROOT_DIR . $url);
			$page->path = ROOT_DIR . $url;
			$body_class = 'templates';
		}
		$data = array_merge(array(
			'title' => 'Edit',
			'body_class' => $body_class,
			'current_page' => $page,
			'type' => $get['type'],
			'is_temp' => false
			), $this->settings);
		Utilities::render('editor.php', $data);
	}

	public function settings()
	{
		$safe_settings = array();
		// lets generate a config that is safe for the frontend to edit and display

		foreach ($this->settings as $key => $value) {
			// skip arrays and objects since we cant handle them as key => value
			// skip items in the unsafe array
			if (is_array($value) || is_object($value) || in_array($key, $this->settings['unsafe_settings']) ) {
				continue;
			} else {
				$safe_settings[$key] = $value;
			}
		}
		$data = array_merge(array(
			'title' => 'Settings',
			'body_class' => 'settings',
			'safe_settings' => $safe_settings,
			'required_fields' => $this->settings['required_fields']
			), $this->settings);
		Utilities::render('settings.php', $data);
	}

	public function create()
	{
		$get = Utilities::filter($_GET);
		$date = new \DateTime();
		$filename = 'temp-' . $date->getTimestamp();
		if ($get['type'] == 'page') {
			$default_content = "";
			$template = "<!--\n";
			foreach ($this->settings['required_fields'] as $field) {
				if($field['name'] == 'default_content') {
					$default_content = $field['default'];
					continue;
				}
				if ($field['name'] == 'title') {
					$title = $field['default'];
				}
				// template required meta
				$template .= ucfirst($field['name']).": ".$field['default']."\n";
			}
			$template .= "-->\n\n".$default_content;
			// timestamp the temporary file in case we have multiple pages being made at once
			// create a temp file
			Utilities::file_force_contents(CACHE_DIR . 'temp_pages' . DIRECTORY_SEPARATOR . $filename . '.md', $template);
			// load this file
			$page = $this->pageRespository->findByPath($filename, CACHE_DIR . 'temp_pages' . DIRECTORY_SEPARATOR);
			// raw contents
			$page->markdown = file_get_contents($page->getFilePath());
			// default file extension
			$page->extension = ".md";
			// $page->content = $page->getContent();
			$data = array_merge(array(
				'title' => 'Create',
				'body_class' => 'pages',
				'current_page' => $page,
				'type' => $get['type'],
				'save_path' => CONTENT_DIR . DIRECTORY_SEPARATOR,
				'is_temp' => true
				), $this->settings);
		} else {
			// try load index.html
			$default_content = '';
			if(file_exists(THEMES_DIR . $this->config['theme']. DIRECTORY_SEPARATOR . 'index.html')) {
				$default_content = file_get_contents(THEMES_DIR . $this->config['theme']. DIRECTORY_SEPARATOR . 'index.html');
			}
			// create a temp file
			Utilities::file_force_contents(CACHE_DIR . 'temp_'.$get['type'].'s' . DIRECTORY_SEPARATOR . $filename . '.html', $default_content);
			$page = new \stdClass;
			$page->content = 'This page has no content';
			$page->title = $filename. '.html';
			// raw contents
			$page->markdown = file_get_contents(CACHE_DIR . 'temp_templates' . DIRECTORY_SEPARATOR	 . $filename . '.html');
			// default file extension
			$page->extension = ".html";
			$page->path = CACHE_DIR . 'temp_templates' . DIRECTORY_SEPARATOR . $filename . '.html';
			$data = array_merge(array(
				'title' => 'Create',
				'body_class' => 'templates',
				'current_page' => $page,
				'type' => $get['type'],
				'save_path' => THEMES_DIR . $this->config['theme'] . DIRECTORY_SEPARATOR,
				'is_temp' => true
				), $this->settings);
		}
		Utilities::render('editor.php', $data);
	}

	public function download()
	{
		$file = ROOT_DIR . Utilities::filter($_GET['url']);
		
		if (file_exists($file)) {
			$pathinfo = pathinfo($file);
			
			if (!Utilities::safe_extension($pathinfo)) {
				throw new Exception("Error Processing Request", 1);
				exit;
			}
			
			// Override 404 header
			header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
			// forget the custom mimetype, lets just download
			header('Content-Type: application/x-download');
			header('Content-Transfer-Encoding: Binary');
			// we can use the pathinfo array to find the filename
			header("Content-disposition: attachment; filename=\"$pathinfo[basename]\"");
			ob_clean(); // clean the output buffer
			flush();
			echo readfile($file);
			// Don't continue to render template
			exit;
		}
	}

	/* USERS INTERFACES */

	public function users()
	{
		// get information about each user...
		$users = Users::get_all_users();
		
		$data = array_merge(array(
			'title' => 'Users',
			'body_class' => 'users',
			'safe_users' => $users,
			), $this->settings);
		Utilities::render('users.php', $data);
	}

	public function create_user()
	{
		$data = array_merge(array(
			'user' => array(
				'id' => '',
				'username' => '',
				'displayname' => '',
				'email' => ''
			),
			'user_not_found' => false,
			'user_name' => 'New User',
			'title' => 'Create User',
			'body_class' => 'users',
		), $this->settings);

		Utilities::render('users-editor.php', $data);
	}

	public function edit_user()
	{
		$get = Utilities::filter($_GET);

		$user = Users::get_user_by_hash($get['id']);

		// user not found
		if($user === false) {
			header("location: users"); exit;
		}

		$data = array_merge(array(
			'user' => array(
				'id' => $user->user_id,
				'username' => $user->username,
				'displayname' => $user->display_name,
				'email' => $user->email
			),
			'user_name' => $user->display_name,
			'title' => 'Edit User',
			'body_class' => 'users',
		), $this->settings);

		Utilities::render('users-editor.php', $data);
	}

}
