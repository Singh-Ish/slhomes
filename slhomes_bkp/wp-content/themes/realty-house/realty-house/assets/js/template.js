"use strict";
function initialize() {
	var mapOptions = {
		zoom:               5,
		// This is where you would paste any style found on Snazzy Maps.
		styles:             [{
			"featureType": "water",
			"elementType": "geometry",
			"stylers":     [{"color": "#a0d6d1"}, {"lightness": 17}]
		}, {
			"featureType": "landscape",
			"elementType": "geometry",
			"stylers":     [{"color": "#f2f2f2"}, {"lightness": 20}]
		}, {
			"featureType": "road.highway",
			"elementType": "geometry.fill",
			"stylers":     [{"color": "#dedede"}, {"lightness": 17}]
		}, {
			"featureType": "road.highway",
			"elementType": "geometry.stroke",
			"stylers":     [{"color": "#dedede"}, {"lightness": 29}, {"weight": 0.2}]
		}, {
			"featureType": "road.arterial",
			"elementType": "geometry",
			"stylers":     [{"color": "#dedede"}, {"lightness": 18}]
		}, {
			"featureType": "road.local",
			"elementType": "geometry",
			"stylers":     [{"color": "#ffffff"}, {"lightness": 16}]
		}, {
			"featureType": "poi",
			"elementType": "geometry",
			"stylers":     [{"color": "#f1f1f1"}, {"lightness": 21}]
		}, {
			"elementType": "labels.text.stroke",
			"stylers":     [{"visibility": "on"}, {"color": "#ffffff"}, {"lightness": 16}]
		}, {
			"elementType": "labels.text.fill",
			"stylers":     [{"saturation": 36}, {"color": "#333333"}, {"lightness": 40}]
		}, {"elementType": "labels.icon", "stylers": [{"visibility": "off"}]}, {
			"featureType": "transit",
			"elementType": "geometry",
			"stylers":     [{"color": "#f2f2f2"}, {"lightness": 19}]
		}, {
			"featureType": "administrative",
			"elementType": "geometry.fill",
			"stylers":     [{"color": "#fefefe"}, {"lightness": 20}]
		}, {
			"featureType": "administrative",
			"elementType": "geometry.stroke",
			"stylers":     [{"color": "#fefefe"}, {"lightness": 17}, {"weight": 1.2}]
		}],
		// Extra options
		scrollwheel:        false,
		mapTypeControl:     false,
		panControl:         false,
		zoomControlOptions: {
			style:    google.maps.ZoomControlStyle.SMALL,
			position: google.maps.ControlPosition.LEFT_BOTTOM
		}
	};
	var map        = new google.maps.Map(document.getElementById('map'), mapOptions);

	var markers = [];

	if (typeof rawPropertyData !== 'undefined') {

		var rawPropertyDataJson = JSON.parse(rawPropertyData),
			bounds              = new google.maps.LatLngBounds();

		for (var i = 0; i < rawPropertyDataJson.property.length; i++) {
			var dataProperty   = rawPropertyDataJson.property[i],
				latLng         = new google.maps.LatLng(dataProperty.latitude, dataProperty.longitude),
				propertyType   = dataProperty.pType,
				propertyId     = dataProperty.pId,
				boxText        = document.createElement("div"),
				infoboxOptions = {
					content:                boxText,
					disableAutoPan:         false,
					pixelOffset:            new google.maps.Size(-125, 8),
					zIndex:                 null,
					alignBottom:            true,
					maxWidth:               200,
					boxClass:               "infobox-main-container",
					enableEventPropagation: true,
					closeBoxURL:            realtyHouse.assetsURL + "img/close.png",
					infoBoxClearance:       new google.maps.Size(1, 1)
				},
				marker         = new RichMarker({
					position: latLng,
					map:      map,
					flat:     true,
					content:  '<div class="map-marker ' + propertyType + '" id="p_id_' + propertyId + '"></div>'
				});

			markers.push(marker);

			bounds.extend(markers[i].getPosition());

			boxText.innerHTML  =
				'<div id="property-marker">' +
				'<div class="l-sec">' +
				'<a href="' + dataProperty.pURL + '">' +
				dataProperty.pImage +
				'</a>' +
				'<div class="caption">' +
				'<div class="title">' + dataProperty.title + '</div>' +
				'<div class="price-box">' + dataProperty.price + '</div>' +
				'</div>' +
				'</div>' +
				'<div class="r-sec">' +
				'<div class="location">' + dataProperty.location + '</div>' +
				'<div class="features">' +
				'<div class="bed"><i class="realty-house-bedroom"></i>' + dataProperty.bedroom + ' bd</div>' +
				'<div class="bath"><i class="realty-house-bathroom"></i>' + dataProperty.bathroom + ' ba</div>' +
				'<div class="size"><i class="realty-house-size"></i>' + dataProperty.size + ' sqft</div>' +
				'</div>' +
				'<div class="desc-title">Description</div>' +
				'<div class="desc">' + dataProperty.description + '</div>' +
				'</div>' +
				'</div>';
			markers[i].infobox = new InfoBox(infoboxOptions);

			google.maps.event.addListener(marker, 'click', (function (marker, i) {
				return function () {
					var h;
					for (h = 0; h < markers.length; h++) {
						markers[h].infobox.close();
					}
					markers[i].infobox.open(map, this);
				}
			})(marker, i));

			map.fitBounds(bounds);
		}
		var clusterStyles = [
			{
				url:    realtyHouse.assetsURL + 'img/pattern.png',
				height: 42,
				width:  42
			}
		];
		var markerCluster = new MarkerClusterer(map, markers, {styles: clusterStyles, maxZoom: 15});
	}

	if (jQuery('#location-search-box').length > 0) {
		var input        = document.getElementById('location-search-box');
		var autocomplete = new google.maps.places.Autocomplete(input);
	}
}
function dynamicInitialize(mapID, dataRaw) {
	var mapOptions = {
		zoom:               5,
		// This is where you would paste any style found on Snazzy Maps.
		styles:             [{
			"featureType": "water",
			"elementType": "geometry",
			"stylers":     [{"color": "#a0d6d1"}, {"lightness": 17}]
		}, {
			"featureType": "landscape",
			"elementType": "geometry",
			"stylers":     [{"color": "#f2f2f2"}, {"lightness": 20}]
		}, {
			"featureType": "road.highway",
			"elementType": "geometry.fill",
			"stylers":     [{"color": "#dedede"}, {"lightness": 17}]
		}, {
			"featureType": "road.highway",
			"elementType": "geometry.stroke",
			"stylers":     [{"color": "#dedede"}, {"lightness": 29}, {"weight": 0.2}]
		}, {
			"featureType": "road.arterial",
			"elementType": "geometry",
			"stylers":     [{"color": "#dedede"}, {"lightness": 18}]
		}, {
			"featureType": "road.local",
			"elementType": "geometry",
			"stylers":     [{"color": "#ffffff"}, {"lightness": 16}]
		}, {
			"featureType": "poi",
			"elementType": "geometry",
			"stylers":     [{"color": "#f1f1f1"}, {"lightness": 21}]
		}, {
			"elementType": "labels.text.stroke",
			"stylers":     [{"visibility": "on"}, {"color": "#ffffff"}, {"lightness": 16}]
		}, {
			"elementType": "labels.text.fill",
			"stylers":     [{"saturation": 36}, {"color": "#333333"}, {"lightness": 40}]
		}, {"elementType": "labels.icon", "stylers": [{"visibility": "off"}]}, {
			"featureType": "transit",
			"elementType": "geometry",
			"stylers":     [{"color": "#f2f2f2"}, {"lightness": 19}]
		}, {
			"featureType": "administrative",
			"elementType": "geometry.fill",
			"stylers":     [{"color": "#fefefe"}, {"lightness": 20}]
		}, {
			"featureType": "administrative",
			"elementType": "geometry.stroke",
			"stylers":     [{"color": "#fefefe"}, {"lightness": 17}, {"weight": 1.2}]
		}],
		// Extra options
		scrollwheel:        false,
		mapTypeControl:     false,
		panControl:         false,
		zoomControlOptions: {
			style:    google.maps.ZoomControlStyle.SMALL,
			position: google.maps.ControlPosition.LEFT_BOTTOM
		}
	};
	var map        = new google.maps.Map(document.getElementById(mapID), mapOptions);

	var markers             = [],
		rawPropertyDataJson = dataRaw,
		bounds              = new google.maps.LatLngBounds();

	for (var i = 0; i < rawPropertyDataJson.property.length; i++) {
		var dataProperty   = rawPropertyDataJson.property[i],
			latLng         = new google.maps.LatLng(dataProperty.latitude, dataProperty.longitude),
			propertyType   = dataProperty.pType,
			propertyId     = dataProperty.pId,
			boxText        = document.createElement("div"),
			infoboxOptions = {
				content:                boxText,
				disableAutoPan:         false,
				pixelOffset:            new google.maps.Size(-125, 8),
				zIndex:                 null,
				alignBottom:            true,
				maxWidth:               200,
				boxClass:               "infobox-main-container",
				enableEventPropagation: true,
				closeBoxURL:            realtyHouse.assetsURL + "img/close.png",
				infoBoxClearance:       new google.maps.Size(1, 1)
			},
			marker         = new RichMarker({
				position: latLng,
				map:      map,
				flat:     true,
				content:  '<div class="map-marker ' + propertyType + '" id="p_id_' + propertyId + '"></div>'
			});

		markers.push(marker);

		bounds.extend(markers[i].getPosition());

		boxText.innerHTML  =
			'<div id="property-marker">' +
			'<div class="l-sec">' +
			'<a href="' + dataProperty.pURL + '">' +
			dataProperty.pImage +
			'</a>' +
			'<div class="caption">' +
			'<div class="title">' + dataProperty.title + '</div>' +
			'<div class="price-box">' + dataProperty.price + '</div>' +
			'</div>' +
			'</div>' +
			'<div class="r-sec">' +
			'<div class="location">' + dataProperty.location + '</div>' +
			'<div class="features">' +
			'<div class="bed"><i class="realty-house-bedroom"></i>' + dataProperty.bedroom + ' bd</div>' +
			'<div class="bath"><i class="realty-house-bathroom"></i>' + dataProperty.bathroom + ' ba</div>' +
			'<div class="size"><i class="realty-house-size"></i>' + dataProperty.size + ' sqft</div>' +
			'</div>' +
			'<div class="desc-title">Description</div>' +
			'<div class="desc">' + dataProperty.description + '</div>' +
			'</div>' +
			'</div>';
		markers[i].infobox = new InfoBox(infoboxOptions);

		google.maps.event.addListener(marker, 'click', (function (marker, i) {
			return function () {
				var h;
				for (h = 0; h < markers.length; h++) {
					markers[h].infobox.close();
				}
				markers[i].infobox.open(map, this);
			}
		})(marker, i));

		map.fitBounds(bounds);
	}
	var clusterStyles = [
		{
			url:    realtyHouse.assetsURL + 'img/pattern.png',
			height: 42,
			width:  42
		}
	];
	var markerCluster = new MarkerClusterer(map, markers, {styles: clusterStyles, maxZoom: 15});

	if (jQuery('#location-search-box').length > 0) {
		var input        = document.getElementById('location-search-box');
		var autocomplete = new google.maps.places.Autocomplete(input);
	}
}

function pDetailMap() {
	var pMapContainer = jQuery('#property-map');


	var myLatLng   = new google.maps.LatLng(pMapContainer.data('lat'), pMapContainer.data('long'));
	var mapOptions = {
		zoom:               13,
		center:             myLatLng,
		// This is where you would paste any style found on Snazzy Maps.
		styles:             [{
			"featureType": "water",
			"elementType": "geometry",
			"stylers":     [{"color": "#a0d6d1"}, {"lightness": 17}]
		}, {
			"featureType": "landscape",
			"elementType": "geometry",
			"stylers":     [{"color": "#f2f2f2"}, {"lightness": 20}]
		}, {
			"featureType": "road.highway",
			"elementType": "geometry.fill",
			"stylers":     [{"color": "#dedede"}, {"lightness": 17}]
		}, {
			"featureType": "road.highway",
			"elementType": "geometry.stroke",
			"stylers":     [{"color": "#dedede"}, {"lightness": 29}, {"weight": 0.2}]
		}, {
			"featureType": "road.arterial",
			"elementType": "geometry",
			"stylers":     [{"color": "#dedede"}, {"lightness": 18}]
		}, {
			"featureType": "road.local",
			"elementType": "geometry",
			"stylers":     [{"color": "#ffffff"}, {"lightness": 16}]
		}, {
			"featureType": "poi",
			"elementType": "geometry",
			"stylers":     [{"color": "#f1f1f1"}, {"lightness": 21}]
		}, {
			"elementType": "labels.text.stroke",
			"stylers":     [{"visibility": "on"}, {"color": "#ffffff"}, {"lightness": 16}]
		}, {
			"elementType": "labels.text.fill",
			"stylers":     [{"saturation": 36}, {"color": "#333333"}, {"lightness": 40}]
		}, {"elementType": "labels.icon", "stylers": [{"visibility": "off"}]}, {
			"featureType": "transit",
			"elementType": "geometry",
			"stylers":     [{"color": "#f2f2f2"}, {"lightness": 19}]
		}, {
			"featureType": "administrative",
			"elementType": "geometry.fill",
			"stylers":     [{"color": "#fefefe"}, {"lightness": 20}]
		}, {
			"featureType": "administrative",
			"elementType": "geometry.stroke",
			"stylers":     [{"color": "#fefefe"}, {"lightness": 17}, {"weight": 1.2}]
		}],
		// Extra options
		scrollwheel:        false,
		mapTypeControl:     false,
		panControl:         false,
		zoomControlOptions: {
			style:    google.maps.ZoomControlStyle.SMALL,
			position: google.maps.ControlPosition.LEFT_BOTTOM
		}
	};
	var map        = new google.maps.Map(document.getElementById('property-map'), mapOptions);

	var image = realtyHouse.assetsURL + 'img/marker.png';

	var beachMarker = new google.maps.Marker({
		position: myLatLng,
		map:      map,
		icon:     image
	});
}

function syncPosition(el) {
	var current = this.currentItem,
		sync2   = jQuery("#thumbnail-slider");
	sync2
		.find(".owl-item")
		.removeClass("synced")
		.eq(current)
		.addClass("synced");
	if (sync2.data("owlCarousel") !== undefined) {
		center(current)
	}
}

function center(number) {
	var sync2        = jQuery("#thumbnail-slider"),
		sync2visible = sync2.data("owlCarousel").owl.visibleItems,
		num          = number,
		found        = false;
	for (var i in sync2visible) {
		if (num === sync2visible[i]) {
			var found = true;
		}
	}
	if (found === false) {
		if (num > sync2visible[sync2visible.length - 1]) {
			sync2.trigger("owl.goTo", num - sync2visible.length + 2)
		} else {
			if (num - 1 === -1) {
				num = 0;
			}
			sync2.trigger("owl.goTo", num);
		}
	} else if (num === sync2visible[sync2visible.length - 1]) {
		sync2.trigger("owl.goTo", sync2visible[1])
	} else if (num === sync2visible[0]) {
		sync2.trigger("owl.goTo", num - 1)
	}
}

jQuery(document).ready(function () {
	var adminAjax = realtyHouse.ajaxurl;

	// Clone the main menu to the mobile menu
	jQuery('#main-menu').clone().removeClass().appendTo('#mobile-menu-container');

	// Enable Menu menu toggling
	jQuery('#main-menu-handle').on('click', function () {
		jQuery(this).toggleClass('active');
		jQuery('#mobile-menu-container').slideToggle(function () {
			jQuery(window).trigger('scroll').trigger('resize');
		});
	});

	jQuery('[data-bg-img]').each(function () {
		var _this = jQuery(this);
		if (_this.data('bg-img')) {
			_this.css('background-image', 'url(' + _this.data('bg-img') + ')');
		}
	});

	// Enable Featured properties slider
	if (jQuery('#featured-properties .owl-carousel').length > 0) {
		jQuery("#featured-properties .owl-carousel").owlCarousel({
			items:             2,
			itemsDesktop:      [1200, 2],
			itemsDesktopSmall: [980, 1],
			itemsTablet:       [768, 1],
			itemsMobile:       [480, 1],
			navigation:        !1,
			pagination:        !0
		});
	}

	// Enable Our Agents slider
	if (jQuery('.agent-box-container').length > 0) {
		jQuery(".agent-box-container").owlCarousel({
			items:             3,
			itemsDesktop:      [1200, 3],
			itemsDesktopSmall: [980, 2],
			itemsTablet:       [768, 2],
			itemsMobile:       [600, 1],
			navigation:        !0,
			pagination:        !1
		});
	}

	// Search Boxes
	var mainSearchBox = jQuery("[id*='search-box-']");
	if (mainSearchBox.length > 0 && mainSearchBox.hasClass('accordion')) {
		mainSearchBox.on('click', '.title-box', function () {
			var $this         = jQuery(this),
				clickedParent = $this.parent();
			if (!clickedParent.hasClass('active')) {
				clickedParent.addClass('active').siblings().removeClass('active');
				mainSearchBox.find('.content-box').slideUp();
				$this.next('.content-box').slideDown();
			}
		});
	}

	if (mainSearchBox.length > 0 && mainSearchBox.hasClass('horizontal')) {
		mainSearchBox.on('click', '.more-options', function (e) {
			e.preventDefault();
			mainSearchBox.find('.advanced-search-sec').slideToggle();
		});
		mainSearchBox.on('click', '.title', function () {
			jQuery(this).toggleClass('disable');
			mainSearchBox.find('.field-main-container').slideToggle();
		});
	}

	// SideBar SearchForm
	var sideBarSearch = jQuery('#side-bar').find('.property-search-form');
	if (sideBarSearch.length > 0) {
		sideBarSearch.find('.more-options').on('click', function (e) {
			e.preventDefault();
			sideBarSearch.find('.b-sec').slideToggle();
		});
	}

	if (jQuery.isFunction(jQuery.fn.select2)) {
		// Change all the select boxes to select2
		jQuery("select:not(.rate-items):not([id*='currency-switcher-select-'])").select2({
			minimumResultsForSearch: 10
		});
		jQuery("[id*='currency-switcher-select-']").select2({
			minimumResultsForSearch: 10,
			dropdownCssClass:        "currency-switcher"
		});

	}

	// Enable Range Sliders
	if (jQuery('.range-slider').length > 0) {
		jQuery(".range-slider").ionRangeSlider({
			type:                   'double',
			step:                   100,
			input_values_separator: "-",
			prettify_separator:     ",",
			force_edges:            true,
			prettify:               false
		});
	}

	if (jQuery(".realty-main-slider").length > 0) {

		// Main Slider
		var mainSlider = jQuery(".realty-main-slider");

		mainSlider.find('.items').each(function () {
			var imgContainer = jQuery(this).children('.img-container');
			imgContainer.css('background-image', 'url(' + imgContainer.data('bg-img') + ')');
		});

		mainSlider.owlCarousel({
			navigation:     !0,
			singleItem:     !0,
			addClassActive: !0,
			autoPlay:       !0,
			pagination:     !1
		});
	}

	if (jQuery.isFunction(jQuery.fn.isotope)) {
		var propertyMainContainer = jQuery(".property-boxes:not(.map-view)");
		var isoTopProperty        = propertyMainContainer.isotope({
			transitionDuration: "0.7s"
		});
		isoTopProperty.on('layoutComplete', function () {
			jQuery(window).trigger('scroll').trigger('resize');
		});
		propertyMainContainer.imagesLoaded(function () {
			propertyMainContainer.isotope("layout");
			jQuery(".sort-section-container").on("click", "a", function (e) {
				e.preventDefault();
				jQuery(".sort-section-container a").removeClass("active");
				jQuery(this).addClass("active");
				var filterValue = jQuery(this).attr("data-filter");
				propertyMainContainer.isotope({filter: filterValue});
				jQuery(window).trigger('resize.px.parallax');
			});
		});
	}

	if (jQuery.isFunction(jQuery.fn.isotope)) {
		var mainContainer = jQuery(".image-main-box");
		var isotopGallery = mainContainer.isotope({
			transitionDuration: "0.7s"
		});
		isotopGallery.on('layoutComplete', function () {
			jQuery(window).trigger('scroll').trigger('resize');
		});
	}

	if (jQuery.isFunction(jQuery.fn.isotope)) {
		var mainContainerService = jQuery(".features-container:not(.single)");
		var isotopServices       = mainContainerService.isotope({
			transitionDuration: "0.7s"
		});
		isotopServices.on('layoutComplete', function () {
			jQuery(window).trigger('scroll').trigger('resize');
		});
	}

	if (jQuery.isFunction(jQuery.fn.isotope)) {
		var testiMainContainer = jQuery(".testimonials-main-container:not(.slider)");
		var isoTopProperty     = testiMainContainer.isotope({
			transitionDuration: "0.7s"
		});
		isoTopProperty.on('layoutComplete', function () {
			jQuery(window).trigger('scroll').trigger('resize');
		});
	}

	if (jQuery.isFunction(jQuery.fn.magnificPopup)) {
		jQuery('#login-form-url, #login-register-url, #send-price-offer').magnificPopup({
			type:         'inline',
			removalDelay: 600,
			mainClass:    'mfp-fade'
		});


		jQuery('.image-main-box').magnificPopup({
			delegate:     '.more-details',
			type:         'image',
			removalDelay: 600,
			mainClass:    'mfp-fade',
			gallery:      {
				enabled:            true,
				navigateByImgClick: true,
				preload:            [0, 1] // Will preload 0 - before current, and 1 after the current image
			},
			image:        {
				titleSrc: 'data-title',
				tError:   '<a href="%url%">The image #%curr%</a> could not be loaded.'
			}
		});
	}

	//Enable neighborhood buttons
	function enableByButtons() {
		jQuery('.neighborhood-row .btn-container').on('click', 'button', function () {
			jQuery(this).parent().siblings('.neighbor-by').val(jQuery(this).data('value'));
			jQuery(this).addClass('active').siblings('button').removeClass('active');
		});
	}

	if (jQuery('.neighborhood-row').length > 0) {

		enableByButtons();
		// Add new neighborhood
		jQuery('.add-neighborhood-btn').on('click', function () {
			var neighboorPattern = jQuery('#neighborhood-pattern').clone().attr('id', '');
			jQuery('.neighborhood-container').append(neighboorPattern);
			jQuery(window).trigger('resize.px.parallax');
			enableByButtons();
		});
	}

	if (jQuery('.counter-box').length > 0) {

		jQuery('.counter-box').on('inview', function (event, isInView) {
			if (isInView) {
				jQuery(this).find('.value').countTo({
					speed:     3000,
					formatter: function (value, options) {
						return value.toFixed(options.decimals).replace(/\B(?=(?:\d{3})+(?!\d))/g, ',');
					}
				});
			}
		});
	}

	if (jQuery('.twitter-news-ticker').length > 0) {
		jQuery('.twitter-news-ticker').newsTicker({
			row_height: 40,
			speed:      800,
			max_rows:   1,
			duration:   4000,
			prevButton: jQuery('.prev-button'),
			nextButton: jQuery('.next-button')
		});
	}

	// Enable Google Map
	if (jQuery('#map').length > 0) {
		initialize();
	}


	// Enable Google Map
	if (jQuery('#property-map').length > 0) {
		pDetailMap();
	}

	// Enable Location auto complete
	if (jQuery('#location-search-box').length > 0 && jQuery('#map').length == 0) {
		var input        = document.getElementById('location-search-box');
		var autocomplete = new google.maps.places.Autocomplete(input);
	}

	if (jQuery.isFunction(jQuery.fn.mCustomScrollbar)) {
		jQuery('#property-listing-map .property-listing-container').mCustomScrollbar({
			scrollButtons: {enable: true}
		});
	}


	// FAQ section
	var faqBox = jQuery('.faq-box');
	if (faqBox.length > 0) {
		jQuery('.faq-box.opened').find('.content').slideDown();
		faqBox.on('click', '.title', function () {
			jQuery(this).next('.content').stop().slideToggle().parent().toggleClass('opened');
		});
	}

	if (jQuery('.details-boxes').length > 0) {
		jQuery('.details-boxes').on('click', '.title', function () {
			jQuery(this).next('.content').stop().slideToggle().parent('.details-boxes').toggleClass('closed');
		});
	}
	if (jQuery('#reply-title').length > 0) {
		jQuery('#reply-title').on('click', function () {
			jQuery(this).next('form').stop().slideToggle().parents('#respond').toggleClass('closed');
		});
	}


	var postSlider = jQuery(".post-slider");
	if (postSlider.length > 0 && jQuery.fn.owlCarousel) {
		// Post Slider
		postSlider.owlCarousel({
			navigation:     !0,
			loop:           !0,
			addClassActive: !0,
			singleItem:     true,
			pagination:     !1
		});
	}

	if (jQuery('#property-slider').length > 0) {

		// Property Details Sliders
		var sync1 = jQuery("#property-slider"),
			sync2 = jQuery("#thumbnail-slider");

		sync1.owlCarousel({
			singleItem:            true,
			slideSpeed:            1000,
			navigation:            true,
			pagination:            false,
			afterAction:           syncPosition,
			responsiveRefreshRate: 200
		});

		sync2.owlCarousel({
			items:                 8,
			itemsDesktop:          [1199, 6],
			itemsDesktopSmall:     [979, 5],
			itemsTablet:           [768, 4],
			itemsMobile:           [479, 2],
			pagination:            false,
			responsiveRefreshRate: 100,
			afterInit:             function (el) {
				el.find(".owl-item").eq(0).addClass("synced");
			}
		});

		sync2.on("click", ".owl-item", function (e) {
			e.preventDefault();
			var number = jQuery(this).data("owlItem");
			sync1.trigger("owl.goTo", number);
		});
	}

	if (jQuery('body').hasClass('compare')) {
		var compareItemContainer = jQuery('.value-list .property-items-container'),
			propertyItems        = compareItemContainer.find('.property-item'),
			itemsWidth           = propertyItems.width(),
			itemsLength          = propertyItems.length;

		compareItemContainer.width(itemsWidth * itemsLength);

		jQuery('.value-list').mCustomScrollbar({
			axis:          "x",
			scrollButtons: {enable: true}
		});
	}

	if (jQuery('body').hasClass('video-tour')) {

		// Property Details Sliders
		var sync1 = jQuery("#video-tour-slider"),
			sync2 = jQuery("#thumbnail-slider");

		sync1.owlCarousel({
			singleItem:            true,
			slideSpeed:            1000,
			navigation:            false,
			pagination:            false,
			afterAction:           syncPosition,
			responsiveRefreshRate: 200
		});

		sync2.owlCarousel({
			items:                 8,
			itemsDesktop:          [1199, 6],
			itemsDesktopSmall:     [979, 5],
			itemsTablet:           [768, 4],
			itemsMobile:           [479, 2],
			pagination:            false,
			responsiveRefreshRate: 100,
			afterInit:             function (el) {
				el.find(".owl-item").eq(0).addClass("synced");
			}
		});

		sync2.on("click", ".owl-item", function (e) {
			e.preventDefault();
			var number = jQuery(this).data("owlItem");
			sync1.trigger("owl.goTo", number);
		});
	}

	var currencySwitchers = jQuery('[id*="currency-switcher-select-"]');
	if (currencySwitchers.length > 0) {
		currencySwitchers.on('change', function () {
			var _this    = jQuery(this),
				postData = {
					action:   "realty_house_currency_cookie",
					currency: _this.val()
				};
			jQuery.ajax({
				url:    adminAjax,
				method: "POST",
				data:   postData
			}).done(function (response) {
				if (response) {
					location.reload();
				}
			});
		})
	}

	// Login Ajax
	var loginFrom = jQuery('#login-form').find('form');
	loginFrom.on("submit", function (e) {
		e.preventDefault();
		loginFrom.addClass("loading");
		loginFrom.find('.error-box').removeClass("alert alert-danger").text('');
		jQuery.ajax({
			type:     'POST',
			dataType: 'json',
			url:      realtyHouse.ajaxurl,
			data:     {
				'action':   'realty_house_login',
				'username': loginFrom.find('.email').val(),
				'password': loginFrom.find('.pass').val(),
				'security': loginFrom.find('#security-login').val()
			},
			success:  function (data) {
				loginFrom.removeClass("loading");
				if (data.loggedin === true) {
					document.location.href = realtyHouse.redirecturl;
				}
				else {
					loginFrom.find('.error-box').addClass("alert alert-danger").text(data.message);
				}
			}
		});
	});

	// Register Ajax
	var registerFrom = jQuery('#register-form').find('form');
	registerFrom.on("submit", function (e) {
		e.preventDefault();
		registerFrom.addClass("loading");
		registerFrom.find('.error-box').removeClass("alert alert-danger").text('');
		var userName = registerFrom.find('.user-name'),
			email    = registerFrom.find('.email');

		if (userName.val() !== '' && email.val() !== '') {
			jQuery.ajax({
				type:     'POST',
				dataType: 'json',
				url:      realtyHouse.ajaxurl,
				data:     {
					'action':   'realty_house_register',
					'username': userName.val(),
					'email':    email.val(),
					'security': registerFrom.find('#security-register').val()
				},
				success:  function (data) {
					registerFrom.removeClass("loading");
					userName.parent().removeClass('has-error');
					email.parent().removeClass('has-error');
					if (data.loggedin === true) {
						registerFrom.find('.error-box').addClass("alert alert-success").text(data.message);
					}
					else {
						registerFrom.find('.error-box').addClass("alert alert-danger").text(data.message);
					}
				}
			});
		}
		else {
			registerFrom.removeClass("loading");
			userName.parent().removeClass('has-error');
			email.parent().removeClass('has-error');
			if (userName.val() === '') {
				userName.parent().addClass('has-error');
			}
			if (email.val() === '') {
				email.parent().addClass('has-error');
			}
			return false;
		}
	})
});