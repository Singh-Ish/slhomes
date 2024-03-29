"use strict";

function setCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
	var expires     = "expires=" + d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
	var name = cname + "=";
	var ca   = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}
function updateCompareMenu() {
	var compareMenu = jQuery('.property-compare-menu-item');

	if (compareMenu.length > 0) {
		var cookieVal = getCookie('pCompare');

		if (cookieVal == '' || cookieVal == null) {
			compareMenu.attr('data-properties', 0);
			compareMenu.removeClass('active');
		} else {
			var cookieArray   = cookieVal.split(','),
				propertyItems = cookieArray.length;

			compareMenu.attr('data-properties', propertyItems);
			compareMenu.addClass('active');
		}
	}
}

jQuery(document).ready(function () {

	updateCompareMenu();

	jQuery('.compare-btn').on('click', function (e) {
		e.preventDefault();

		var _this     = jQuery(this),
			pID       = _this.data('p-id'),
			cookieVal = getCookie('pCompare');

		if (cookieVal == '' || cookieVal == null) {
			setCookie('pCompare', pID);
			_this.addClass('active');
		}
		else {
			if (cookieVal.indexOf(pID) >= 0) {


				if (cookieVal.indexOf(',') >= 0) {
					var currentPID = (cookieVal.indexOf(pID) == 0 ? pID + ',' : ',' + pID),
						newVal     = cookieVal.replace(currentPID, '');

					setCookie('pCompare', newVal);
					_this.removeClass('active');
				}
				else {
					setCookie('pCompare', '', -1);
					_this.removeClass('active');
				}
			}
			else {
				setCookie('pCompare', cookieVal + ',' + pID);
				_this.addClass('active');
			}
		}
		updateCompareMenu();
	});

	jQuery('.bookmark-btn').on('click', function (e) {
		e.preventDefault();
		var _this    = jQuery(this),
			pID      = _this.data('p-id'),
			postData = {
				action:  "realty_house_update_bookmark",
				post_id: pID
			};

		jQuery.ajax({
			url:      realty_house_front.ajaxurl,
			method:   "POST",
			data:     postData,
			dataType: "html"
		}).done(function (response) {
			var returnData = JSON.parse(jQuery.trim(response));
			returnData.status == '1' ? _this.addClass('active') : _this.removeClass('active');
		});
	});

	jQuery('.remove-compare-btn').on('click', function (e) {
		e.preventDefault();

		var _this     = jQuery(this),
			pID       = _this.data('p-id'),
			cookieVal = getCookie('pCompare');

		if (cookieVal.indexOf(pID) >= 0) {

			if (cookieVal.indexOf(',') >= 0) {
				var currentPID = (cookieVal.indexOf(pID) == 0 ? pID + ',' : ',' + pID),
					newVal     = cookieVal.replace(currentPID, '');
				setCookie('pCompare', newVal);

			}
			else {
				setCookie('pCompare', '', -1);
			}
		}
		else {
			setCookie('pCompare', cookieVal + ',' + pID);
		}
		location.reload();
	});

	jQuery('.property-search-form').submit(function (event) {
		var searchFormElements = jQuery(this).find(':input');
		jQuery.each(searchFormElements, function (index, element) {
			if (element.value == '' || element.value == null) {
				if (!element.disabled) {
					element.disabled = true;
				}
			}
		});
	});

	jQuery.fn.magnificPopup && jQuery('.video-url').magnificPopup({
		disableOn:       700,
		type:            'iframe',
		mainClass:       'mfp-fade',
		removalDelay:    600,
		preloader:       false,
		fixedContentPos: false
	});

	jQuery.fn.magnificPopup && jQuery('#save-search-btn').magnificPopup({
		type:         'inline',
		removalDelay: 600,
		mainClass:    'mfp-fade'
	});

	jQuery('#save-search-form').find('form').on('submit', function (e) {
		e.preventDefault();
		var _this       = jQuery(this),
			searchQuery = _this.find('input[name="_wp_http_referer"]').val(),
			security    = _this.find('input[name="security-save-search"]').val(),
			title       = _this.find('input[name="search-title"]').val(),
			userID      = _this.find('input[name="current-user"]').val();

		if (searchQuery && security && title && userID) {
			_this.addClass('loading');
			jQuery.ajax({
				type:     'POST',
				dataType: 'json',
				url:      realty_house_front.ajaxurl,
				data:     {
					'action':   'realty_house_save_search',
					'query':    searchQuery,
					'title':    title,
					'userID':   userID,
					'security': security
				},
				success:  function (data) {
					_this.removeClass("loading");
					_this.find('.error-box').removeClass("alert-success alert-danger").text(data.message);
					if (data.status === true) {
						_this.find('.error-box').addClass("alert alert-success").text(data.message);
					}
					else {
						_this.find('.error-box').addClass("alert alert-danger").text(data.message);
					}
				}
			});
		}
		else {
			return false;
		}
	});
});