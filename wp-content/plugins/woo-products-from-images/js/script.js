 jQuery( document ).ready( function() {

    // Uploading files
    var file_frame;
    var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
    //var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this

    jQuery('#upload_image_button').on('click', function( event ){

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            // Set the post ID to what we want
            //file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
            // Open frame
            file_frame.open();
            return;
        } else {
            // Set the wp.media post id so the uploader grabs the ID we want when initialised
            //wp.media.model.settings.post.id = set_to_post_id;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Выберите изображение для загрузки',
            button: {
                text: 'Выбрать это изображение',
            },
            multiple: true	// Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            attachments = file_frame.state().get('selection').toJSON();

            jQuery('.image-preview-wrapper').empty();
            //console.log(attachments);
            // Do something with attachment.id and/or attachment.url here
            jQuery.each(attachments, function(index, attachment) {
                jQuery('.image-preview-wrapper')
                    .append("<img class='image-preview' src=''>");
                jQuery('.image-preview-wrapper .image-preview:last-child').data('id', attachment.id).attr('src', attachment.url);
            });
            // jQuery( '#image-preview' ).attr( 'src', attachments.url ).css( 'width', 'auto' );
            // jQuery( '#image_attachment_id' ).val( attachments.url );

            // Restore the main post ID
            wp.media.model.settings.post.id = wp_media_post_id;
        });

        // Finally, open the modal
        file_frame.open();
    });

    jQuery('form#product_generation_form').submit(function(event) {
        event.preventDefault();
        let form = jQuery(this);
        let product_ids = [];

        //form.find('input.image_attachment_id').remove();
            // form.append("<input type='hidden' name='product_ids[" + index + "]' class='image_attachment_id' value='" +
            //         jQuery(this).data('id') + "'>");
        //console.log(jQuery('input.image_attachment_id'));
        // if (jQuery('input.image_attachment_id').length === 0) {
        //     return;
        // }
        //else
        //{

        if (jQuery('.image-preview-wrapper .image-preview').length === 0)
            return;

        jQuery('.image-preview-wrapper .image-preview').each(function(index, el) {
            product_ids.push( jQuery(this).data('id') );
        });
        //}
        var formValues = form.serialize();
        //console.log(formValues);

        // let product_variations = [];
        //
        // jQuery('.product_variation_item').each(function(index, el) {
        //     let price = jQuery(this).find('input[type="text"]').val();
        //     let attribute = jQuery(this).find('select').val();
        //     product_variations.push({price: price, attribute: attribute});
        // });

        jQuery.post(
            //location.protocol+"//"+location.hostname+"/wp-content/plugins/woo-products-from-images/woo-products-from-images.php",
            my_ajax_obj.ajax_url,
            {
                _ajax_nonce: my_ajax_obj.nonce,
                fields: formValues,
                action: "generate_products",
                product_ids: product_ids
            },

            function(response)
            {
                console.log(response);
                //console.log(JSON.parse(response));
            }
        );
    });
    // Restore the main ID when the add media button is pressed
    jQuery( 'a.add_media' ).on( 'click', function() {
        wp.media.model.settings.post.id = wp_media_post_id;
    });

    //Adding variations
     jQuery('#add_variation_btn').click(function() {
         generation_add_variation();
         jQuery(".product_variation_wrapper .product_variation_item:last-child").mouseover(function() {
             jQuery(this).find(".settings_close").css('visibility', 'visible');
         });
         jQuery(".product_variation_wrapper .product_variation_item:last-child").mouseout(function() {
             jQuery(this).find(".settings_close").css('visibility', 'hidden');
         });

         jQuery('.product_variation_wrapper .product_variation_item a.settings_close').click(function(){
             generation_remove_variation(jQuery(this));
         });
     });

     jQuery(".product_variation_wrapper .product_variation_item:last-child").mouseover(function() {
         jQuery(this).find(".settings_close").css('visibility', 'visible');
     });
     jQuery(".product_variation_wrapper .product_variation_item:last-child").mouseout(function() {
         jQuery(this).find(".settings_close").css('visibility', 'hidden');
     });

     jQuery('.product_variation_wrapper .product_variation_item a.settings_close').click(function(){
         generation_remove_variation(jQuery(this));
     });

});

function generation_add_variation()
{
    let variation_wrapper = jQuery('.product_variation_wrapper');
    let variation_amount = variation_wrapper.children().length;
    let cur_index = variation_amount + 1;
    let attribute_options = jQuery('.product_variation_item:first-child > select > option');
    let attributes = [];
    jQuery.each(attribute_options, function(index, el) {
        attributes.push({slug: jQuery(el).val(), name: jQuery(el).text()});
    });
    variation_wrapper.append(
        '<div class="product_variation_item">\n' +
            '<div class="variation_header">' +
                '<h3>Вариация ' + cur_index + '</h3>\n' +
                '<a class="settings_close">×</a>' +
            '</div>' +
            '<label for="product_variation_price_' + variation_amount + '">Цена вариации ' + cur_index + '</label>\n' +
            '<input id="product_variation_price_' + variation_amount + '" required type="text" name="product_variation[' + variation_amount + '][price]">\n' +
            '<label for="product_variation_attribute_' + variation_amount + '">Атрибут вариации ' + cur_index + '</label>\n' +
            '<select id="product_variation_attribute_' + variation_amount + '" required name="product_variation[' + variation_amount + '][attribute]">\n' +
            '</select>\n' +
        '</div>');

    let new_variation_select = jQuery('.product_variation_item:last-child > select');

    jQuery.each(attributes, function(index, attribute) {
        new_variation_select.append('<option value="' + attribute.slug + '">' + attribute.name + '</option>');
    });
}
function generation_remove_variation(element)
{
    var item_el = element.parents(".product_variation_item");
    var nextSiblings = item_el.nextAll();

    if (nextSiblings.length > 0)
    {
        //при удалении вариации смещаем все индексы на 1 влево
        jQuery.each(nextSiblings, function(){
            var item = jQuery(this);
            let input = item.find('input[type="text"]');
            let select = item.find('select');

            //Меняем id у select
            let split_id = select.attr('id').split("_");
            let cur_index = split_id[split_id.length - 1];
            console.log("cur_index: " + cur_index);

            split_id[split_id.length - 1] = cur_index - 1;
            let new_id = split_id.join('_');

            select.attr('id', new_id);
            select.prev('label').attr('for', new_id)

            //Меняем name у select
            var select_name = select.attr('name');
            var arr = select_name.split('');
            var removed = arr.splice(
                select_name.indexOf('[') + 1,
                select_name.indexOf(']') - select_name.indexOf('[') - 1,
                cur_index - 1); // arr is modified
            select_name = arr.join('');

            select.attr('name', select_name);

            //Меняем id у input
            split_id = input.attr('id').split("_");
            split_id[split_id.length - 1] = cur_index - 1;
            new_id = split_id.join('_');

            input.attr('id', new_id);
            input.prev('label').attr('for', new_id);

            //Меняем name у input
            var input_name = input.attr('name');
            arr = input_name.split('');
            removed = arr.splice(
                input_name.indexOf('[') + 1,
                input_name.indexOf(']') - input_name.indexOf('[') - 1,
                cur_index - 1); // arr is modified
            input_name = arr.join('');

            input.attr('name', input_name);

        });
    }
    item_el.remove();
}