/**
 * Media Upload
 *
 */
jQuery(document).ready(function($){

	// variables
	var fileFrame;

	$(document).on('click', '.tcu-image-button-upload', function(e) {

		e.preventDefault();

		var inputId = $(this).parent('.tcu-image-widget').find('.tcu-image-button-url').attr('id');
		var imgContainer = $(this).parent('.tcu-image-widget');
		var button = $(this);
		var heightId = $(this).parents('form').find('.tcu-image-height').attr('id');
		var widthId = $(this).parents('form').find('.tcu-image-width').attr('id');

		// crate a new media fileFrame
		fileFrame = wp.media({
			title: 'Select Image',
			button: {
				text: 'Insert into Widget'
			},
			multiple: false // Set to true to allow multiple files to be selected
		});

		fileFrame.on( 'select', function() {

			var attachment = fileFrame.state().get('selection').first().toJSON();

			// Send the attachment URL to our custom image input field
			imgContainer.prepend('<img src="'+ attachment.url +'" class="tcu-custom-image" /><br><a class="tcu-image-button-clear-image" href="#">Remove Image</a>');

	 		$('#' + inputId).val( attachment.url );

	 		// hide upload button
	 		button.css('display', 'none');

	 		// populate image size and add into our form
			$( '#' + widthId ).val( attachment.width );
			$( '#' + heightId ).val( attachment.height );

		});

		// open the modal on click
		fileFrame.open();

	});

	// empty values for image_url, width, and height
	$(document).on("click", ".tcu-image-button-clear-image", function(e) {

		e.preventDefault();

		var inputId = $(this).parent('.tcu-image-widget').find('.tcu-image-button-url').attr('id');
		var imgContainer = $(this).parent('.tcu-image-widget');
		var image = $(this).parent('.tcu-image-widget').find('.tcu-custom-image');
		var removeLink = $(this);
		var heightId = $(this).parents('form').find('.tcu-image-height').attr('id');
		var widthId = $(this).parents('form').find('.tcu-image-width').attr('id');

		image.remove();
		imgContainer.prepend('<a href="#" class="tcu-image-button-upload button-primary">Upload Image</a>');
		removeLink.css('display', 'none');
		$( '#' + inputId ).val('');
		$( '#' + heightId ).val('');
		$( '#' + widthId).val('');

	});

});