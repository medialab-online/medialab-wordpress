<?php

class MediaLabConfig {

	/**
	 * @var string
	 */
	public $file;

	/**
	 * @var string
	 */
	public $page_slug;

	/**
	 * @var string
	 */
	public $option_group;

	/**
	 * @var string
	 */
	public $option_name;

	/**
	 * @var string
	 */
	public $section_name;

	/**
	 * @var string
	 */
	public $activate_option_name;

	/**
	 * @var string
	 */
	private $plugin_name = 'medialab-oembed';

	/**
	 * MediaLabConfig constructor.
	 * @param string $file
	 */
	public function __construct($file) {
		$this->file = $file;

		$this->loadConfigs();
	}

	/**
	 * @return string
	 */
	public function getPluginName() {
		return $this->plugin_name;
	}

	/**
	 * Load configs
	 */
	private function loadConfigs() {
		$this->page_slug = $this->plugin_name . '_options';
		$this->option_group = $this->plugin_name . '_group';
		$this->option_name = $this->plugin_name . '_url';
		$this->section_name = $this->plugin_name . '_settings';
		$this->activate_option_name = $this->plugin_name . '_needs_setting';
	}

}