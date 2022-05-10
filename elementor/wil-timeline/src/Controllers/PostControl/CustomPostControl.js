jQuery(document).ready(function ($) {
    let postSelectControl = elementor.modules.controls.BaseData.extend({

        onReady: function () {

            this.postNumber = this.$el.find('#wil-post-number');
            this.categories = this.$el.find('#wil-categories-ids');
            this.orderBy = this.$el.find('#wil-order-by');
            this.order = this.$el.find('#wil-order');
            this.postID = this.$el.find('#wil-post-id');

            this.initCategory();
            this.categories.on('change', () => {
                this.saveValue();
                this.initCategory();
            })
            this.postNumber.on('change', () => {
                this.saveValue();
                this.initCategory();
            })
            this.orderBy.on('change', () => {
                this.saveValue();
                this.initCategory();
            })
            this.order.on('change', () => {
                this.saveValue();
                this.initCategory();
            })

        },

        saveValue: function () {
            $.ajax({
                url: ajaxurl,
                data: {
                    action: "wiloke-element-core_custom_wil_post_save_value",
                    payload: {
                        postID: this.postID.val(),
                        postNumber: this.postNumber.val(),
                        categories: this.categories.select2().val(),
                        orderBy: this.orderBy.val(),
                        order: this.order.val()
                    }
                },
                type: "post",
                dataType: "text",
                success: (data) =>{
                    let response = JSON.parse(data);
                    this.setValue(response.data)
                }
            });
        },
        initCategory: function () {
            this.categories.select2({
                ajax: {
                    url: ajaxurl,
                    data: {
                        action: "wiloke-element-core_custom_wil_post"
                    },
                    dataType: 'json',
                    processResults: function (data) {
                        return {
                            results: data
                        }
                    }
                }
            });
        },

        onBeforeDestroy: function () {

            this.saveValue();
            $.ajax({
                url: ajaxurl,
                data: {
                    action: "wiloke-element-core_custom_wil_post_delete_value",
                    payload: {
                        postID: this.postID.val()
                    }
                },
                type: "post",
                dataType: "text",
                success: (data) =>{
                }
            });
        }

    });
    elementor.addControlView('wil-custom-post', postSelectControl);
});