<?php

namespace WilokeTeamMember\Controllers;

use WilokeTeamMember\Controllers\Controls\CustomPostControl;
use WilokeTeamMember\Share\App;

class RegistrationController
{
	public function __construct()
	{
		$aConfigs = json_decode(file_get_contents(plugin_dir_path(__FILE__) . '../Configs/config.json'), true);
		App::bind('dataConfig', $aConfigs);
		add_action('elementor/widgets/register', [$this, 'registerAddon'], 100);
		add_action('elementor/controls/register', [$this, 'registerControls']);
		add_action('wp_enqueue_scripts', [$this, 'registerScripts']);

	}

	public function registerScripts()
	{
		$aHandleCss=[];
		$aHandleJs=[];
		wp_register_style(
			WILOKE_WILOKETEAMMEMBER_NAMESPACE . md5(App::get('dataConfig')['css']),
			App::get('dataConfig')['css'],
			[],
			WILOKE_WILOKETEAMMEMBER_VERSION);
		$aHandleCss[]=WILOKE_WILOKETEAMMEMBER_NAMESPACE . md5(App::get('dataConfig')['css']);


		wp_register_script(
			WILOKE_WILOKETEAMMEMBER_NAMESPACE . md5(App::get('dataConfig')['js']),
			App::get('dataConfig')['js'],
			['elementor-frontend'],
			WILOKE_WILOKETEAMMEMBER_VERSION,
			true
		);
		$aHandleJs[]=WILOKE_WILOKETEAMMEMBER_NAMESPACE . md5(App::get('dataConfig')['js']);
		if (isset(App::get('dataConfig')['libs']['css']) && !empty($aLibCss = App::get('dataConfig')['libs']['css'])) {
			foreach ($aLibCss as $urlCss) {
				wp_register_style(
					WILOKE_WILOKETEAMMEMBER_NAMESPACE . md5($urlCss),
					$urlCss,
					[], WILOKE_WILOKETEAMMEMBER_VERSION);
				$aHandleCss[]=WILOKE_WILOKETEAMMEMBER_NAMESPACE . md5($urlCss);
			}
		}
		App::bind('handleCss',$aHandleCss);

		if (isset(App::get('dataConfig')['libs']['js']) && !empty($aLibJs = App::get('dataConfig')['libs']['js'])) {
			foreach ($aLibJs as $urlJs) {
				wp_register_script(
					WILOKE_WILOKETEAMMEMBER_NAMESPACE . md5($urlJs),
					$urlJs,
					[],
					WILOKE_WILOKETEAMMEMBER_VERSION,
					true
				);
			}
			$aHandleJs[]=WILOKE_WILOKETEAMMEMBER_NAMESPACE . md5($urlJs);
		}
		App::bind('handleJs',$aHandleJs);
	}

	public function registerAddon($oWidgetManager)
	{
		$oWidgetManager->register(new PluginAddon());
	}

	public function registerControls($oControlManager)
	{
		$oControlManager->register(new CustomPostControl());
	}
}