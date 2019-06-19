function open_modal_box(id, height, width, checkradio_style_prop, is_close_link, checked_checkbox_prop, checked_radio_prop, popup_data_uniq_id) {
    
    var arf_body_class = jQuery('body').attr('class').split(' ');
    var body_class = '';
    for (var i = 0; i < arf_body_class.length; i++) {
        body_class = arf_body_class[i].split('arforms_model_view_');
        if(body_class[1] != 'undefined' && jQuery.isNumeric(body_class[1])){
            jQuery('body').removeClass(arf_body_class[i]);
        }
    }
    jQuery('body').addClass("arforms_model_view_"+id);

    jQuery(document).ready(function ($)
    {
        jQuery(".vpb_captcha").keypress(function () {
            jQuery(this).parents('.arfformfield').find('.popover').remove();
            jQuery(this).removeClass('control-group');
            jQuery(this).removeClass('arf_error');
        });
    });

    var admin_ajax_url = jQuery('#admin_ajax_url').val();
    admin_ajax_url = is_ssl_replace(admin_ajax_url);
    jQuery.ajax({type: "POST", url: admin_ajax_url, data: "action=current_modal&position_modal=arf_modal_default",
        success: function (errObj) {
        }
    });

    var screenheight = jQuery(window).height();
    var screenwidth = jQuery(window).width();
    var modal_body_height = Number(height);
    var checkstep = 0;

    if ((height) >= screenheight)
    {
        var tmp_height = Number(height) - Number(screenheight);
        checkstep = 1;
    }
    else
    {
        var tmp_height = Number(screenheight) - Number(height);
        checkstep = 0;
    }

    if (checkstep != 0)
    {
        var total_height = 20;
        modal_body_height = screenheight - 55;
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('max-height', (screenheight - 40) + 'px');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('height', (screenheight - 40) + 'px');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arfmodal-body').css('height', modal_body_height + 'px');
    }
    else
    {
        var total_height = Number(tmp_height / 2);
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('max-height', modal_body_height + 'px');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('height', modal_body_height + 'px');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arfmodal-body').css('height', (modal_body_height - 15) + 'px');
    }

    var tmp_width = Number(screenwidth) - Number(width);
    var total_width = Number(tmp_width / 2);

    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('top', total_height + 'px');

    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('left', total_width + 'px');

    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('data-mtop', total_height);
    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('data-mleft', total_width);



    /*19-06-2015 */
    var scroll_top = jQuery(window).scrollTop();
    if (height == '' || height == 'auto') {
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('top', (scroll_top + 20) + 'px');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('height', 'auto');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arfmodal-body').css('height', 'auto');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('position', 'absolute');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arfmodal-body').css('overflow','visible');
    }
    /*19-06-2015  */


    /*01-08-2015*/
        jQuery('.arf_pop_' + popup_data_uniq_id).find('.arf_form.ar_main_div_' + id).css('max-width',width+'px');
    /*01-08-2015*/

    var form_key = jQuery('.arf_pop_' + popup_data_uniq_id).find('#form_key_' + id).val();
    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find('.arfmodal-body #form_' + form_key).show();
    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find('.arfmodal-body .arf_content_another_page').empty().hide();
    // jQuery('#popup-form-' + id).arfmodal({show: true});
    if (is_close_link == 'no') {
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).arfmodal({
            show: true,
            backdrop: 'static',
            keyboard: true
        });
    } else {
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).arfmodal({show: true});
    }

    if (screenwidth <= 770)
    {
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("height", 'auto');

        var windowHeight = jQuery(window).height() - Number(60);
        var windowHeightOrg = jQuery(window).height();
        var actualheight = jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find('.arf_fieldset').height();

        if (actualheight < windowHeight)
        {
            if (screenwidth < width) {
                jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("height", windowHeightOrg + "px");
            }
        }

        /*19-06-2015 */
        if (screenwidth < width) {
            if (height == '' || height == 'auto') {
                jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('position', 'fixed');
                jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arfmodal-body').css('overflow','auto');
            }
        }
        /*19-06-2015 */

    }

    var data_open = jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).attr('data-open');
    data_open = (data_open !== undefined) ? data_open : true;
    if (checkradio_style_prop != "" && data_open == true)
    {
        if (jQuery.isFunction(jQuery().iCheck))
        {
            if(checkradio_style_prop == 'custom') {
            jQuery('.arf_pop_' + popup_data_uniq_id).find('#arffrm_' + id + '_container input').not('.arf_hide_opacity').iCheck({
                checkboxClass: 'icheckbox_' + checkradio_style_prop,
                radioClass: 'iradio_' + checkradio_style_prop,
                    hoverClass: ' ',
                    checkedCheckboxClass: checked_checkbox_prop,
                    checkedRadioClass: checked_radio_prop
            });
            } else {
                jQuery('.arf_pop_' + popup_data_uniq_id).find('#arffrm_' + id + '_container input').not('.arf_hide_opacity').iCheck({
                    checkboxClass: 'icheckbox_' + checkradio_style_prop,
                    radioClass: 'iradio_' + checkradio_style_prop,
                    increaseArea: '25%'
                });
            }
        }
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).attr('data-open', 'false');
    }

    // for colorpicker
    if (data_open == true)
    {
        arfmodalcolorpicker(id);
    }

    // for file upload
    var arfmainformurl = jQuery('.arf_pop_' + popup_data_uniq_id).find('#arfmainformurl').val();
    arfmainformurl = is_ssl_replace(arfmainformurl);
    var url = arfmainformurl + '/js/filedrag/filedrag_front.js';
    var submit_type = jQuery('.arf_pop_' + popup_data_uniq_id).find('#form_submit_type').val();
    if (submit_type == 1) {
        jQuery.getScript(url);
    }
    jQuery('.arf_pop_' + popup_data_uniq_id).find('.original_normal').on('change', function (e) {
        var id = jQuery(this).attr('id');
        id = id.replace('field_', '');

        var fileName = jQuery(this).val();
        fileName = fileName.replace(/C:\\fakepath\\/i, '');
        if (fileName != '') {
            jQuery('.arf_pop_' + popup_data_uniq_id).find('#file_name_' + id).html(fileName);
        }
    });
    // for file upload end

    //for star rating
    jQuery('.rate_widget').each(function (i) {

        widget_id = jQuery(this).attr('id');
        var rate_data_id = jQuery(this).parents('form').attr('data-id');    
        var current_frm = jQuery("form.arfshowmainform[data-id='"+rate_data_id+"']");

        current_frm.find('.ratings_stars').hover(
                function () {
                    var color = jQuery(this).attr('data-color');
                    var datasize = jQuery(this).attr('data-size');
                    jQuery(this).prevAll().andSelf().addClass('ratings_over_' + color + datasize);
                    jQuery(this).nextAll().removeClass('ratings_vote_' + color + datasize);
                },
                function () {
                    var color = jQuery(this).attr('data-color');
                    var datasize = jQuery(this).attr('data-size');
                    jQuery(this).prevAll().andSelf().removeClass('ratings_over_' + color + datasize);
                    widget_id_new = jQuery(this).parent().attr('id');
                    set_votes(jQuery(this).parent(), widget_id_new);
                }
        );

        current_frm.find('.ratings_stars').bind('click', function () {
            var star = this;
            var widget = jQuery(this).parent();

            var clicked_data = {
                clicked_on: jQuery(star).attr('data-val'),
                widget_id: jQuery(star).parent().attr('id')
            };
            widget_id_new = jQuery(this).parent().attr('id');

            current_frm.find('#field_' + widget_id_new).val(clicked_data.clicked_on);
            current_frm.find('#field_' + widget_id_new).trigger('click');
            set_votes(widget, widget_id_new);
        });

    });
    //for star rating

    //for like button
    jQuery('.arf_like_btn, .arf_dislike_btn').not('.field_edit').on("click", function () {
        var field = jQuery(this).attr('id');
        var field = field.replace('like_', 'field_');
        if (!jQuery("#" + field).is(':checked')) {
            jQuery("#" + jQuery(this).attr("for")).trigger('click').trigger('change');
        }
    });

    jQuery('.arf_form .arf_like').on("click", function () {
        var field = jQuery(this).attr('id');
        field_data = field.split('-');

        var field_id = field_data[0];
        field_id = field_id.replace('field_', '');
        field_id = 'like_' + field_id;
        var like = field_data[1];

        if (like == 1) {
            jQuery('#' + field_id + '-0').removeClass('active');
            jQuery('#' + field_id + '-1').addClass('active');
        } else if (like == 0) {
            jQuery('#' + field_id + '-1').removeClass('active');
            jQuery('#' + field_id + '-0').addClass('active');
        }
    });
    jQuery('.arf_like_btn, .arf_dislike_btn').each(function () {
        var title = jQuery(this).attr('data-title');
        if (title !== undefined) {
            jQuery(this).popover({
                html: true,
                trigger: 'hover',
                placement: 'top',
                content: title,
                title: '',
                animation: false
            });
        }
    });
    //for like button end

    arf_change_modal_slider(jQuery('.arf_pop_' + popup_data_uniq_id).find('#form_' + form_key));		//for modal slider
}

function open_modal_box_fly_left(id, height, width, checkradio_style_prop, checked_checkbox_prop, checked_radio_prop, popup_data_uniq_id)
{
    
    var arf_body_class = jQuery('body').attr('class').split(' ');
    var body_class = '';
    for (var i = 0; i < arf_body_class.length; i++) {
        body_class = arf_body_class[i].split('arforms_model_fly_left_view_');
        if(body_class[1] != 'undefined' && jQuery.isNumeric(body_class[1])){
            jQuery('body').removeClass(arf_body_class[i]);
        }
    }
    jQuery('body').addClass("arforms_model_fly_left_view_"+id);
    
    jQuery(document).ready(function ($)
    {
        jQuery(".vpb_captcha").keypress(function () {
            jQuery(this).parents('.arfformfield').find('.popover').remove();
            jQuery(this).removeClass('control-group');
            jQuery(this).removeClass('arf_error');
        });
    });

    var admin_ajax_url = jQuery('.arf_pop_' + popup_data_uniq_id).find('#admin_ajax_url').val();
    admin_ajax_url = is_ssl_replace(admin_ajax_url);
    jQuery.ajax({type: "POST", url: admin_ajax_url, data: "action=current_modal&position_modal=arf_modal_left",
        success: function (errObj) {
        }
    });

    var screenheight = jQuery(window).height();
    var screenwidth = jQuery(window).width();
    var modal_body_height = Number(height);
    var checkstep = 0;

    if ((height) >= screenheight)
    {
        var tmp_height = Number(height) - Number(screenheight);
        checkstep = 1;
    }
    else
    {
        var tmp_height = Number(screenheight) - Number(height);
        checkstep = 0;
    }

    if (checkstep != 0)
    {
        var total_height = 20;
        modal_body_height = screenheight - 57;
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('max-height', (screenheight - 40) + 'px');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('height', (screenheight - 40) + 'px');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arfmodal-body').css('height', modal_body_height + 'px');
    }
    else
    {
        var total_height = Number(tmp_height / 2);
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('max-height', modal_body_height + 'px');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('height', modal_body_height + 'px');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arfmodal-body').css('height', (modal_body_height - 15) + 'px');
    }

    var tmp_width = Number(screenwidth) - Number(width);
    var total_width = Number(tmp_width / 2);

    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('top', total_height);

    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('data-mtop', total_height);
    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('data-mleft', total_width);




    /*19-06-2015 */
    var scroll_top = jQuery(window).scrollTop();
    if (height == '' ||  height == 'auto') {

        total_height = scroll_top + 20;
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css({opacity: "0", display: "block"});
        var actualheight = jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find('.arf_fieldset').outerHeight();
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css({opacity: '', display: ""});

        if (screenheight > actualheight) {
            var total_height = (screenheight - actualheight) / 2;
        }
        //jQuery('#popup-form-' + id).css('top', (scroll_top + 20) + 'px');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('height', 'auto');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arfmodal-body').css('height', 'auto');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('position', 'absolute');

        
        var isSafari = !!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/)
        if (!isSafari){
          jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arfmodal-body').css('overflow','visible');
        } 
        
        
    }
    /*19-06-2015  */
    
    /*01-08-2015*/
        jQuery('.arf_pop_' + popup_data_uniq_id).find('.arf_form.ar_main_div_' + id).css('max-width',width+'px');
    /*01-08-2015*/

    jQuery('.arf_popup_' + popup_data_uniq_id).find('.arform_side_block_left_' + id).hide();
    if (jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).hasClass('in'))
    {
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).removeClass('in');
        jQuery('.arf_popup_' + popup_data_uniq_id).find('.arform_side_block').show();
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).animate({
            'top': total_height + 'px',
            'left': total_width + 'px',
            'right': total_width + 'px',
            'bottom': total_height + 'px',
        }, 500);
    }
    else
    {
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).addClass('in');

        var form_key = jQuery('.arf_pop_' + popup_data_uniq_id).find('#form_key_' + id).val();
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find('.arfmodal-body #form_' + form_key).show();
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find('.arfmodal-body .arf_content_another_page').empty().hide();

        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arform_sb_fx_form').css('display', 'block');
        jQuery('.arf_popup_' + popup_data_uniq_id).find('#open_modal_box_fly_left_' + id).show();
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).animate({
            'top': total_height + 'px',
            'left': total_width + 'px',
            'right': total_width + 'px',
            'bottom': total_height + 'px',
        }, 500);
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('display', 'block');

        var data_open = jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).attr('data-open');
        data_open = (data_open !== undefined) ? data_open : true;

        if (checkradio_style_prop != "" && data_open == true)
        {
            if (jQuery.isFunction(jQuery().iCheck))
            {
                if(checkradio_style_prop == 'custom') {
                jQuery('.arf_pop_' + popup_data_uniq_id).find('#arffrm_' + id + '_container input').not('.arf_hide_opacity').iCheck({
                    checkboxClass: 'icheckbox_' + checkradio_style_prop,
                    radioClass: 'iradio_' + checkradio_style_prop,
                        hoverClass: ' ',
                        checkedCheckboxClass: checked_checkbox_prop,
                        checkedRadioClass: checked_radio_prop
                });
                } else {
                    jQuery('.arf_pop_' + popup_data_uniq_id).find('#arffrm_' + id + '_container input').not('.arf_hide_opacity').iCheck({
                        checkboxClass: 'icheckbox_' + checkradio_style_prop,
                        radioClass: 'iradio_' + checkradio_style_prop,
                        increaseArea: '25%'
                    });
                }
            }
            jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).attr('data-open', 'false');
        }

        // for colorpicker
        if (data_open == true)
        {
            arfmodalcolorpicker(id);
        }

        // for file upload
        var arfmainformurl = jQuery('.arf_pop_' + popup_data_uniq_id).find('#arfmainformurl').val();
        arfmainformurl = is_ssl_replace(arfmainformurl);
        var url = arfmainformurl + '/js/filedrag/filedrag_front.js';
        var submit_type = jQuery('.arf_pop_' + popup_data_uniq_id).find('#form_submit_type').val();
        if (submit_type == 1) {
            jQuery.getScript(url);
        }
        jQuery('.arf_pop_' + popup_data_uniq_id).find('.original_normal').on('change', function (e) {
            var id = jQuery(this).attr('id');
            id = id.replace('field_', '');

            var fileName = jQuery(this).val();
            fileName = fileName.replace(/C:\\fakepath\\/i, '');
            if (fileName != '') {
                jQuery('.arf_pop_' + popup_data_uniq_id).find('#file_name_' + id).html(fileName);
            }
        });
        // for file upload end
        //for star rating
        jQuery('.rate_widget').each(function (i) {

            widget_id = jQuery(this).attr('id');
            var rate_data_id = jQuery(this).parents('form').attr('data-id');    
            var current_frm = jQuery("form.arfshowmainform[data-id='"+rate_data_id+"']");

            current_frm.find('.ratings_stars').hover(
                    function () {
                        var color = jQuery(this).attr('data-color');
                        var datasize = jQuery(this).attr('data-size');
                        jQuery(this).prevAll().andSelf().addClass('ratings_over_' + color + datasize);
                        jQuery(this).nextAll().removeClass('ratings_vote_' + color + datasize);
                    },
                    function () {
                        var color = jQuery(this).attr('data-color');
                        var datasize = jQuery(this).attr('data-size');
                        jQuery(this).prevAll().andSelf().removeClass('ratings_over_' + color + datasize);
                        widget_id_new = jQuery(this).parent().attr('id');
                        set_votes(jQuery(this).parent(), widget_id_new);
                    }
            );

            current_frm.find('.ratings_stars').bind('click', function () {
                var star = this;
                var widget = jQuery(this).parent();

                var clicked_data = {
                    clicked_on: jQuery(star).attr('data-val'),
                    widget_id: jQuery(star).parent().attr('id')
                };
                widget_id_new = jQuery(this).parent().attr('id');

                current_frm.find('#field_' + widget_id_new).val(clicked_data.clicked_on);
                current_frm.find('#field_' + widget_id_new).trigger('click');
                set_votes(widget, widget_id_new);
            });

        });
        //for star rating

        //for like button
        jQuery('.arf_like_btn, .arf_dislike_btn').not('.field_edit').on("click", function () {
            var field = jQuery(this).attr('id');
            var field = field.replace('like_', 'field_');
            if (!jQuery("#" + field).is(':checked')) {
                jQuery("#" + jQuery(this).attr("for")).trigger('click').trigger('change');
            }
        });

        jQuery('.arf_form .arf_like').on("click", function () {
            var field = jQuery(this).attr('id');
            field_data = field.split('-');

            var field_id = field_data[0];
            field_id = field_id.replace('field_', '');
            field_id = 'like_' + field_id;
            var like = field_data[1];

            if (like == 1) {
                jQuery('#' + field_id + '-0').removeClass('active');
                jQuery('#' + field_id + '-1').addClass('active');
            } else if (like == 0) {
                jQuery('#' + field_id + '-1').removeClass('active');
                jQuery('#' + field_id + '-0').addClass('active');
            }
        });
        jQuery('.arf_like_btn, .arf_dislike_btn').each(function () {
            var title = jQuery(this).attr('data-title');
            if (title !== undefined) {
                jQuery(this).popover({
                    html: true,
                    trigger: 'hover',
                    placement: 'top',
                    content: title,
                    title: '',
                    animation: false
                });
            }
        });
        //for like button end

        arf_change_modal_slider(jQuery('.arf_pop_' + popup_data_uniq_id).find('#form_' + form_key));		//for modal slider

    }

    if (screenwidth <= 770)
    {
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("height", 'auto');

        var windowHeight = jQuery(window).height() - Number(60);
        var actualheight = jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find('.arf_fieldset').height();

        if (actualheight < windowHeight) {
            jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("height", windowHeight + "px");
        }

        if (screenwidth < width) {
             if (height == '' ||  height == 'auto') {
                jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('position', 'fixed');
                jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arfmodal-body').css('overflow', 'auto');
            }
        }
    }

}

function open_modal_box_fly_left_move(id, height, width, popup_data_uniq_id)
{

    var modalwidth = jQuery(window).width();
    modalwidth = Number(modalwidth) + Number(20);

    var tmp_width = Number(modalwidth) - Number(width);
    var total_width = Number(tmp_width / 2) - Number(50);

    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).removeClass('in');

    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('position', '');

    var screenwidth = jQuery(window).width();
    if (screenwidth <= 770)
    {
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).hide();
    }
    else
    {


        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).animate({
            'left': total_width + 'px',
        }, 200, function () {
        });

        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).animate({
            'left': modalwidth + 'px',
        }, 500, function () {
        });
    }
    jQuery('.arf_popup_' + popup_data_uniq_id).find('.arform_side_block_right_' + id).show(800);
    jQuery('.arf_popup_' + popup_data_uniq_id).find('#open_modal_box_fly_right_' + id).show();
}

function open_modal_box_fly_right(id, height, width, checkradio_style_prop, checked_checkbox_prop, checked_radio_prop, popup_data_uniq_id)
{
    var arf_body_class = jQuery('body').attr('class').split(' ');
    var body_class = '';
    for (var i = 0; i < arf_body_class.length; i++) {
        body_class = arf_body_class[i].split('arforms_model_fly_right_view_');
        if(body_class[1] != 'undefined' && jQuery.isNumeric(body_class[1])){
            jQuery('body').removeClass(arf_body_class[i]);
        }
    }
    jQuery('body').addClass("arforms_model_fly_right_view_"+id);
    
    
    jQuery(document).ready(function ($)
    {
        jQuery(".vpb_captcha").keypress(function () {
            jQuery(this).parents('.arfformfield').find('.popover').remove();
            jQuery(this).removeClass('control-group');
            jQuery(this).removeClass('arf_error');
        });
    });

    var admin_ajax_url = jQuery('.arf_pop_' + popup_data_uniq_id).find('#admin_ajax_url').val();
    admin_ajax_url = is_ssl_replace(admin_ajax_url);
    jQuery.ajax({type: "POST", url: admin_ajax_url, data: "action=current_modal&position_modal=arf_modal_right",
        success: function (errObj) {
        }
    });


    var screenheight = jQuery(window).height();
    var screenwidth = jQuery(window).width();
    var modal_body_height = Number(height);
    var checkstep = 0;

    if (height >= screenheight)
    {
        var tmp_height = Number(height) - Number(screenheight);
        checkstep = 1;
    }
    else
    {
        var tmp_height = Number(screenheight) - Number(height);
        checkstep = 0;
    }

    if (checkstep != 0)
    {
        var total_height = 20;
        modal_body_height = screenheight - 59;
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('max-height', (screenheight - 40) + 'px');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('height', (screenheight - 40) + 'px');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arfmodal-body').css('height', modal_body_height + 'px');
    }
    else
    {
        var total_height = Number(tmp_height / 2);
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('max-height', modal_body_height + 'px');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('height', modal_body_height + 'px');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arfmodal-body').css('height', (modal_body_height - 19) + 'px');
    }

    var tmp_width = Number(screenwidth) - Number(width);
    var total_width = Number(tmp_width / 2);

    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('left', screenwidth);
    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('top', total_height);

    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('data-mtop', total_height);
    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('data-mleft', total_width);


    /*19-06-2015 */
    var scroll_top = jQuery(window).scrollTop();
     if (height == '' ||  height == 'auto') {

        total_height = scroll_top + 20;
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css({opacity: "0", display: "block"});
        var actualheight = jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find('.arf_fieldset').outerHeight();
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css({opacity: '', display: ""});

        if (screenheight > actualheight) {
            var total_height = (screenheight - actualheight) / 2;
        }
        //jQuery('#popup-form-' + id).css('top', (scroll_top + 20) + 'px');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('height', 'auto');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arfmodal-body').css('height', 'auto');
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('position', 'absolute');

        
        var isSafari = !!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/)
        if (!isSafari){
          jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arfmodal-body').css('overflow','visible');
        } 
        
    }
    /*19-06-2015  */

    /*01-08-2015*/
        jQuery('.arf_pop_' + popup_data_uniq_id).find('.arf_form.ar_main_div_' + id).css('max-width',width+'px');
    /*01-08-2015*/


    jQuery('.arf_popup_' + popup_data_uniq_id).find('.arform_side_block_right_' + id).hide();
    if (jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).hasClass('in'))
    {
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).removeClass('in');
        jQuery('.arf_popup_' + popup_data_uniq_id).find('.arform_side_block').show();
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).animate({
            'right': total_width + 'px',
            'bottom': total_height + 'px',
            'top': total_height + 'px',
            'left': total_width + 'px',
        }, 500);
    }
    else
    {
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).addClass('in');

        var form_key = jQuery('.arf_pop_' + popup_data_uniq_id).find('#form_key_' + id).val();
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find('.arfmodal-body #form_' + form_key).show();
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find('.arfmodal-body .arf_content_another_page').empty().hide();

        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arform_sb_fx_form').css('display', 'block');
        jQuery('.arf_popup_' + popup_data_uniq_id).find('.arform_side_block').hide();
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).animate({
            'right': total_width + 'px',
            'bottom': total_height + 'px',
            'top': total_height + 'px',
            'left': total_width + 'px',
        }, 500);
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('display', 'block');

        var data_open = jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).attr('data-open');
        data_open = (data_open !== undefined) ? data_open : true;
        if (checkradio_style_prop != "" && data_open == true)
        {
            if (jQuery.isFunction(jQuery().iCheck))
            {
                if(checkradio_style_prop == 'custom') {
                jQuery('.arf_pop_' + popup_data_uniq_id).find('#arffrm_' + id + '_container input').not('.arf_hide_opacity').iCheck({
                    checkboxClass: 'icheckbox_' + checkradio_style_prop,
                    radioClass: 'iradio_' + checkradio_style_prop,
                        hoverClass: ' ',
                        checkedCheckboxClass: checked_checkbox_prop,
                        checkedRadioClass: checked_radio_prop
                });
                } else {
                    jQuery('.arf_pop_' + popup_data_uniq_id).find('#arffrm_' + id + '_container input').not('.arf_hide_opacity').iCheck({
                        checkboxClass: 'icheckbox_' + checkradio_style_prop,
                        radioClass: 'iradio_' + checkradio_style_prop,
                        increaseArea: '25%'
                    });
                }
            }
            jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).attr('data-open', 'false');
        }

        // for colorpicker
        if (data_open == true)
        {
            arfmodalcolorpicker(id);
        }

        // for file upload
        var arfmainformurl = jQuery('.arf_pop_' + popup_data_uniq_id).find('#arfmainformurl').val();
        arfmainformurl = is_ssl_replace(arfmainformurl);
        var url = arfmainformurl + '/js/filedrag/filedrag_front.js';
        var submit_type = jQuery('.arf_pop_' + popup_data_uniq_id).find('#form_submit_type').val();
        if (submit_type == 1) {
            jQuery.getScript(url);
        }
        jQuery('.arf_pop_' + popup_data_uniq_id).find('.original_normal').on('change', function (e) {
            var id = jQuery(this).attr('id');
            id = id.replace('field_', '');

            var fileName = jQuery(this).val();
            fileName = fileName.replace(/C:\\fakepath\\/i, '');
            if (fileName != '') {
                jQuery('.arf_pop_' + popup_data_uniq_id).find('#file_name_' + id).html(fileName);
            }
        });
        // for file upload end
        //for star rating
        jQuery('.rate_widget').each(function (i) {

            widget_id = jQuery(this).attr('id');
            var rate_data_id = jQuery(this).parents('form').attr('data-id');    
            var current_frm = jQuery("form.arfshowmainform[data-id='"+rate_data_id+"']");

            current_frm.find('.ratings_stars').hover(
                    function () {
                        var color = jQuery(this).attr('data-color');
                        var datasize = jQuery(this).attr('data-size');
                        jQuery(this).prevAll().andSelf().addClass('ratings_over_' + color + datasize);
                        jQuery(this).nextAll().removeClass('ratings_vote_' + color + datasize);
                    },
                    function () {
                        var color = jQuery(this).attr('data-color');
                        var datasize = jQuery(this).attr('data-size');
                        jQuery(this).prevAll().andSelf().removeClass('ratings_over_' + color + datasize);
                        widget_id_new = jQuery(this).parent().attr('id');
                        set_votes(jQuery(this).parent(), widget_id_new);
                    }
            );

            current_frm.find('.ratings_stars').bind('click', function () {
                var star = this;
                var widget = jQuery(this).parent();

                var clicked_data = {
                    clicked_on: jQuery(star).attr('data-val'),
                    widget_id: jQuery(star).parent().attr('id')
                };
                widget_id_new = jQuery(this).parent().attr('id');

                current_frm.find('#field_' + widget_id_new).val(clicked_data.clicked_on);
                current_frm.find('#field_' + widget_id_new).trigger('click');
                set_votes(widget, widget_id_new);
            });

        });
        //for star rating

        //for like button
        jQuery('.arf_like_btn, .arf_dislike_btn').not('.field_edit').on("click", function () {
            var field = jQuery(this).attr('id');
            var field = field.replace('like_', 'field_');
            if (!jQuery("#" + field).is(':checked')) {
                jQuery("#" + jQuery(this).attr("for")).trigger('click').trigger('change');
            }
        });

        jQuery('.arf_form .arf_like').on("click", function () {
            var field = jQuery(this).attr('id');
            field_data = field.split('-');

            var field_id = field_data[0];
            field_id = field_id.replace('field_', '');
            field_id = 'like_' + field_id;
            var like = field_data[1];

            if (like == 1) {
                jQuery('#' + field_id + '-0').removeClass('active');
                jQuery('#' + field_id + '-1').addClass('active');
            } else if (like == 0) {
                jQuery('#' + field_id + '-1').removeClass('active');
                jQuery('#' + field_id + '-0').addClass('active');
            }
        });
        jQuery('.arf_like_btn, .arf_dislike_btn').each(function () {
            var title = jQuery(this).attr('data-title');
            if (title !== undefined) {
                jQuery(this).popover({
                    html: true,
                    trigger: 'hover',
                    placement: 'top',
                    content: title,
                    title: '',
                    animation: false
                });
            }
        });
        //for like button end		

        arf_change_modal_slider(jQuery('.arf_pop_' + popup_data_uniq_id).find('#form_' + form_key));		//for modal slider
    }

    if (screenwidth <= 770)
    {
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("height", 'auto');

        var windowHeight = jQuery(window).height() - Number(60);
        var actualheight = jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find('.arf_fieldset').height();

        if (actualheight < windowHeight) {
            jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("height", windowHeight + "px");
        }

        if (screenwidth < width) {
             if (height == '' ||  height == 'auto') {
                jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('position', 'fixed');
                jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arfmodal-body').css('overflow', 'auto');
            }
        }

    }

}

function open_modal_box_fly_right_move(id, height, width, popup_data_uniq_id)
{

    var modalwidth = jQuery(window).width();
    modalwidth = Number(modalwidth) + Number(20);

    var tmp_width = Number(modalwidth) - Number(width);
    var total_width = Number(tmp_width / 2) + Number(50);

    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).removeClass('in');

    var screenwidth = jQuery(window).width();
    if (screenwidth <= 770)
    {
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).hide();
    }
    else
    {


        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).animate({
            'left': total_width + 'px',
        }, 200, function () {
        });

        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).animate({
            'left': -modalwidth + 'px',
            //'top': '100%',
        }, 800, function () {
        });
    }

    jQuery('.arf_popup_' + popup_data_uniq_id).find('.arform_side_block_left_' + id).show(800);
    jQuery('.arf_popup_' + popup_data_uniq_id).find('#open_modal_box_fly_right_' + id).show();
}

function open_modal_box_sitcky_bottom(id, height, width, checkradio_style_prop, checked_checkbox_prop, checked_radio_prop, popup_data_uniq_id)
{
    
    var arf_body_class = jQuery('body').attr('class').split(' ');
    var body_class = '';
    for (var i = 0; i < arf_body_class.length; i++) {
        body_class = arf_body_class[i].split('arforms_model_sitcky_bottom_view_');
        if(body_class[1] != 'undefined' && jQuery.isNumeric(body_class[1])){
            jQuery('body').removeClass(arf_body_class[i]);
        }
    }
    jQuery('body').addClass("arforms_model_sitcky_bottom_view_"+id);
    
    
    jQuery(document).ready(function ($)
    {
        jQuery(".vpb_captcha").keypress(function () {
            jQuery(this).parents('.arfformfield').find('.popover').remove();
            jQuery(this).removeClass('control-group');
            jQuery(this).removeClass('arf_error');
        });
    });

    var admin_ajax_url = jQuery('.arf_pop_' + popup_data_uniq_id).find('#admin_ajax_url').val();
    admin_ajax_url = is_ssl_replace(admin_ajax_url);
    jQuery.ajax({type: "POST", url: admin_ajax_url, data: "action=current_modal&position_modal=arf_modal_bottom",
        success: function (errObj) {
        }
    });
    var screenwidth = jQuery(window).width();
    
    var modal_body_height = Number(height) - 140;
    var screenheight = jQuery(window).height();
    var newheightformainmodal = 0;
    if (height >= screenheight)
    {
        modal_body_height = screenheight - 55;
        newheightformainmodal = screenheight - 55;
    }
    else
    {
        modal_body_height = Number(height) + 36;
        newheightformainmodal = Number(height);
    }
    
     /*11-08-2015*/
        if (height == 'auto') {
            setTimeout(function(){
                var total_popup_height = jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).height();
                if (total_popup_height > screenheight) {
                    var button_height = jQuery('.arf_popup_' + popup_data_uniq_id).find('.arform_modal_stickybottom_' + id).outerHeight();
                    modal_body_height = screenheight - button_height;
                    newheightformainmodal = screenheight - button_height;
                    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('max-height', (newheightformainmodal) + 'px');
                    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('height', (newheightformainmodal) + 'px');
                    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arfmodal-body').css('height', modal_body_height + 'px');
                }
            },499);
        }
    /*11-08-2015*/
    
    
    
    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('max-height', (newheightformainmodal) + 'px');
    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('height', (newheightformainmodal) + 'px');

    /*jQuery('#popup-form-'+id).css('data-mtop', total_height);
     jQuery('#popup-form-'+id).css('data-mleft', total_width);*/
    
    /*04-08-2015*/
        if(width >= screenwidth){
            jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('width',screenwidth+'px');
            jQuery('.arf_pop_' + popup_data_uniq_id).find('.arf_form.ar_main_div_' + id).css('max-width',screenwidth+'px');   
            jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).css('right','0');
        }else{
            jQuery('.arf_pop_' + popup_data_uniq_id).find('.arf_form.ar_main_div_' + id).css('max-width',width+'px');
        }
    /*04-08-2015*/
    
    

    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arfmodal-body').css('height', modal_body_height + 'px');
    if (jQuery('.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_block_bottom').hasClass('arform_bottom_fixed_block_open'))
    {
        jQuery('.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_block_bottom').removeClass('arform_bottom_fixed_block_open');
    }
    else
    {
        var form_key = jQuery('.arf_pop_' + popup_data_uniq_id).find('#form_key_' + id).val();
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find('.arfmodal-body #form_' + form_key).show();
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find('.arfmodal-body .arf_content_another_page').empty().hide();

        jQuery('.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_block_bottom').addClass('arform_bottom_fixed_block_open');

        var data_open = jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).attr('data-open');
        data_open = (data_open !== undefined) ? data_open : true;
        if (checkradio_style_prop != "" && data_open == true)
        {
            if (jQuery.isFunction(jQuery().iCheck))
            {
                if(checkradio_style_prop == 'custom') {
                jQuery('.arf_pop_' + popup_data_uniq_id).find('#arffrm_' + id + '_container input').not('.arf_hide_opacity').iCheck({
                    checkboxClass: 'icheckbox_' + checkradio_style_prop,
                    radioClass: 'iradio_' + checkradio_style_prop,
                        hoverClass: ' ',
                        checkedCheckboxClass: checked_checkbox_prop,
                        checkedRadioClass: checked_radio_prop
                });
                } else {
                    jQuery('.arf_pop_' + popup_data_uniq_id).find('#arffrm_' + id + '_container input').not('.arf_hide_opacity').iCheck({
                        checkboxClass: 'icheckbox_' + checkradio_style_prop,
                        radioClass: 'iradio_' + checkradio_style_prop,
                        increaseArea: '25%'
                    });
                }
            }
            jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).attr('data-open', 'false');
        }

        // for colorpicker
        if (data_open == true)
        {
            arfmodalcolorpicker(id);
        }

        // for file upload
        var arfmainformurl = jQuery('.arf_pop_' + popup_data_uniq_id).find('#arfmainformurl').val();
        arfmainformurl = is_ssl_replace(arfmainformurl);
        var url = arfmainformurl + '/js/filedrag/filedrag_front.js';
        var submit_type = jQuery('.arf_pop_' + popup_data_uniq_id).find('#form_submit_type').val();
        if (submit_type == 1) {
            jQuery.getScript(url);
        }
        jQuery('.arf_pop_' + popup_data_uniq_id).find('.original_normal').on('change', function (e) {
            var id = jQuery(this).attr('id');
            id = id.replace('field_', '');

            var fileName = jQuery(this).val();
            fileName = fileName.replace(/C:\\fakepath\\/i, '');
            if (fileName != '') {
                jQuery('.arf_pop_' + popup_data_uniq_id).find('#file_name_' + id).html(fileName);
            }
        });
        // for file upload end
        //for star rating
        jQuery('.rate_widget').each(function (i) {

            widget_id = jQuery(this).attr('id');
            var rate_data_id = jQuery(this).parents('form').attr('data-id');    
            var current_frm = jQuery("form.arfshowmainform[data-id='"+rate_data_id+"']");

            current_frm.find('.ratings_stars').hover(
                    function () {
                        var color = jQuery(this).attr('data-color');
                        var datasize = jQuery(this).attr('data-size');
                        jQuery(this).prevAll().andSelf().addClass('ratings_over_' + color + datasize);
                        jQuery(this).nextAll().removeClass('ratings_vote_' + color + datasize);
                    },
                    function () {
                        var color = jQuery(this).attr('data-color');
                        var datasize = jQuery(this).attr('data-size');
                        jQuery(this).prevAll().andSelf().removeClass('ratings_over_' + color + datasize);
                        widget_id_new = jQuery(this).parent().attr('id');
                        set_votes(jQuery(this).parent(), widget_id_new);
                    }
            );

            current_frm.find('.ratings_stars').bind('click', function () {
                var star = this;
                var widget = jQuery(this).parent();

                var clicked_data = {
                    clicked_on: jQuery(star).attr('data-val'),
                    widget_id: jQuery(star).parent().attr('id')
                };
                widget_id_new = jQuery(this).parent().attr('id');

                current_frm.find('#field_' + widget_id_new).val(clicked_data.clicked_on);
                current_frm.find('#field_' + widget_id_new).trigger('click');
                set_votes(widget, widget_id_new);
            });

        });
        //for star rating

        //for like button
        jQuery('.arf_like_btn, .arf_dislike_btn').not('.field_edit').on("click", function () {
            var field = jQuery(this).attr('id');
            var field = field.replace('like_', 'field_');
            if (!jQuery("#" + field).is(':checked')) {
                jQuery("#" + jQuery(this).attr("for")).trigger('click').trigger('change');
            }
        });

        jQuery('.arf_form .arf_like').on("click", function () {
            var field = jQuery(this).attr('id');
            field_data = field.split('-');

            var field_id = field_data[0];
            field_id = field_id.replace('field_', '');
            field_id = 'like_' + field_id;
            var like = field_data[1];

            if (like == 1) {
                jQuery('#' + field_id + '-0').removeClass('active');
                jQuery('#' + field_id + '-1').addClass('active');
            } else if (like == 0) {
                jQuery('#' + field_id + '-1').removeClass('active');
                jQuery('#' + field_id + '-0').addClass('active');
            }
        });
        jQuery('.arf_like_btn, .arf_dislike_btn').each(function () {
            var title = jQuery(this).attr('data-title');
            if (title !== undefined) {
                jQuery(this).popover({
                    html: true,
                    trigger: 'hover',
                    placement: 'top',
                    content: title,
                    title: '',
                    animation: false
                });
            }
        });
        //for like button end

        arf_change_modal_slider(jQuery('.arf_pop_' + popup_data_uniq_id).find('#form_' + form_key));		//for modal slider
    }
    
//    jQuery('.arform_bottom_fixed_block_bottom').parents('.arform_bottom_fixed_main_block_bottom').find('.arform_bottom_fixed_form_block_bottom_main').slideToggle("500");

    jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_form_block_bottom_main').slideToggle('500');
}

function open_modal_box_sitcky_top(id, height, width, checkradio_style_prop, checked_checkbox_prop, checked_radio_prop, popup_data_uniq_id)
{
    
    var arf_body_class = jQuery('body').attr('class').split(' ');
    var body_class = '';
    for (var i = 0; i < arf_body_class.length; i++) {
        body_class = arf_body_class[i].split('arforms_model_sitcky_top_view_');
        if(body_class[1] != 'undefined' && jQuery.isNumeric(body_class[1])){
            jQuery('body').removeClass(arf_body_class[i]);
        }
    }
    jQuery('body').addClass("arforms_model_sitcky_top_view_"+id);
    
    
    jQuery(document).ready(function ($)
    {
        jQuery(".vpb_captcha").keypress(function () {
            jQuery(this).parents('.arfformfield').find('.popover').remove();
            jQuery(this).removeClass('control-group');
            jQuery(this).removeClass('arf_error');
        });
    });

    var screenwidth = jQuery(window).width();
    var modal_body_height = Number(height) - 140;
    var screenheight = jQuery(window).height();
    var newheightformainmodal = 0;
    if (height >= screenheight)
    {
        modal_body_height = screenheight - 55;
        newheightformainmodal = screenheight - 55;
    }
    else
    {
        modal_body_height = Number(height) + 36 - 26;
        newheightformainmodal = Number(height); // + 10;
    }
    
    /*11-08-2015*/
        if (height == 'auto') {
            setTimeout(function(){
                var total_popup_height = jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).height();
                if (total_popup_height > screenheight) {
                    var button_height = jQuery('.arf_popup_' + popup_data_uniq_id).find('.arform_modal_stickytop_' + id).outerHeight();
                    modal_body_height = screenheight - button_height;
                    newheightformainmodal = screenheight - button_height;
                    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('max-height', (newheightformainmodal) + 'px');
                    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('height', (newheightformainmodal) + 'px');
                    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arfmodal-body').css('height', modal_body_height + 'px');
                }
            },499);
        }
    /*11-08-2015*/
    
    
    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('max-height', (newheightformainmodal) + 'px');
    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('height', (newheightformainmodal) + 'px');
    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('zIndex', '9998');
    jQuery('.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_block_top').css('zIndex', '9999');
    jQuery('.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_block_top').css('position', 'relative');

    /*jQuery('#popup-form-'+id).css('data-mtop', total_height);
     jQuery('#popup-form-'+id).css('data-mleft', total_width);*/
   

    
    /*04-08-2015*/
        if(width >= screenwidth){
            jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('width',screenwidth+'px');
            jQuery('.arf_pop_' + popup_data_uniq_id).find('.arf_form.ar_main_div_' + id).css('max-width',screenwidth+'px');   
            jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).css('right','0');
        }else{
            jQuery('.arf_pop_' + popup_data_uniq_id).find('.arf_form.ar_main_div_' + id).css('max-width',width+'px');
        }
    /*04-08-2015*/

    


    var applycheckstyleprop = "";
    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arfmodal-body').css('height', modal_body_height + 'px');
    if (jQuery('.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_block_top').hasClass('arform_bottom_fixed_block_open'))
    {
        jQuery('.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_block_top').removeClass('arform_bottom_fixed_block_open');
    }
    else
    {
        var form_key = jQuery('#form_key_' + id).val();
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find('.arfmodal-body #form_' + form_key).show();
        jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find('.arfmodal-body .arf_content_another_page').empty().hide();

        jQuery('.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_block_top').addClass('arform_bottom_fixed_block_open');
        // for file upload
        var arfmainformurl = jQuery('.arf_pop_' + popup_data_uniq_id).find('#arfmainformurl').val();
        arfmainformurl = is_ssl_replace(arfmainformurl);
        var url = arfmainformurl + '/js/filedrag/filedrag_front.js';
        var submit_type = jQuery('.arf_pop_' + popup_data_uniq_id).find('#form_submit_type').val();
        if (submit_type == 1) {
            jQuery.getScript(url);
        }

        jQuery('.arf_pop_' + popup_data_uniq_id).find('.original_normal').on('change', function (e) {
            var id = jQuery(this).attr('id');
            id = id.replace('field_', '');

            var fileName = jQuery(this).val();
            fileName = fileName.replace(/C:\\fakepath\\/i, '');
            if (fileName != '') {
                jQuery('.arf_pop_' + popup_data_uniq_id).find('#file_name_' + id).html(fileName);
            }
        });
        // for file upload end	
        //for star rating
        jQuery('.rate_widget').each(function (i) {

            widget_id = jQuery(this).attr('id');
            var rate_data_id = jQuery(this).parents('form').attr('data-id');    
            var current_frm = jQuery("form.arfshowmainform[data-id='"+rate_data_id+"']");

            current_frm.find('.ratings_stars').hover(
                    function () {
                        var color = jQuery(this).attr('data-color');
                        var datasize = jQuery(this).attr('data-size');
                        jQuery(this).prevAll().andSelf().addClass('ratings_over_' + color + datasize);
                        jQuery(this).nextAll().removeClass('ratings_vote_' + color + datasize);
                    },
                    function () {
                        var color = jQuery(this).attr('data-color');
                        var datasize = jQuery(this).attr('data-size');
                        jQuery(this).prevAll().andSelf().removeClass('ratings_over_' + color + datasize);
                        widget_id_new = jQuery(this).parent().attr('id');
                        set_votes(jQuery(this).parent(), widget_id_new);
                    }
            );

            current_frm.find('.ratings_stars').bind('click', function () {
                var star = this;
                var widget = jQuery(this).parent();

                var clicked_data = {
                    clicked_on: jQuery(star).attr('data-val'),
                    widget_id: jQuery(star).parent().attr('id')
                };
                widget_id_new = jQuery(this).parent().attr('id');

                current_frm.find('#field_' + widget_id_new).val(clicked_data.clicked_on);
                current_frm.find('#field_' + widget_id_new).trigger('click');
                set_votes(widget, widget_id_new);
            });

        });
        applycheckstyleprop = 1;
        //for star rating

        //for like button
        jQuery('.arf_like_btn, .arf_dislike_btn').not('.field_edit').on("click", function () {
            var field = jQuery(this).attr('id');
            var field = field.replace('like_', 'field_');
            if (!jQuery("#" + field).is(':checked')) {
                jQuery("#" + jQuery(this).attr("for")).trigger('click').trigger('change');
            }
        });

        jQuery('.arf_form .arf_like').on("click", function () {
            var field = jQuery(this).attr('id');
            field_data = field.split('-');

            var field_id = field_data[0];
            field_id = field_id.replace('field_', '');
            field_id = 'like_' + field_id;
            var like = field_data[1];

            if (like == 1) {
                jQuery('#' + field_id + '-0').removeClass('active');
                jQuery('#' + field_id + '-1').addClass('active');
            } else if (like == 0) {
                jQuery('#' + field_id + '-1').removeClass('active');
                jQuery('#' + field_id + '-0').addClass('active');
            }
        });
        jQuery('.arf_like_btn, .arf_dislike_btn').each(function () {
            var title = jQuery(this).attr('data-title');
            if (title !== undefined) {
                jQuery(this).popover({
                    html: true,
                    trigger: 'hover',
                    placement: 'top',
                    content: title,
                    title: '',
                    animation: false
                });
            }
        });
        //for like button end

        arf_change_modal_slider(jQuery('.arf_pop_' + popup_data_uniq_id).find('#form_' + form_key));		//for modal slider

    }
//    jQuery('.arform_bottom_fixed_block_top').parents('.arform_bottom_fixed_main_block_top').find('.arform_bottom_fixed_form_block_top_main').slideToggle("500");
    
	jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_form_block_top_main').slideToggle("500");
    
    if (applycheckstyleprop == 1)
    {
        var data_open = jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).attr('data-open');
        data_open = (data_open !== undefined) ? data_open : true;

        if (checkradio_style_prop != "" && data_open == true)
        {
            if (jQuery.isFunction(jQuery().iCheck))
            {
                if(checkradio_style_prop == 'custom') {
                jQuery('.arf_pop_' + popup_data_uniq_id).find('#arffrm_' + id + '_container input').not('.arf_hide_opacity').iCheck({
                    checkboxClass: 'icheckbox_' + checkradio_style_prop,
                    radioClass: 'iradio_' + checkradio_style_prop,
                        hoverClass: ' ',
                        checkedCheckboxClass: checked_checkbox_prop,
                        checkedRadioClass: checked_radio_prop
                });
                } else {
                    jQuery('.arf_pop_' + popup_data_uniq_id).find('#arffrm_' + id + '_container input').not('.arf_hide_opacity').iCheck({
                        checkboxClass: 'icheckbox_' + checkradio_style_prop,
                        radioClass: 'iradio_' + checkradio_style_prop,
                        increaseArea: '30%'
                    });
                }
            }
            jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).attr('data-open', 'false');
        }

        // for colorpicker
        if (data_open == true)
        {
            arfmodalcolorpicker(id);
        }

    }
}

function arfmodalcolorpicker(id)
{
    if (!id) {
        return;
    }

    jQuery('.arfhiddencolor').on('focus', function () {
        jQuery(this).parents('.arf_colorpicker_control').first().find('.arf_colorpicker, .arf_basic_colorpicker').first().trigger('click');
    });

    if (jQuery.isFunction(jQuery().colpick))
    {
        jQuery("form.arfshowmainform").each(function(){
            var pcolor_data_id = jQuery(this).attr('data-id');
            var pcolor_curr_form = jQuery("form.arfshowmainform[data-id='" + pcolor_data_id + "']");
            pcolor_curr_form.find('.arf_colorpicker').colpick({
                layout: 'hex',
                submit: 1,
                color: 'ffffff',
                onBeforeShow: function () {
                    var fid = jQuery(this).attr('id');
                    var fid = fid.replace('arfcolorpicker_', '');
                    var color = pcolor_curr_form.find('#field_' + fid).val();
                    var new_color = color.replace('#', '');
                    if (new_color) {
                        jQuery(this).colpickSetColor(new_color);
                    }
                },
                onChange: function (hsb, hex, rgb, el, bySetColor) {
                    var field_key = jQuery(el).attr('id');
                    field_key = field_key.replace('arfcolorpicker_', '');
                    pcolor_curr_form.find('#field_' + field_key).val('#' + hex).trigger('change');
                    jQuery(el).find('.arfcolorvalue').text('#' + hex);
                    jQuery(el).find('.arfcolorvalue').css('background', '#' + hex);
                    var arffontcolor = HextoHsl(hex) > 0.5 ? '#000000' : '#ffffff';
                    jQuery(el).find('.arfcolorvalue').css('color', arffontcolor);
                },
                onSubmit: function () {
                    pcolor_curr_form.find('.arf_colorpicker').colpickHide();
                }
            });
        });
    }

    if (jQuery.isFunction(jQuery().simpleColorPicker))
    {
        jQuery("form.arfshowmainform").each(function(){
            var pscolor_data_id = jQuery(this).attr('data-id');
            var pscolor_curr_form = jQuery("form.arfshowmainform[data-id='" + pscolor_data_id + "']");
            pscolor_curr_form.find('.arf_basic_colorpicker').simpleColorPicker({
                onChangeColor: function (color) {
                    var field_key = jQuery(this).attr('id');
                    field_key = field_key.replace('arfcolorpicker_', '');
                    pscolor_curr_form.find('#field_' + field_key).val(color).trigger('change');
                    jQuery(this).find('.arfcolorvalue').text(color);
                    jQuery(this).find('.arfcolorvalue').css('background', color);
                    var hex = color.replace('#', '');
                    var arffontcolor = HextoHsl(hex) > 0.5 ? '#000000' : '#ffffff';
                    if (hex == "ffff00")
                    {
                        arffontcolor = "#000000";
                    }
                    jQuery(this).find('.arfcolorvalue').css('color', arffontcolor);
                }
            });
        });
    }

    jQuery('#popup-form-' + id).attr('data-open', 'false');
}




/**02-02-2015**/
function open_modal_box_sitcky_left(id, height, width, checkradio_style_prop, checked_checkbox_prop, checked_radio_prop, popup_data_uniq_id)
{
    
    var arf_body_class = jQuery('body').attr('class').split(' ');
    var body_class = '';
    for (var i = 0; i < arf_body_class.length; i++) {
        body_class = arf_body_class[i].split('arforms_model_sitcky_left_view_');
        if(body_class[1] != 'undefined' && jQuery.isNumeric(body_class[1])){
            jQuery('body').removeClass(arf_body_class[i]);
        }
    }
    jQuery('body').addClass("arforms_model_sitcky_left_view_"+id);
    
    
    jQuery('.arf_pop_' + popup_data_uniq_id).find('#arf_sitcky_close_btn').remove();
    jQuery(document).ready(function ($)
    {
        jQuery(".vpb_captcha").keypress(function () {
            jQuery(this).parents('.arfformfield').find('.popover').remove();
            jQuery(this).removeClass('control-group');
            jQuery(this).removeClass('arf_error');
        });

    });

    var admin_ajax_url = jQuery('.arf_pop_' + popup_data_uniq_id).find('#admin_ajax_url').val();
    admin_ajax_url = is_ssl_replace(admin_ajax_url);
    jQuery.ajax({type: "POST", url: admin_ajax_url, data: "action=current_modal&position_modal=arf_modal_sitcky_left",
        success: function (errObj) {
        }
    });


    var screenwidth = jQuery(window).width();
    
    var modal_body_height = Number(height) - 140;
    var screenheight = jQuery(window).height();
    var newheightformainmodal = 0;
    if (height >= screenheight)
    {
        modal_body_height = screenheight - 55;
        newheightformainmodal = screenheight - 55;
    }
    else
    {
        modal_body_height = Number(height);
        newheightformainmodal = Number(height);
    }
    
    
    /*04-08-2015*/
        var modal_body_width = width;
        if(width >= screenwidth){
            modal_body_width = screenwidth;
            jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('width',screenwidth+'px');
            jQuery('.arf_pop_' + popup_data_uniq_id).find('.arf_form.ar_main_div_' + id).css('max-width',screenwidth+'px');
            var close_btn = '<button id="arf_sitcky_close_btn" onclick="open_modal_box_sitcky_left('+"'"+id+"'"+', '+"'"+height+"'"+', '+"'"+width+"'"+', '+"'"+checkradio_style_prop+"'"+', '+"'"+checked_checkbox_prop+"'"+', '+"'"+checked_radio_prop+"'"+', '+"'"+popup_data_uniq_id+"'"+');" style="z-index:99999; cursor:pointer; margin:5px 5px 0;" type="button" class="close" data-dismiss="arfmodal">x</button>';
            jQuery('.arf_pop_' + popup_data_uniq_id).find('.arf_form.ar_main_div_' + id).prepend(close_btn);
        }else{
            modal_body_width = width;
            jQuery('.arf_pop_' + popup_data_uniq_id).find('#arf_sitcky_close_btn').remove();
            jQuery('.arf_pop_' + popup_data_uniq_id).find('.arf_form.ar_main_div_' + id).css('max-width',width+'px');
            
        }
        
    /*04-08-2015*/
    
    
    /*11-08-2015*/
    if(height == 'auto'){
        var total_popup_height = jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).height();
        if(total_popup_height > screenheight){
            modal_body_height = screenheight - (screenheight *10 /100);
            newheightformainmodal = screenheight - (screenheight *10 /100);
        }
    }
    /*11-08-2015*/

    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('max-height', (newheightformainmodal) + 'px');
    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('height', (newheightformainmodal) + 'px');

    /*jQuery('#popup-form-'+id).css('data-mtop', total_height);
     jQuery('#popup-form-'+id).css('data-mleft', total_width);*/



    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arfmodal-body').css('height', modal_body_height + 'px');



    if (jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_block_left').hasClass('arform_bottom_fixed_block_open'))
    {
        jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_block_left').removeClass('arform_bottom_fixed_block_open');
        //jQuery('.arform_bottom_fixed_block_left').css('margin-left','0');
        jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_block_left').animate({'margin-left': '0'}, 500);
        
        //jQuery('.arform_bottom_fixed_form_block_left_main').animate({'margin-left': '-' + width + 'px'}, 500);
        jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_form_block_left_main').animate({'margin-left': '-' + modal_body_width + 'px'}, 500);
        
        
    }
    else
    {
        jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_form_block_left_main').animate({'margin-left': '0'}, 500);

        //jQuery('.arform_bottom_fixed_block_left').css('margin-left',width+'px');
        //jQuery('.arform_bottom_fixed_block_left').animate({'margin-left': width + 'px'}, 500);
        jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_block_left').animate({'margin-left': modal_body_width + 'px'}, 500);


        var form_key = jQuery('.arf_pop_' + popup_data_uniq_id).find('#form_key_' + id).val();
        jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).find('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find('.arfmodal-body #form_' + form_key).show();
        jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).find('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find('.arfmodal-body .arf_content_another_page').empty().hide();

        jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_block_left').addClass('arform_bottom_fixed_block_open');

        var data_open = jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).attr('data-open');
        data_open = (data_open !== undefined) ? data_open : true;
        if (checkradio_style_prop != "" && data_open == true)
        {
            if (jQuery.isFunction(jQuery().iCheck))
            {
                if(checkradio_style_prop == 'custom') {
                jQuery('.arf_pop_' + popup_data_uniq_id).find('#arffrm_' + id + '_container input').not('.arf_hide_opacity').iCheck({
                    checkboxClass: 'icheckbox_' + checkradio_style_prop,
                    radioClass: 'iradio_' + checkradio_style_prop,
                        hoverClass: ' ',
                        checkedCheckboxClass: checked_checkbox_prop,
                        checkedRadioClass: checked_radio_prop
                });
                } else {
                    jQuery('.arf_pop_' + popup_data_uniq_id).find('#arffrm_' + id + '_container input').not('.arf_hide_opacity').iCheck({
                        checkboxClass: 'icheckbox_' + checkradio_style_prop,
                        radioClass: 'iradio_' + checkradio_style_prop,
                        increaseArea: '25%'
                    });
                }
            }
            jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).attr('data-open', 'false');
        }

        // for colorpicker
        if (data_open == true)
        {
            arfmodalcolorpicker(id);
        }

        // for file upload
        var arfmainformurl = jQuery('.arf_pop_' + popup_data_uniq_id).find('#arfmainformurl').val();
        arfmainformurl = is_ssl_replace(arfmainformurl);
        var url = arfmainformurl + '/js/filedrag/filedrag_front.js';
        var submit_type = jQuery('.arf_pop_' + popup_data_uniq_id).find('#form_submit_type').val();
        if (submit_type == 1) {
            jQuery.getScript(url);
        }
        jQuery('.arf_pop_' + popup_data_uniq_id).find('.original_normal').on('change', function (e) {
            var id = jQuery(this).attr('id');
            id = id.replace('field_', '');

            var fileName = jQuery(this).val();
            fileName = fileName.replace(/C:\\fakepath\\/i, '');
            if (fileName != '') {
                jQuery('.arf_pop_' + popup_data_uniq_id).find('#file_name_' + id).html(fileName);
            }
        });
        // for file upload end
        //for star rating
        jQuery('.rate_widget').each(function (i) {

            widget_id = jQuery(this).attr('id');
            var rate_data_id = jQuery(this).parents('form').attr('data-id');    
            var current_frm = jQuery("form.arfshowmainform[data-id='"+rate_data_id+"']");

            current_frm.find('.ratings_stars').hover(
                    function () {
                        var color = jQuery(this).attr('data-color');
                        var datasize = jQuery(this).attr('data-size');
                        jQuery(this).prevAll().andSelf().addClass('ratings_over_' + color + datasize);
                        jQuery(this).nextAll().removeClass('ratings_vote_' + color + datasize);
                    },
                    function () {
                        var color = jQuery(this).attr('data-color');
                        var datasize = jQuery(this).attr('data-size');
                        jQuery(this).prevAll().andSelf().removeClass('ratings_over_' + color + datasize);
                        widget_id_new = jQuery(this).parent().attr('id');
                        set_votes(jQuery(this).parent(), widget_id_new);
                    }
            );

            current_frm.find('.ratings_stars').bind('click', function () {
                var star = this;
                var widget = jQuery(this).parent();

                var clicked_data = {
                    clicked_on: jQuery(star).attr('data-val'),
                    widget_id: jQuery(star).parent().attr('id')
                };
                widget_id_new = jQuery(this).parent().attr('id');

                current_frm.find('#field_' + widget_id_new).val(clicked_data.clicked_on);
                current_frm.find('#field_' + widget_id_new).trigger('click');
                set_votes(widget, widget_id_new);
            });

        });
        //for star rating

        //for like button
        jQuery('.arf_like_btn, .arf_dislike_btn').not('.field_edit').on("click", function () {
            var field = jQuery(this).attr('id');
            var field = field.replace('like_', 'field_');
            if (!jQuery("#" + field).is(':checked')) {
                jQuery("#" + jQuery(this).attr("for")).trigger('click').trigger('change');
            }
        });

        jQuery('.arf_form .arf_like').on("click", function () {
            var field = jQuery(this).attr('id');
            field_data = field.split('-');

            var field_id = field_data[0];
            field_id = field_id.replace('field_', '');
            field_id = 'like_' + field_id;
            var like = field_data[1];

            if (like == 1) {
                jQuery('#' + field_id + '-0').removeClass('active');
                jQuery('#' + field_id + '-1').addClass('active');
            } else if (like == 0) {
                jQuery('#' + field_id + '-1').removeClass('active');
                jQuery('#' + field_id + '-0').addClass('active');
            }
        });
        jQuery('.arf_like_btn, .arf_dislike_btn').each(function () {
            var title = jQuery(this).attr('data-title');
            if (title !== undefined) {
                jQuery(this).popover({
                    html: true,
                    trigger: 'hover',
                    placement: 'top',
                    content: title,
                    title: '',
                    animation: false
                });
            }
        });
        //for like button end

        arf_change_modal_slider(jQuery('.arf_pop_' + popup_data_uniq_id).find('#form_' + form_key));		//for modal slider

    }


    /*jQuery('.arform_bottom_fixed_form_block_left_main').css('margin-left','0');*/

    var options1 = {direction: 'left'};
    //jQuery('.arform_bottom_fixed_block_left').parents('.arform_bottom_fixed_main_block_left').find('.arform_bottom_fixed_form_block_left_main').show('slow');
    //jQuery('.arform_bottom_fixed_block_left').parents('.arform_bottom_fixed_main_block_left').find('.arform_bottom_fixed_form_block_left_main').fadeIn();

    //jQuery('.arform_bottom_fixed_form_block_left_main').effect('slide', options1, 5000);

    //jQuery('.arform_bottom_fixed_form_block_left_main').toggle('slide', options1, 5000);

    //jQuery('.arform_bottom_fixed_form_block_left_main').animate({"left":"800px"}, "slow");

    //jQuery('.arform_bottom_fixed_block_left').parents('.arform_bottom_fixed_main_block_left').find('.arform_bottom_fixed_form_block_left_main').toggle('slide', options1, 5000);



    //jQuery('.arform_bottom_fixed_block_left').parents('.arform_bottom_fixed_main_block_left').find('.arform_bottom_fixed_form_block_left_main').toggle('slide', options1, 500);


}


function open_modal_box_sitcky_right(id, height, width, checkradio_style_prop, checked_checkbox_prop, checked_radio_prop, popup_data_uniq_id)
{
    
    var arf_body_class = jQuery('body').attr('class').split(' ');
    var body_class = '';
    for (var i = 0; i < arf_body_class.length; i++) {
        body_class = arf_body_class[i].split('arforms_model_sitcky_right_view_');
        if(body_class[1] != 'undefined' && jQuery.isNumeric(body_class[1])){
            jQuery('body').removeClass(arf_body_class[i]);
        }
    }
    jQuery('body').addClass("arforms_model_sitcky_right_view_"+id);
    
    
    
    jQuery('.arf_pop_' + popup_data_uniq_id).find('#arf_sitcky_close_btn').remove();
    
    jQuery(document).ready(function ($)
    {
        jQuery(".vpb_captcha").keypress(function () {
            jQuery(this).parents('.arfformfield').find('.popover').remove();
            jQuery(this).removeClass('control-group');
            jQuery(this).removeClass('arf_error');
        });
    });

    var admin_ajax_url = jQuery('.arf_pop_' + popup_data_uniq_id).find('#admin_ajax_url').val();
    admin_ajax_url = is_ssl_replace(admin_ajax_url);
    jQuery.ajax({type: "POST", url: admin_ajax_url, data: "action=current_modal&position_modal=arf_modal_bottom",
        success: function (errObj) {
        }
    });

    var screenwidth = jQuery(window).width();
    
    var modal_body_height = Number(height) - 140;
    var screenheight = jQuery(window).height();
    var newheightformainmodal = 0;
    if (height >= screenheight)
    {
        modal_body_height = screenheight - 55;
        newheightformainmodal = screenheight - 55;
    }
    else
    {
        modal_body_height = Number(height);
        newheightformainmodal = Number(height);
    }
    
    
    /*04-08-2015*/
        var modal_body_width = width;
        if(width >= screenwidth){
            modal_body_width = screenwidth;
            jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('width',screenwidth+'px');
            jQuery('.arf_pop_' + popup_data_uniq_id).find('.arf_form.ar_main_div_' + id).css('max-width',screenwidth+'px');   
            var close_btn = '<button id="arf_sitcky_close_btn" onclick="open_modal_box_sitcky_right('+"'"+id+"'"+', '+"'"+height+"'"+', '+"'"+width+"'"+', '+"'"+checkradio_style_prop+"'"+', '+"'"+checked_checkbox_prop+"'"+', '+"'"+checked_radio_prop+"'"+', '+"'"+popup_data_uniq_id+"'"+');" style="z-index:99999; float:left; cursor:pointer; margin:5px 5px 0;" type="button" class="close" data-dismiss="arfmodal">x</button>';
            jQuery('.arf_pop_' + popup_data_uniq_id).find('.arf_form.ar_main_div_' + id).prepend(close_btn);
        }else{
            modal_body_width = width;
            jQuery('.arf_pop_' + popup_data_uniq_id).find('#arf_sitcky_close_btn').remove();
            jQuery('.arf_pop_' + popup_data_uniq_id).find('.arf_form.ar_main_div_' + id).css('max-width',width+'px');
        }
    /*04-08-2015*/
    
    /*11-08-2015*/
    if(height == 'auto'){
        var total_popup_height = jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).height();
        if(total_popup_height > screenheight){
            modal_body_height = screenheight - (screenheight *10 /100);
            newheightformainmodal = screenheight - (screenheight *10 /100);
        }
    }
    /*11-08-2015*/





    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('max-height', (newheightformainmodal) + 'px');
    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).css('height', (newheightformainmodal) + 'px');

    /*jQuery('#popup-form-'+id).css('data-mtop', total_height);
     jQuery('#popup-form-'+id).css('data-mleft', total_width);*/

    jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id + ' .arfmodal-body').css('height', modal_body_height + 'px');
    if (jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_block_right').hasClass('arform_bottom_fixed_block_open'))
    {
        jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_block_right').removeClass('arform_bottom_fixed_block_open');

        //jQuery('.arform_bottom_fixed_block_right').css('margin-right','0');
        jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_block_right').animate({'margin-right': '0'}, 500);
        //jQuery('.arform_bottom_fixed_form_block_right_main').animate({'margin-right': '-' + width + 'px'}, 500);
        jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_form_block_right_main').animate({'margin-right': '-' + modal_body_width + 'px'}, 500);
        
    }
    else
    {
        jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_form_block_right_main').animate({'margin-right': '0'}, 500);
        //jQuery('.arform_bottom_fixed_block_right').css('margin-right',width+'px');
        
        //jQuery('.arform_bottom_fixed_block_right').animate({'margin-right': width + 'px'}, 500);
        jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_block_right').animate({'margin-right': modal_body_width + 'px'}, 500);
        

        var form_key = jQuery('.arf_pop_' + popup_data_uniq_id).find('#form_key_' + id).val();
        jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).find('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find('.arfmodal-body #form_' + form_key).show();
        jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).find('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).find('.arfmodal-body .arf_content_another_page').empty().hide();

        jQuery('#arf-popup-form-' + id + '.arf_popup_' + popup_data_uniq_id).find('.arform_bottom_fixed_block_right').addClass('arform_bottom_fixed_block_open');

        var data_open = jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).attr('data-open');
        data_open = (data_open !== undefined) ? data_open : true;
        if (checkradio_style_prop != "" && data_open == true)
        {
            if (jQuery.isFunction(jQuery().iCheck))
            {
                if(checkradio_style_prop == 'custom') {
                jQuery('.arf_pop_' + popup_data_uniq_id).find('#arffrm_' + id + '_container input').not('.arf_hide_opacity').iCheck({
                    checkboxClass: 'icheckbox_' + checkradio_style_prop,
                    radioClass: 'iradio_' + checkradio_style_prop,
                        hoverClass: ' ',
                        checkedCheckboxClass: checked_checkbox_prop,
                        checkedRadioClass: checked_radio_prop
                });
                } else {
                    jQuery('.arf_pop_' + popup_data_uniq_id).find('#arffrm_' + id + '_container input').not('.arf_hide_opacity').iCheck({
                        checkboxClass: 'icheckbox_' + checkradio_style_prop,
                        radioClass: 'iradio_' + checkradio_style_prop,
                        increaseArea: '25%'
                    });
                }
            }
            jQuery('#popup-form-' + id + '.arf_pop_' + popup_data_uniq_id).attr('data-open', 'false');
        }

        // for colorpicker
        if (data_open == true)
        {
            arfmodalcolorpicker(id);
        }

        // for file upload
        var arfmainformurl = jQuery('.arf_pop_' + popup_data_uniq_id).find('#arfmainformurl').val();
        arfmainformurl = is_ssl_replace(arfmainformurl);
        var url = arfmainformurl + '/js/filedrag/filedrag_front.js';
        var submit_type = jQuery('.arf_pop_' + popup_data_uniq_id).find('#form_submit_type').val();
        if (submit_type == 1) {
            jQuery.getScript(url);
        }

        jQuery('.arf_pop_' + popup_data_uniq_id).find('.original_normal').on('change', function (e) {
            var id = jQuery(this).attr('id');
            id = id.replace('field_', '');

            var fileName = jQuery(this).val();
            fileName = fileName.replace(/C:\\fakepath\\/i, '');
            if (fileName != '') {
                jQuery('.arf_pop_' + popup_data_uniq_id).find('#file_name_' + id).html(fileName);
            }
        });
        // for file upload end
        //for star rating
        jQuery('.rate_widget').each(function (i) {

            widget_id = jQuery(this).attr('id');
            var rate_data_id = jQuery(this).parents('form').attr('data-id');    
            var current_frm = jQuery("form.arfshowmainform[data-id='"+rate_data_id+"']");

            current_frm.find('.ratings_stars').hover(
                    function () {
                        var color = jQuery(this).attr('data-color');
                        var datasize = jQuery(this).attr('data-size');
                        jQuery(this).prevAll().andSelf().addClass('ratings_over_' + color + datasize);
                        jQuery(this).nextAll().removeClass('ratings_vote_' + color + datasize);
                    },
                    function () {
                        var color = jQuery(this).attr('data-color');
                        var datasize = jQuery(this).attr('data-size');
                        jQuery(this).prevAll().andSelf().removeClass('ratings_over_' + color + datasize);
                        widget_id_new = jQuery(this).parent().attr('id');
                        set_votes(jQuery(this).parent(), widget_id_new);
                    }
            );

            current_frm.find('.ratings_stars').bind('click', function () {
                var star = this;
                var widget = jQuery(this).parent();

                var clicked_data = {
                    clicked_on: jQuery(star).attr('data-val'),
                    widget_id: jQuery(star).parent().attr('id')
                };
                widget_id_new = jQuery(this).parent().attr('id');

                current_frm.find('#field_' + widget_id_new).val(clicked_data.clicked_on);
                current_frm.find('#field_' + widget_id_new).trigger('click');
                set_votes(widget, widget_id_new);
            });

        });
        //for star rating

        //for like button
        jQuery('.arf_like_btn, .arf_dislike_btn').not('.field_edit').on("click", function () {
            var field = jQuery(this).attr('id');
            var field = field.replace('like_', 'field_');
            if (!jQuery("#" + field).is(':checked')) {
                jQuery("#" + jQuery(this).attr("for")).trigger('click').trigger('change');
            }
        });

        jQuery('.arf_form .arf_like').on("click", function () {
            var field = jQuery(this).attr('id');
            field_data = field.split('-');

            var field_id = field_data[0];
            field_id = field_id.replace('field_', '');
            field_id = 'like_' + field_id;
            var like = field_data[1];

            if (like == 1) {
                jQuery('#' + field_id + '-0').removeClass('active');
                jQuery('#' + field_id + '-1').addClass('active');
            } else if (like == 0) {
                jQuery('#' + field_id + '-1').removeClass('active');
                jQuery('#' + field_id + '-0').addClass('active');
            }
        });
        jQuery('.arf_like_btn, .arf_dislike_btn').each(function () {
            var title = jQuery(this).attr('data-title');
            if (title !== undefined) {
                jQuery(this).popover({
                    html: true,
                    trigger: 'hover',
                    placement: 'top',
                    content: title,
                    title: '',
                    animation: false
                });
            }
        });
        //for like button end

        arf_change_modal_slider(jQuery('.arf_pop_' + popup_data_uniq_id).find('#form_' + form_key));		//for modal slider
    }

    //jQuery('.arform_bottom_fixed_block_left').parents('.arform_bottom_fixed_main_block_left').find('.arform_bottom_fixed_form_block_left_main').slideToggle("500");

    var options_right = {direction: 'right'};

    //jQuery('.arform_bottom_fixed_block_right').parents('.arform_bottom_fixed_main_block_right').find('.arform_bottom_fixed_form_block_right_main').toggle('slide', options_right, 500);

}