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
            //console.log(attachments);
            // Do something with attachment.id and/or attachment.url here
            jQuery.each(attachments, function(index, attachment) {
                jQuery('.image-preview-wrapper')
                    .append("<img class='image-preview' src='' style='max-height: 100px; max-width: 150px;'>");
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

    // Restore the main ID when the add media button is pressed
    jQuery( 'a.add_media' ).on( 'click', function() {
        wp.media.model.settings.post.id = wp_media_post_id;
    });
});