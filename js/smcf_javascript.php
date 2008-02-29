<?php require_once('../../../../wp-config.php'); ?>

var smcf_url = '<?php echo get_bloginfo('wpurl') . SMCF_DIR ?>';

// make sure jQuery is loaded
if (typeof jQuery !== "undefined" && typeof jQuery.modal !== "undefined") {
	jQuery(document).ready(function () {
		jQuery('.smcf_link').click(function (e) {
			e.preventDefault();
			// display the contact form
			jQuery('#smcf_content').modal({
				close: false,
				overlayId: 'contactModalOverlay',
				containerId: 'contactModalContainer',
				onOpen: contact.open,
				onShow: contact.show,
				onClose: contact.close
			});
		});
		// preload images
		var img = ['cancel.png','form_bottom.gif','form_top.gif','form_top_ie.gif','loading.gif','send.png'];
		jQuery(img).each(function () {
			var i = new Image();
			i.src = smcf_url + '/img/' + this;
		});
	});

	var contact = {
		message: null,
		open: function (dialog) {
			dialog.overlay.fadeIn(200, function () {
				dialog.container.fadeIn(200, function () {
					dialog.data.fadeIn(200, function () {
						jQuery('#contactModalContainer #name').focus();
					});
					// resize the textarea for safari
					if (jQuery.browser.safari) {
						jQuery('#contactModalContainer #name, #contactModalContainer #email, #contactModalContainer #message').css({
							'font-size': '.9em'
						});
					}
					// fix png's for IE 6
					if (jQuery.browser.msie && jQuery.browser.version < 7) {
						jQuery('#contactModalContainer .send, #contactModalContainer .cancel').each(function () {
							if (jQuery(this).css('backgroundImage').match(/^url[("']+(.*\.png)[)"']+$/i)) {
								var src = RegExp.$1;
								jQuery(this).css({
									backgroundImage: 'none',
									filter: 'progid:DXImageTransform.Microsoft.AlphaImageLoader(src="' +  src + '", sizingMethod="crop")'
								});
							}
						});
					}
				});
			});
		},
		show: function (dialog) {
			jQuery('#contactModalContainer .send').click(function (e) {
				e.preventDefault();
				// validate form
				if (contact.validate()) {
					jQuery('#contactModalContainer .message').fadeOut(function () {
						jQuery('#contactModalContainer .message').removeClass('error').empty();
					});
					jQuery('#contactModalContainer .title').html('Sending...');
					jQuery('#contactModalContainer form').fadeOut(200);
					jQuery('#contactModalContainer .content').animate({
						height: '80px'
					}, function () {
						jQuery('#contactModalContainer .loading').fadeIn(200, function () {
							jQuery.ajax({
								url: smcf_url + '/smcf_data.php',
								data: jQuery('#contactModalContainer form').serialize() + '&action=send',
								dataType: 'html',
								complete: function (xhr) {
									jQuery('#contactModalContainer .loading').fadeOut(200, function () {
										jQuery('#contactModalContainer .title').html('Thank you!');
										jQuery('#contactModalContainer .message').html(xhr.responseText).fadeIn(200);
									});
								},
								error: contact.error
							});
						});
					});
				}
				else {
					if (jQuery('#contactModalContainer .message:visible').length > 0) {
						jQuery('#contactModalContainer .message div').fadeOut(200, function () {
							jQuery('#contactModalContainer .message div').empty();
							contact.showError();
							jQuery('#contactModalContainer .message div').fadeIn(200);
						});
					}
					else {
						jQuery('#contactModalContainer .message').animate({
							height: '30px'
						}, contact.showError);
					}
					
				}
			});
		},
		close: function (dialog) {
			dialog.data.fadeOut(200, function () {
				dialog.container.fadeOut(200, function () {
					dialog.overlay.fadeOut(200, function () {
						jQuery.modal.close();
					});
				});
			});
		},
		error: function (xhr) {
			alert(xhr.statusText);
		},
		validate: function () {
			contact.message = '';
			if (!jQuery('#contactModalContainer #name').val()) {
				contact.message += 'Name is required. ';
			}

			var email = jQuery('#contactModalContainer #email').val();
			if (!email) {
				contact.message += 'Email is required. ';
			}
			else {
				if (!contact.validateEmail(email)) {
					contact.message += 'Email is invalid. ';
				}
			}

			if (!jQuery('#contactModalContainer #message').val()) {
				contact.message += 'Message is required.';
			}

			if (contact.message.length > 0) {
				return false;
			}
			else {
				return true;
			}
		},
		validateEmail: function (email) {
			var at = email.lastIndexOf("@");

			// Make sure the at (@) sybmol exists and  
			// it is not the first or last character
			if (at < 1 || (at + 1) === email.length)
				return false;

			// Make sure there aren't multiple periods together
			if (/(\.{2,})/.test(email))
				return false;

			// Break up the local and domain portions
			var local = email.substring(0, at);
			var domain = email.substring(at + 1);

			// Check lengths
			if (local.length < 1 || local.length > 64 || domain.length < 4 || domain.length > 255)
				return false;

			// Make sure local and domain don't start with or end with a period
			if (/(^\.|\.$)/.test(local) || /(^\.|\.$)/.test(domain))
				return false;

			// Check for quoted-string addresses
			// Since almost anything is allowed in a quoted-string address,
			// we're just going to let them go through
			if (!/^"(.+)"$/.test(local)) {
				// It's a dot-string address...check for valid characters
				if (!/^[-a-zA-Z0-9!#$%*\/?|^{}`~&'+=_\.]*$/.test(local))
					return false;
			}

			// Make sure domain contains only valid characters and at least one period
			if (!/^[-a-zA-Z0-9\.]*$/.test(domain) || domain.indexOf(".") === -1)
				return false;	

			return true;
		},
		showError: function () {
			jQuery('#contactModalContainer .message')
				.html(jQuery('<div>').addClass('error').append(contact.message))
				.fadeIn(200);
		}
	};
}