<?php

namespace WilokeFeaturedBlock\Controllers;


use WilokeFeaturedBlock\Controllers\PostControl\CustomPostControl;
use WilokeFeaturedBlock\Controllers\SliderControl\CustomSliderControl;
use WilokeFeaturedBlock\Share\App;
use function foo\func;

class RegistrationController
{
	public function __construct()
	{
		add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );
		$aConfigs = json_decode(file_get_contents(plugin_dir_path(__FILE__) . '../Configs/config.json'), true);
		App::bind('dataConfig', $aConfigs);
		add_action('elementor/widgets/register', [$this, 'registerAddon']);
		add_action('elementor/controls/register', [$this, 'registerControls']);
		add_action('wp_enqueue_scripts', [$this, 'registerScripts']);
//		// Register Category
//		add_action( 'elementor/elements/categories_registered', [ $this, 'add_elementor_widget_categories' ] );
	}
//	function add_elementor_widget_categories( $elements_manager ) {
//
//		$elements_manager->add_category(
//			'wil-ok-category',
//			[
//				'title' =>'wiloke',
//				'icon' => 'eicon-person',
//			]
//		);
//	}
	public function registerScripts()
	{
		$aHandleCss=[];
		$aHandleJs=[];
		wp_register_style(
			WILOKE_WILOKEFEATUREDBLOCK_NAMESPACE . md5(App::get('dataConfig')['css']),
			App::get('dataConfig')['css'],
			[],
			WILOKE_WILOKEFEATUREDBLOCK_VERSION);
		$aHandleCss[]=WILOKE_WILOKEFEATUREDBLOCK_NAMESPACE . md5(App::get('dataConfig')['css']);


		wp_register_script(
			WILOKE_WILOKEFEATUREDBLOCK_NAMESPACE . md5(App::get('dataConfig')['js']),
			App::get('dataConfig')['js'],
			['elementor-frontend'],
			WILOKE_WILOKEFEATUREDBLOCK_VERSION,
			true
		);
		$aHandleJs[]=WILOKE_WILOKEFEATUREDBLOCK_NAMESPACE . md5(App::get('dataConfig')['js']);
		if (isset(App::get('dataConfig')['libs']['css']) && !empty($aLibCss = App::get('dataConfig')['libs']['css'])) {
			foreach ($aLibCss as $urlCss) {
				wp_register_style(
					WILOKE_WILOKEFEATUREDBLOCK_NAMESPACE . md5($urlCss),
					$urlCss,
					[], WILOKE_WILOKEFEATUREDBLOCK_VERSION);
				$aHandleCss[]=WILOKE_WILOKEFEATUREDBLOCK_NAMESPACE . md5($urlCss);
			}
		}
		App::bind('handleCss',$aHandleCss);

		if (isset(App::get('dataConfig')['libs']['js']) && !empty($aLibJs = App::get('dataConfig')['libs']['js'])) {
			foreach ($aLibJs as $urlJs) {
				wp_register_script(
					WILOKE_WILOKEFEATUREDBLOCK_NAMESPACE . md5($urlJs),
					$urlJs,
					[],
					WILOKE_WILOKEFEATUREDBLOCK_VERSION,
					true
				);
			}
			$aHandleJs[]=WILOKE_WILOKEFEATUREDBLOCK_NAMESPACE . md5($urlJs);
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
		$oControlManager->register(new CustomSliderControl());
	}
}