<?php

class MediaLabOembed {

	/**
	 * @var MediaLabConfig
	 */
	private $config;

	/**
	 * MediaLabOembed constructor.
	 * @param MediaLabConfig $config
	 */
	public function __construct($config) {
		$this->config = $config;

		register_activation_hook($this->config->file, array($this, 'activate'));
		register_deactivation_hook($this->config->file, array($this, 'deactivate'));

		add_action('init', array($this, 'init'));

		if(is_admin()) {
			add_action( 'admin_menu', array($this, 'addSettingsPage'));
			add_action( 'admin_init', array($this, 'initAdmin'));

			$plugin_file = plugin_basename($this->config->file);
			add_filter( "plugin_action_links_{$plugin_file}", array($this, 'addSettingsLink'));
		}
	}

	/**
	 * Activate Plugin
	 */
	public function activate() {
		$option_value = get_option($this->config->option_name);

		if(empty($option_value)) {
			add_option($this->config->activate_option_name, true);
		}
	}

	/**
	 * De-activate Plugin
	 */
	public function deactivate() {

		$option_value = get_option($this->config->option_name);

		if(!empty($option_value)) {
			wp_oembed_remove_provider('https://' . $option_value . '/share/*');
		}

		delete_option($this->config->option_name);
	}

	/**
	 * Initialize Plugin
	 */
	public function init() {

		if(!empty(get_option($this->config->activate_option_name))) {
			delete_option($this->config->activate_option_name);

			wp_redirect(
				admin_url('options-general.php?page='. $this->config->page_slug)
			);

			exit();
		}

		$option_value = get_option($this->config->option_name);

		if(!empty($option_value)) {
			wp_oembed_add_provider(
				'https://' . $option_value . '/share/*',
				'https://' . $option_value . '/api/oembed/'
			);
		}
	}

	/**
	 * Initialize WP admin part
	 */
	public function initAdmin() {

		$args = array(
			'type' => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default' => null
		);

		register_setting(
			$this->config->option_group,
			$this->config->option_name,
			$args
		);

		add_settings_section(
			$this->config->section_name,
			__('MediaLab oEmbed plugin settings', $this->config->getPluginName()),
			function() {
				$help_text = '<h4>';
				$help_text .= __('Please fill in your MediaLab URL (ex: mylab.medialab.co)', $this->config->getPluginName());
				$help_text .= '</h4>';

				echo $help_text;
			},
			$this->config->page_slug
		);

		$this->addSettingsField();
	}

	/**
	 * Create and add settings field
	 */
	private function addSettingsField() {

		add_settings_field(
			$this->config->option_name,
			__('Your MediaLab URL', $this->config->getPluginName()) . ':',
			function() {
				$field = 'https://'
					. '<input type="text" class="regular-text" '
					. 'name="' .$this->config->option_name .'" '
					. 'id="' .$this->config->option_name .'" '
					. 'value="' . get_option($this->config->option_name) . '" '
				. '/>';

				echo $field;
			},
			$this->config->page_slug,
			$this->config->section_name
		);
	}

	/**
	 * Add Settings page
	 */
	public function addSettingsPage() {

		add_options_page(
			__('MediaLab oEmbed options', $this->config->getPluginName()),
			__('MediaLab oEmbed', $this->config->getPluginName()),
			'manage_options',
			$this->config->page_slug,
			array($this, 'createSettingsPage')
		);
	}

	/**
	 * Create HTML of the settings page
	 */
	public function createSettingsPage() {

		if (!current_user_can( 'manage_options' ))  {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}

		echo '<div class="wrap">';
			echo '<h1>';
				echo __('MediaLab oEmbed options', $this->config->getPluginName());
			echo '</h1>';
			echo '<form method="post" action="options.php">';
				settings_fields($this->config->option_group);
				do_settings_sections($this->config->page_slug);
				submit_button();
			echo '</form>';
		echo '</div>';
	}

	/**
	 * Add link 'Settings' to the Plugin row when the plugin is activated
	 * @param array $links
	 * @return array
	 */
	public function addSettingsLink($links) {
		$settings_link = '<a href="'
			. admin_url('options-general.php?page='. $this->config->page_slug)
		. '">';
			$settings_link .= __('Settings');
		$settings_link .= '</a>';

		array_push( $links, $settings_link );
		return $links;
	}
}