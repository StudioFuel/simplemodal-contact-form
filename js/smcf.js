// make sure jQuery and SimpleModal are loaded
if (typeof jQuery !== "undefined" && typeof jQuery.modal !== "undefined") {
	jQuery(document).ready(function () {
		jQuery('.smcf_link, .smcf-link').click(function (e) { // added .smcf_link for previous version
			e.preventDefault();
			// display the contact form
			jQuery('#smcf-content').modal({
				close: false,
				position: ["15%",],
				overlayId: 'smcf-overlay',
				containerId: 'smcf-container',
				onOpen: contact.open,
				onShow: contact.show,
				onClose: contact.close
			});
		});

		// preload images
		var img = ['cancel.png','form_bottom.gif','form_top.gif','loading.gif','send.png'];
		if (jQuery('#smcf-content form').length > 0) {
			var url = jQuery('#smcf-content form').attr('action').replace(/smcf_data\.php/, 'img/');
			jQuery(img).each(function () {
				var i = new Image();
				i.src = url + this;
			});
		}
	});

	var contact = {
		message: null,
		open: function (dialog) {
			// dynamically determine height
			var h = 280;
			if (jQuery('#smcf-subject').length) {
				h += 26;
			}
			if (jQuery('#smcf-cc').length) {
				h += 22;
			}

			// resize the textarea for safari
			if (jQuery.browser.safari) {
				jQuery('#smcf-container .smcf-input').css({
					'font-size': '.9em'
				});
			}

			// add padding to the buttons in firefox/mozilla
			if (jQuery.browser.mozilla) {
				jQuery('#smcf-container .smcf-button').css({
					'padding-bottom': '2px'
				});
			}

			var title = jQuery('#smcf-container .smcf-title').html();
			jQuery('#smcf-container .smcf-title').html(smcf_messages.loading);
			dialog.overlay.fadeIn(200, function () {
				dialog.container.fadeIn(200, function () {
					dialog.data.fadeIn(200, function () {
						jQuery('#smcf-container .smcf-content').animate({
							height: h
						}, function () {
							jQuery('#smcf-container .smcf-title').html(title);
							jQuery('#smcf-container form').fadeIn(200, function () {
								jQuery('#smcf-container #smcf-name').focus();

								jQuery('#smcf-container .smcf-cc').click(function () {
									var cc = jQuery('#smcf-container #smcf-cc');
									cc.is(':checked') ? cc.attr('checked', '') : cc.attr('checked', 'checked');
								});

								// fix png's for IE 6
								if (jQuery.browser.msie && jQuery.browser.version < 7) {
									jQuery('#smcf-container .smcf-button').each(function () {
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
					});
				});
			});
		},
		show: function (dialog) {
			jQuery('#smcf-container .smcf-send').click(function (e) {
				e.preventDefault();
				// validate form
				if (contact.validate()) {
					jQuery('#smcf-container .smcf-message').fadeOut(function () {
						jQuery('#smcf-container .smcf-message').removeClass('smcf-error').empty();
					});
					jQuery('#smcf-container .smcf-title').html(smcf_messages.sending);
					jQuery('#smcf-container form').fadeOut(200);
					jQuery('#smcf-container .smcf-content').animate({
						height: '90px'
					}, function () {
						jQuery('#smcf-container .smcf-loading').fadeIn(200, function () {
							jQuery.ajax({
								url: jQuery('#smcf-content form').attr('action'),
								data: jQuery('#smcf-container form').serialize() + '&action=send',
								type: 'post',
								cache: false,
								dataType: 'html',
								success: function (data) {
									jQuery('#smcf-container .smcf-loading').fadeOut(200, function () {
										jQuery('#smcf-container .smcf-title').html(smcf_messages.thankyou);
										jQuery('#smcf-container .smcf-message').html(data).fadeIn(200);
									});
								},
								error: function (xhr) {
									jQuery('#smcf-container .smcf-loading').fadeOut(200, function () {
										jQuery('#smcf-container .smcf-title').html(smcf_messages.error);
										jQuery('#smcf-container .smcf-message').html(xhr.status + ': ' + xhr.statusText).fadeIn(200);
									});
								}
							});
						});
					});
				}
				else {
					if (jQuery('#smcf-container .smcf-message:visible').length > 0) {
					var msg = jQuery('#smcf-container .smcf-message div');
						msg.fadeOut(200, function () {
							msg.empty();
							contact.showError();
							msg.fadeIn(200);
						});
					}
					else {
						jQuery('#smcf-container .smcf-message').animate({
							height: '30px'
						}, contact.showError);
					}
				}
			});
		},
		close: function (dialog) {
			jQuery('#smcf-container .smcf-message').fadeOut();
			jQuery('#smcf-container .smcf-title').html(smcf_messages.goodbye);
			jQuery('#smcf-container form').fadeOut(200);
			jQuery('#smcf-container .smcf-content').animate({
				height: '40px'
			}, function () {
				dialog.data.fadeOut(200, function () {
					dialog.container.fadeOut(200, function () {
						dialog.overlay.fadeOut(200, function () {
							jQuery.modal.close();
						});
					});
				});
			});
		},
		validate: function () {
			contact.message = '';
			var req = [],
				invalid = "";

			if (!jQuery('#smcf-container #smcf-name').val()) {
				req.push(smcf_messages.name);
			}

			var email = jQuery('#smcf-container #smcf-email').val();
			if (!email) {
				req.push(smcf_messages.email);
			}
			else {
				if (!contact.validateEmail(email)) {
					invalid = smcf_messages.emailinvalid;
				}
			}

			var subj = jQuery('#smcf-container #smcf-subject');
			if (subj.length > 0 && !subj.val()) {
				req.push(smcf_messages.subject);
			}

			if (!jQuery('#smcf-container #smcf-message').val()) {
				req.push(smcf_messages.message);
			}

			if (req.length > 0) {
				var fields = req.join(', ');
				contact.message += req.length > 1 ?
					fields.replace(/(.*),/,'$1 ' + smcf_messages.and) + ' ' + smcf_messages.are :
					fields + ' ' + smcf_messages.is;
				contact.message += ' ' + smcf_messages.required;
			}

			if (invalid.length > 0) {
				contact.message += (req.length > 0 ? ' ' : '') + smcf_messages.emailinvalid;
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
			jQuery('#smcf-container .smcf-message')
				.html(jQuery('<div/>').addClass('smcf-error').append(contact.message))
				.fadeIn(200);
		}
	};
}