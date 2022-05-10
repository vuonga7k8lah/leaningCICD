<?php

namespace WilokeTimeline\Share;

use WP_Query;

trait TraitHandleQuery
{
	public array $aArgs = [];

	public function commonParseArgs(array $aRawArgs)
	{
		if (isset($aRawArgs['postNumber'])) {
			$this->aArgs['limit'] = $aRawArgs['postNumber'];
		}
		if (isset($aRawArgs['orderBy'])) {
			$this->aArgs['orderBy'] = $aRawArgs['postNumber'];
		}
		if (isset($aRawArgs['order'])) {
			$this->aArgs['order'] = $aRawArgs['order'];
		}
		if (isset($aRawArgs['categories'])) {
			$this->aArgs['category__in'] =  $aRawArgs['categories'];
		}
		$this->aArgs = wp_parse_args($this->aArgs, $this->defineArgs());
		return $this;
	}

	private function defineArgs(): array
	{
		return [
			'limit'   => 20,
			'paged'   => 1,
			'orderby' => 'ID',
			'order'   => 'DESC',
			'status'  => 'publish',
		];
	}

	public function query()
	{
		$aItems = [];
		$oQuery = new WP_Query($this->aArgs);
		if ($oQuery->have_posts()) {
			while ($oQuery->have_posts()) {
				$oQuery->the_post();
				$postID = $oQuery->post->ID;
				$aItems[] = [
					'id'           => (string)$postID,
					'content'      => get_the_content($postID),
					'title'        => get_the_title($postID),
					'slug'         => $oQuery->post->post_name,
					'excerpt'      => $oQuery->post->post_excerpt,
					'createDate'   => $oQuery->post->post_date,
					'modifiedDate' => $oQuery->post->post_modified,
					'link'         => $oQuery->post->guid,
					'status'       => get_post_status($postID),
					'urlImage'     => get_the_post_thumbnail_url($postID)?:''
				];
			}
		}
		wp_reset_postdata();
		return $aItems;
	}

}