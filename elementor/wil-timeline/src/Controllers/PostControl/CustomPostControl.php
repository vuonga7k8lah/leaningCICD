<?php

namespace WilokeTimeline\Controllers\PostControl;

use Elementor\Base_Control;
use WilokeTimeline\Share\TraitHandleCustomPost;

class CustomPostControl extends Base_Control
{
	use TraitHandleCustomPost;

	protected array $aOrderBy
		= [
			'ID'            => 'ID',
			'author'        => 'Author',
			'title'         => 'Title',
			'date'          => 'Date',
			'menu_order'    => 'Menu Order',
			'rand'          => 'Random',
			'comment_count' => 'Comment Count',
		];
	protected array $aOrder
		= [
			'desc' => 'DESC',
			'asc'  => 'ASC'
		];

	public function get_type()
	{
		return 'wil-custom-post';
	}

	public function enqueue()
	{
		wp_register_script(WILOKE_WILOKETIMELINE_NAMESPACE . '_custom_post_script',
			WILOKE_WILOKETIMELINE_VIEWS_URL . 'src/Controllers/PostControl/CustomPostControl.js',
			['jquery'],
			WILOKE_WILOKETIMELINE_VERSION,
			true);
		wp_enqueue_script(WILOKE_WILOKETIMELINE_NAMESPACE . '_custom_post_script');
	}

	public function content_template()
	{
		$postID = (int)$_GET['post'];
		$aData = $this->getFieldsData($postID);
		$orderBy = $aData['orderBy'] ?? 'ID';
		$order = $aData['order'] ?? 'desc';
		$postNumber = $aData['postNumber'] ?? 20;
		$aCategories = [];
		if (isset($aData['categories']) && !empty($aData['categories'])) {
			foreach ($aData['categories'] as $categoryID) {
				$aCategories[$categoryID] = get_cat_name($categoryID);
			}
		}
		?>
        <div class="elementor-control elementor-control-type-divider elementor-label-inline elementor-control-separator-none">
            <div class="elementor-control-content">
            </div>
        </div>
        <label for="elementor-control-default-c1218" class="elementor-control-title">Wil Post</label>
        <br>
        <div class="elementor-control-field">
            <input id="wil-post-id" type="hidden" name="wilPostId" value="<?= $_GET['post'] ?? 0 ?>">
            <label class="elementor-control-title">Categories</label>

            <div class="elementor-control-input-wrapper elementor-control-unit-5">
                <select multiple class="post-select" name="wil-categories" id="wil-categories-ids"
                        style="width:100%">
					<?php
					if (!empty($aCategories)) {
						foreach ($aCategories as $key => $name):
							?>
                            <option value="<?= $key ?>" selected="selected"><?= $name ?></option>
						<?php
						endforeach;
					}
					?>
                </select>
            </div>
        </div>
        <br>
        <div class="elementor-control-field">

            <label for="elementor-control-default-c1218" class="elementor-control-title">Number Post</label>

            <div class="elementor-control-input-wrapper elementor-control-unit-5">
                <input id="wil-post-number"
                       type="number"
                       name="postNumber"
                       value="<?= $postNumber ?>">
            </div>
        </div>
        <br>
        <div class="elementor-control-field">

            <label class="elementor-control-title">Order By</label>

            <div class="elementor-control-input-wrapper elementor-control-unit-5">
                <select id="wil-order-by" name="wil-order-by" style="width:100%">
					<?php foreach ($this->aOrderBy as $key => $value): ?>
                        <option <?= $key == $orderBy ? 'selected' : '' ?> value="<?= $key ?>"><?= $value ?></option>
					<?php endforeach; ?>
                </select>
            </div>
        </div>
        <br>
        <div class="elementor-control-field">

            <label class="elementor-control-title">Order</label>

            <div class="elementor-control-input-wrapper elementor-control-unit-5">
                <select id="wil-order" name="wil-order" style="width:100%">
					<?php foreach ($this->aOrder as $key => $value): ?>
                        <option <?= $key == $order ? 'selected' : '' ?> value="<?= $key ?>"><?= $value ?></option>
					<?php endforeach; ?>
                </select>
            </div>
        </div>
        <br>
        <div class="elementor-control elementor-control-type-divider elementor-label-inline elementor-control-separator-none">
            <div class="elementor-control-content">
            </div>
        </div>
		<?php

	}
}