/*global redux_change, wp, redux*/

(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.currency = redux.field_objects.currency || {};

    $( document ).ready(
        function() {
            $('#currency-update-now').on('click', 'button', function (e) {
                e.preventDefault();
                var _parnet = $('#currency-update-now'),
                    _this = $(this),
                    _message = _parnet.find('.message');
				_parnet.addClass('loading');
				_message.removeClass('notice-green').text('');
				jQuery.ajax({
					type:     "POST",
					url:      redux_obj.ajaxurl,
					data:     {
						action:     "realty_house_update_currency"
					}
				}).done(function (data) {
					_parnet.removeClass('loading');
				    var data = JSON.parse(data);
					_message.addClass('notice-green').text(data.text);
				});
			})
        }
    );

    redux.field_objects.currency.init = function( selector ) {

        if ( !selector ) {
            selector = $( document ).find( ".redux-group-tab:visible" ).find( '.redux-container-currency:visible' );
        }

        $( selector ).each(
            function() {
                var el = $( this );

                redux.field_objects.media.init(el);

                var parent = el;
                if ( !el.hasClass( 'redux-field-container' ) ) {
                    parent = el.parents( '.redux-field-container:first' );
                }
                if ( parent.is( ":hidden" ) ) { // Skip hidden fields
                    return;
                }
                
                if ( parent.hasClass( 'redux-container-currency' ) ) {
                    parent.addClass( 'redux-field-init' );    
                }
                
                if ( parent.hasClass( 'redux-field-init' ) ) {
                    parent.removeClass( 'redux-field-init' );
                } else {
                    return;
                }

                el.find( '.redux-currency-remove' ).live(
                    'click', function() {
                        redux_change( $( this ) );

                        $( this ).parent().siblings().find( 'input[type="text"]' ).val( '' );
                        $( this ).parent().siblings().find( 'textarea' ).val( '' );
                        $( this ).parent().siblings().find( 'input[type="hidden"]' ).val( '' );

                        var slideCount = $( this ).parents( '.redux-container-currency:first' ).find( '.redux-currency-accordion-group' ).length;

                        if ( slideCount > 1 ) {
                            $( this ).parents( '.redux-currency-accordion-group:first' ).slideUp(
                                'medium', function() {
                                    $( this ).remove();
                                }
                            );
                        } else {
                            var content_new_title = $( this ).parent( '.redux-currency-accordion' ).data( 'new-content-title' );

                            $( this ).parents( '.redux-currency-accordion-group:first' ).find( '.remove-image' ).click();
                            $( this ).parents( '.redux-container-currency:first' ).find( '.redux-currency-accordion-group:last' ).find( '.redux-currency-header' ).text( content_new_title );
                        }
                    }
                );

                //el.find( '.redux-currency-add' ).click(
                el.find( '.redux-currency-add' ).off('click').click(
                    function() {
                        var newSlide = $( this ).prev().find( '.redux-currency-accordion-group:last' ).clone( true );

                        var slideCount = $( newSlide ).find( '.slide-title' ).attr( "name" ).match( /[0-9]+(?!.*[0-9])/ );
                        var slideCount1 = slideCount * 1 + 1;

                        $( newSlide ).find( 'input[type="text"], input[type="hidden"], input[type="checkbox"], textarea' ).each(
                            function() {

                                $( this ).attr(
                                    "name", jQuery( this ).attr( "name" ).replace( /[0-9]+(?!.*[0-9])/, slideCount1 )
                                ).attr( "id", $( this ).attr( "id" ).replace( /[0-9]+(?!.*[0-9])/, slideCount1 ) );
                                if(!$( this ).is(':checkbox')){
									$( this ).val( '' );
								}
                                if ( $( this ).hasClass( 'slide-sort' ) ) {
                                    $( this ).val( slideCount1 );
                                }
                            }
                        );

                        var content_new_title = $( this ).prev().data( 'new-content-title' );

                        $( newSlide ).find( 'h3' ).text( '' ).append( '<span class="redux-currency-header">' + content_new_title + '</span><span class="ui-accordion-header-icon ui-icon ui-icon-plus"></span>' );
                        $( this ).prev().append( newSlide );
                    }
                );

                el.find( '.slide-title' ).keyup(
                    function( event ) {
                        var newTitle = event.target.value;
                        $( this ).parents().eq( 3 ).find( '.redux-currency-header' ).text( newTitle );
                    }
                );


                el.find( ".redux-currency-accordion" )
                    .accordion(
                    {
                        header: "> div > fieldset > h3",
                        collapsible: true,
                        active: false,
                        heightStyle: "content",
                        icons: {
                            "header": "ui-icon-plus",
                            "activeHeader": "ui-icon-minus"
                        }
                    }
                )
                    .sortable(
                    {
                        axis: "y",
                        handle: "h3",
                        connectWith: ".redux-currency-accordion",
                        start: function( e, ui ) {
                            ui.placeholder.height( ui.item.height() );
                            ui.placeholder.width( ui.item.width() );
                        },
                        placeholder: "ui-state-highlight",
                        stop: function( event, ui ) {
                            // IE doesn't register the blur when sorting
                            // so trigger focusout handlers to remove .ui-state-focus
                            ui.item.children( "h3" ).triggerHandler( "focusout" );
                            var inputs = $( 'input.slide-sort' );
                            inputs.each(
                                function( idx ) {
                                    $( this ).val( idx );
                                }
                            );
                        }
                    }
                );
            }
        );
    };
})( jQuery );