"use strict";
(function () {
	jQuery('#be-agent-form').on('submit', function (e) {
		e.preventDefault();
		var _this             = jQuery(this),
			requiredFields    = _this.find('[data-required]'),
			emailField        = _this.find('#email'),
			emailConfirmField = _this.find('#confirm_email'),
			successMessage    = jQuery('#success-agent-registration');

		_this.addClass('loading');

		if (emailField.val() !== emailConfirmField.val()) {
			emailConfirmField.parents('.field-row').addClass('mis-matched-error');
			jQuery('html, body').stop().animate({scrollTop: emailConfirmField.parents('.field-row').offset().top - 100}, 2000);
			_this.removeClass('loading');
			return false;
		} else {
			emailConfirmField.parents('.field-row').removeClass('mis-matched-error');
		}

		requiredFields.each(function () {
			var $this = jQuery(this);
			if ($this.val() == '') {
				$this.parents('.field-row').addClass('has-alert');
				jQuery('html, body').stop().animate({scrollTop: $this.parents('.field-row').offset().top - 100}, 2000);
				_this.removeClass('loading');
				return false;
			} else {
				$this.parents('.field-row').removeClass('has-alert');
				_this.removeClass('loading');
			}
		});

		var o = {},
			a = _this.serializeArray();
		jQuery.each(a, function () {
			if (o[this.name] !== undefined) {
				if (!o[this.name].push) {
					o[this.name] = [o[this.name]];
				}
				o[this.name].push(this.value || '');
			} else {
				o[this.name] = this.value || '';
			}
		});

		var data           = {
				action:     "realty_house_register_agent",
				dataFields: JSON.stringify(o)
			},
			formMessageBox = jQuery('#form-message-box');

		jQuery.ajax({
			type:     "POST",
			url:      realty_house_be_agent.ajaxurl,
			data:     data,
			dataType: "json",
			success:  function (data) {
				console.log(data);
				if (data.status == false) {
					if (data.errorNo == 1) {
						requiredFields.each(function () {
							var $this = jQuery(this);
							if ($this.val() == '') {
								$this.parents('.field-row').addClass('has-alert');
							} else {
								$this.parents('.field-row').removeClass('has-alert');
							}
						});
						formMessageBox.addClass('alert alert-danger active').html(data.text);
					}
					if (data.errorNo == 2) {
						formMessageBox.addClass('alert alert-danger active').html(data.text);
					}
				}
				else {
					successMessage.addClass('active');
					_this.text('');
					jQuery('html, body').stop().animate({scrollTop: successMessage.offset().top - 100}, 2000);
				}
			}
		});
		_this.removeClass('loading');
	})
})();