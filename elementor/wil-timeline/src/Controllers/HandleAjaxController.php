<?php

namespace WilokeTimeline\Controllers;

use WilokeTimeline\Share\TraitHandleCustomPost;
use WilokeTimeline\Share\TraitHandleQuery;

class HandleAjaxController
{
	use TraitHandleCustomPost, TraitHandleQuery;

	public function __construct()
	{
		add_action('wp_ajax_' . WILOKE_WILOKETIMELINE_NAMESPACE . '_custom_wil_post', [$this, 'ajaxCustomWilPost']);
		add_action('wp_ajax_' . WILOKE_WILOKETIMELINE_NAMESPACE . '_custom_wil_post_save_value',
			[$this, 'ajaxCustomWilPostSaveValue']);
		add_action('wp_ajax_' . WILOKE_WILOKETIMELINE_NAMESPACE . '_custom_wil_post_delete_value',
			[$this, 'ajaxCustomWilPostDeleteValue']);
	}

	public function ajaxCustomWilPostDeleteValue()
	{
		$postID = (int)$_POST['payload']['postID'];
		$this->deleteFieldsData($postID);
	}

	public function ajaxCustomWilPost()
	{
		$aCategories = get_categories([
			'type'       => 'post',
			'number'     => 10,
			'hide_empty' => 0,
		]);

		foreach ($aCategories as $oCategory) {
			$response[] = [
				"id"   => $oCategory->term_id,
				"text" => $oCategory->name,
			];
		}

		wp_send_json($response);
	}

	public function ajaxCustomWilPostSaveValue()
	{
		$aArgs = $_POST['payload'];
		if (isset($aArgs['postID'])) {
			$postID = (int)$aArgs['postID'];
			unset($aArgs['postID']);
			$this->updateFieldsData($postID, $aArgs);
		}
		$aResponse = $this->commonParseArgs($_POST['payload'])->query();
		$aResponse['wil-post'] = true;
		wp_send_json([
			'data' => $aResponse
		]);
	}

}