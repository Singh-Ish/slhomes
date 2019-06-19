<?php
	if ( ! defined( 'ABSPATH' ) )
	{
		die( 'Your are in wrong place.' );
	}
	define( 'REALTY_HOUSE_SHORTCODE_WIZARD', true );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<form action="#" id="shortcode-form">
		<div class="button-container">
			<input type="submit" value="<?php esc_html_e( 'Insert', 'realty-house-pl' ); ?>">
		</div>
		<div class="inner-container">
			<div class="rows" id="shortcode-item-container">
				<label for="shortcode-item"><?php esc_html_e( 'ShortCode : ', 'realty-house-pl' ); ?></label>
				<select name="shortcode-item" id="shortcode-item">
					<option value="0"><?php esc_html_e( 'Select a shortcode', 'realty-house-pl' ); ?></option>
					<option value="realty-house-social-icons"><?php esc_html_e( 'Social Icons', 'realty-house-pl' ) ?></option>
					<option value="realty-house-main-gallery"><?php esc_html_e( 'Main Gallery', 'realty-house-pl' ) ?></option>
					<option value="realty-house-services"><?php esc_html_e( 'Services', 'realty-house-pl' ) ?></option>
					<option value="realty-house-main-slider"><?php esc_html_e( 'Main Slider', 'realty-house-pl' ) ?></option>
					<option value="realty-house-be-agent"><?php esc_html_e( 'Be an Agent', 'realty-house-pl' ) ?></option>
					<option value="realty-house-fun-facts"><?php esc_html_e( 'Fun Facts', 'realty-house-pl' ) ?></option>
					<option value="realty-house-property-slider"><?php esc_html_e( 'Property Slider', 'realty-house-pl' ) ?></option>
					<option value="realty-house-agent-rating"><?php esc_html_e( 'Agent Rating', 'realty-house-pl' ) ?></option>
					<option value="realty-house-agent"><?php esc_html_e( 'Agents', 'realty-house-pl' ) ?></option>
					<option value="realty-house-testimonials"><?php esc_html_e( 'Testimonials', 'realty-house-pl' ) ?></option>
					<option value="realty-house-new-property"><?php esc_html_e( 'New Properties', 'realty-house-pl' ) ?></option>
					<option value="realty-house-other-properties"><?php esc_html_e( 'Other Properties', 'realty-house-pl' ) ?></option>
					<option value="realty-house-map-listing"><?php esc_html_e( 'Map Listing', 'realty-house-pl' ) ?></option>
					<option value="realty-house-contact"><?php esc_html_e( 'Contact Section', 'realty-house-pl' ) ?></option>
					<option value="realty-house-video"><?php esc_html_e( 'Video Section', 'realty-house-pl' ) ?></option>
					<option value="realty-house-search"><?php esc_html_e( 'Search Form', 'realty-house-pl' ) ?></option>
					<option value="realty-house-currency-switcher"><?php esc_html_e( 'Currency Switcher', 'realty-house-pl' ) ?></option>
					<option value="realty-house-promo"><?php esc_html_e( 'Promo Section', 'realty-house-pl' ) ?></option>
				</select>
				<div class="hint"><?php esc_html_e( 'Please select the shortcode you want to add.', 'realty-house-pl' ) ?></div>
			</div>
			<div class="rows no-attributes hide"><?php esc_html_e( 'This shortcode doesn\'t have any attributes', 'realty-house-pl' ); ?></div>
			<div id="realty-house-social-icons" class="hide">
				<div class="rows">
					<label for="social-icons-id"><?php esc_html_e( 'ID : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="id" id="social-icons-id" placeholder="social-icons">
					<div class="hint"><?php esc_html_e( 'Add the id attribute of the social icons container.', 'realty-house-pl' ) ?></div>
				</div>
			</div>
			<div id="realty-house-main-gallery" class="hide">
				<div class="rows">
					<label for="main-gallery-title"><?php esc_html_e( 'Title : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="title" id="main-gallery-title" placeholder="<?php esc_html_e( 'Our Gallery', 'realty-house-pl' ) ?>">
					<div class="hint"><?php esc_html_e( 'Add title of main gallery section in this field.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows">
					<label for="main-gallery-columns"><?php esc_html_e( 'Columns : ', 'realty-house-pl' ); ?></label>
					<input type="number" class="form-item" name="columns" id="main-gallery-count" placeholder="2">
					<div class="hint"><?php esc_html_e( 'Add in how many columns you want main gallery to be shown.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows">
					<label for="main-gallery-count"><?php esc_html_e( 'Image Count : ', 'realty-house-pl' ); ?></label>
					<input type="number" class="form-item" name="img_count" id="main-gallery-count" placeholder="12">
					<div class="hint"><?php esc_html_e( 'Add how many images you want to show in main gallery.', 'realty-house-pl' ) ?></div>
				</div>
			</div>
			<div id="realty-house-services" class="hide">
				<div class="rows">
					<label for="services-title"><?php esc_html_e( 'Title : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="title" id="services-title" placeholder="<?php esc_html_e( 'Our Services', 'realty-house-pl' ) ?>">
					<div class="hint"><?php esc_html_e( 'Add title of services section in this field.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows">
					<label for="services-post-count"><?php esc_html_e( 'Post Count : ', 'realty-house-pl' ); ?></label>
					<input type="number" class="form-item" name="post_count" id="services-count" placeholder="6">
					<div class="hint"><?php esc_html_e( 'Add how many posts you want to show in services section.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows">
					<label for="services-count"><?php esc_html_e( 'Class : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="class" id="services-count" placeholder="container">
					<div class="hint"><?php esc_html_e( 'Add the class of container box of services section.', 'realty-house-pl' ) ?></div>
				</div>
			</div>
			<div id="realty-house-be-agent" class="hide">
				<div class="rows">
					<label for="be-agent-title"><?php esc_html_e( 'Title : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="title" id="be-agent-title" placeholder="<?php esc_html_e( 'Become a Real Estate Agent', 'realty-house-pl' ) ?>">
					<div class="hint"><?php esc_html_e( 'Add title of be an agent section in this field.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows">
					<label for="be-agent-subtitle"><?php esc_html_e( 'Subtitle : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="subtitle" id="be-agent-subtitle" placeholder="<?php esc_html_e( 'Join us and our real estate community members!', 'realty-house-pl' ) ?>">
					<div class="hint"><?php esc_html_e( 'Add subtitle of be an agent section in this field.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows">
					<label for="be-agent-bg-img"><?php esc_html_e( 'Background Image : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="bg_img" id="be-agent-bg-img" placeholder="http://">
					<div class="hint"><?php esc_html_e( 'Add the URL of background image of this section.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows">
					<label for="be-agent-agent-image"><?php esc_html_e( 'Agent Image : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="agent_img" id="be-agent-agent-image" placeholder="http://">
					<div class="hint"><?php esc_html_e( 'Add the URL of agent image for this section.', 'realty-house-pl' ) ?></div>
				</div>
			</div>
			<div id="realty-house-fun-facts" class="hide">
				<div class="rows">
					<label for="fun-facts-title"><?php esc_html_e( 'Title : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="title" id="fun-facts-title" placeholder="<?php esc_html_e( 'Fun Facts', 'realty-house-pl' ) ?>">
					<div class="hint"><?php esc_html_e( 'Add title of fun facts section in this field.', 'realty-house-pl' ) ?></div>
				</div>
			</div>
			<div id="realty-house-property-slider" class="hide">
				<div class="rows long-hint">
					<label for="property-slider-title"><?php esc_html_e( 'Property IDs : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="property_ids" id="property-slider-title" placeholder="1,3,1004">
					<div class="hint"><?php esc_html_e( 'Add the comma separated value (CSV) of property IDs that you want to be shown in property slider.', 'realty-house-pl' ) ?></div>
				</div>
			</div>
			<div id="realty-house-agent" class="hide">
				<div class="rows">
					<label for="agents-title"><?php esc_html_e( 'Title : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="title" id="agents-title" placeholder="<?php esc_html_e( 'Our Agents', 'realty-house-pl' ) ?>">
					<div class="hint"><?php esc_html_e( 'Add title of agents section in this field.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows">
					<label for="agents-count"><?php esc_html_e( 'Box Count : ', 'realty-house-pl' ); ?></label>
					<input type="number" class="form-item" name="count" id="agents-count" placeholder="6">
					<div class="hint"><?php esc_html_e( 'Add how many agent box you want to show in agent section.', 'realty-house-pl' ) ?></div>
				</div>
			</div>
			<div id="realty-house-testimonials" class="hide">
				<div class="rows">
					<label for="testimonials-title"><?php esc_html_e( 'Title : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="title" id="testimonials-title" placeholder="<?php esc_html_e( 'What Clients Say', 'realty-house-pl' ) ?>">
					<div class="hint"><?php esc_html_e( 'Add title of testimonials section in this field.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows">
					<label for="testimonials-post-count"><?php esc_html_e( 'Count : ', 'realty-house-pl' ); ?></label>
					<input type="number" class="form-item" name="count" id="testimonials-post-count" placeholder="3">
					<div class="hint"><?php esc_html_e( 'Add how many testimonials you want to show in testimonial section.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows">
					<label for="testimonials-type"><?php esc_html_e( 'Type : ', 'realty-house-pl' ); ?></label>
					<select class="form-item" name="type" id="testimonials-type">
						<option value="simple" selected="selected"><?php esc_html_e( 'Simple', 'realty-house-pl' ); ?></option>
						<option value="tab"><?php esc_html_e( 'Tab', 'realty-house-pl' ); ?></option>
					</select>
					<div class="hint"><?php esc_html_e( 'Select which type of testimonial section you want.', 'realty-house-pl' ) ?></div>
				</div>
			</div>
			<div id="realty-house-new-property" class="hide">
				<div class="rows">
					<label for="new-property-title"><?php esc_html_e( 'Title : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="title" id="new-property-title" placeholder="<?php esc_html_e( 'Our Listings', 'realty-house-pl' ) ?>">
					<div class="hint"><?php esc_html_e( 'Add title of new properties section in this field.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows">
					<label for="new-property-count"><?php esc_html_e( 'Property Count : ', 'realty-house-pl' ); ?></label>
					<input type="number" class="form-item" name="property_count" id="new-property-count" placeholder="6">
					<div class="hint"><?php esc_html_e( 'Add how many properties you want to show in new properties section.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows">
					<label for="new-property-class"><?php esc_html_e( 'Class : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="class" id="new-property-class">
					<div class="hint"><?php esc_html_e( 'Add a class for the container box of properties section.', 'realty-house-pl' ) ?></div>
				</div>
			</div>
			<div id="realty-house-other-properties" class="hide">
				<div class="rows">
					<label for="other-properties-title"><?php esc_html_e( 'Title : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="title" id="other-properties-title" placeholder="<?php esc_html_e( 'Other Properties', 'realty-house-pl' ) ?>">
					<div class="hint"><?php esc_html_e( 'Add title of other properties section in this field.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows long-hint">
					<label for="other-properties-status"><?php esc_html_e( 'Property Status : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="property_status" id="other-properties-status" placeholder="1,3,1004">
					<div class="hint"><?php esc_html_e( 'Add the comma separated value (CSV) of property statuses that you want to be shown in other properties section.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="other-properties-new_tag">
						<input type="checkbox" class="form-item" name="new_tag" id="other-properties-new_tag">
						<span></span>
						<?php esc_html_e( 'New Properties', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to show properties with New Tags be shown in other properties section', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="other-properties-hot_offer">
						<input type="checkbox" class="form-item" name="hot_offer" id="other-properties-hot_offer" value="on">
						<span></span>
						<?php esc_html_e( 'Hot Offer Properties', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to show properties with Hot Offer Tags be shown in other properties section', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="other-properties-featured">
						<input type="checkbox" class="form-item" name="featured" id="other-properties-featured" value="on">
						<span></span>
						<?php esc_html_e( 'Featured Properties', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to show properties with Featured Tags be shown in other properties section', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="other-properties-price_cut">
						<input type="checkbox" class="form-item" name="price_cut" id="other-properties-price_cut" value="on">
						<span></span>
						<?php esc_html_e( 'Price Cut Properties', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to show properties with Price Cut Tags be shown in other properties section', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="other-properties-open_house">
						<input type="checkbox" class="form-item" name="open_house" id="other-properties-open_house" value="on">
						<span></span>
						<?php esc_html_e( 'Open House Properties', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to show properties with Open House Tags be shown in other properties section', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="other-properties-foreclosure">
						<input type="checkbox" class="form-item" name="foreclosure" id="other-properties-foreclosure" value="on">
						<span></span>
						<?php esc_html_e( 'Foreclosure Properties', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to show properties with Foreclosure Tags be shown in other properties section', 'realty-house-pl' ) ?></div>
				</div>
			</div>
			<div id="realty-house-map-listing" class="hide">
				<div class="rows long-hint">
					<label for="map-properties-status"><?php esc_html_e( 'Property Status : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="property_status" id="map-properties-status" placeholder="1,3,1004">
					<div class="hint"><?php esc_html_e( 'Add the comma separated value (CSV) of property statuses that you want to be shown in map properties section.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="map-properties-new_tag">
						<input type="checkbox" class="form-item" name="new_tag" id="map-properties-new_tag">
						<span></span>
						<?php esc_html_e( 'New Properties', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to show properties with New Tags be shown in map properties section', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="map-properties-hot_offer">
						<input type="checkbox" class="form-item" name="hot_offer" id="map-properties-hot_offer">
						<span></span>
						<?php esc_html_e( 'Hot Offer Properties', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to show properties with Hot Offer Tags be shown in map properties section', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="map-properties-featured">
						<input type="checkbox" class="form-item" name="featured" id="map-properties-featured">
						<span></span>
						<?php esc_html_e( 'Featured Properties', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to show properties with Featured Tags be shown in map properties section', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="map-properties-price_cut">
						<input type="checkbox" class="form-item" name="price_cut" id="map-properties-price_cut">
						<span></span>
						<?php esc_html_e( 'Price Cut Properties', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to show properties with Price Cut Tags be shown in map properties section', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="map-properties-open_house">
						<input type="checkbox" class="form-item" name="open_house" id="map-properties-open_house">
						<span></span>
						<?php esc_html_e( 'Open House Properties', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to show properties with Open House Tags be shown in map properties section', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="map-properties-foreclosure">
						<input type="checkbox" class="form-item" name="foreclosure" id="map-properties-foreclosure">
						<span></span>
						<?php esc_html_e( 'Foreclosure Properties', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to show properties with Foreclosure Tags be shown in map properties section', 'realty-house-pl' ) ?></div>
				</div>
			</div>
			<div id="realty-house-contact" class="hide">
				<div class="rows">
					<label for="contact-title"><?php esc_html_e( 'Title : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="title" id="contact-title" placeholder="<?php esc_html_e( 'Get In Touch', 'realty-house-pl' ) ?>">
					<div class="hint"><?php esc_html_e( 'Add title of contact section in this field.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows">
					<label for="contact-contact-7"><?php esc_html_e( 'Contact 7 ID : ', 'realty-house-pl' ); ?></label>
					<input type="number" class="form-item" name="contact_7_id" id="contact-contact-7">
					<div class="hint"><?php esc_html_e( 'Add the ID of contact 7 contact form in this field.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows">
					<label for="contact-image"><?php esc_html_e( 'Image : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="img" id="contact-image" placeholder="http://">
					<div class="hint"><?php esc_html_e( 'Add the URL of the image you want to be shown in this section.', 'realty-house-pl' ) ?></div>
				</div>
			</div>
			<div id="realty-house-video" class="hide">
				<div class="rows">
					<label for="video-title"><?php esc_html_e( 'Title : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="title" id="video-title" placeholder="<?php esc_html_e( 'Take a Video Tour!', 'realty-house-pl' ) ?>">
					<div class="hint"><?php esc_html_e( 'Add title of video section in this field.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows">
					<label for="video-subtitle"><?php esc_html_e( 'Subtitle : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="sub_title" id="video-subtitle" placeholder="<?php esc_html_e( 'Customized Marketing', 'realty-house-pl' ) ?>">
					<div class="hint"><?php esc_html_e( 'Add subtitle of video section in this field.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows">
					<label for="video-btn_text"><?php esc_html_e( 'Button Text : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="btn_text" id="video-btn_text" placeholder="<?php esc_html_e( 'Take Tour', 'realty-house-pl' ) ?>">
					<div class="hint"><?php esc_html_e( 'Add button text of video section in this field.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows">
					<label for="video-image"><?php esc_html_e( 'Video Image : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="video_img" id="video-image" placeholder="http://">
					<div class="hint"><?php esc_html_e( 'Add the URL of video that image you want to be shown in this section.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows">
					<label for="video-video_url"><?php esc_html_e( 'Video URL : ', 'realty-house-pl' ); ?></label>
					<input type="text" class="form-item" name="video_url" id="video-video_url" placeholder="http://">
					<div class="hint"><?php esc_html_e( 'Add the URL of video that you want to be shown in this section.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows">
					<label for="video-description"><?php esc_html_e( 'Description : ', 'realty-house-pl' ); ?></label>
					<textarea name="description" id="video-description" class="form-item"></textarea>
					<div class="hint"><?php esc_html_e( 'Add description about the video in this field.', 'realty-house-pl' ) ?></div>
				</div>
			</div>
			<div id="realty-house-search" class="hide">
				<div class="rows">
					<label for="search-type"><?php esc_html_e( 'Type : ', 'realty-house-pl' ); ?></label>
					<select class="form-item" name="type" id="search-type">
						<option value="simple" selected="selected"><?php esc_html_e( 'Simple', 'realty-house-pl' ); ?></option>
						<option value="accordion"><?php esc_html_e( 'Accordion', 'realty-house-pl' ); ?></option>
					</select>
					<div class="hint"><?php esc_html_e( 'Select which type of search box you want.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="search-p_location">
						<input type="checkbox" class="form-item" name="p_location" id="search-p_location">
						<span></span>
						<?php esc_html_e( 'Property Location', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to have property location field in search shortcode.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="search-p_status">
						<input type="checkbox" class="form-item" name="p_status" id="search-p_status">
						<span></span>
						<?php esc_html_e( 'Property Status', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to have property status field in search shortcode.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="search-p_type">
						<input type="checkbox" class="form-item" name="p_type" id="search-p_type">
						<span></span>
						<?php esc_html_e( 'Property Type', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to have property type field in search shortcode.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="search-bedrooms">
						<input type="checkbox" class="form-item" name="bedrooms" id="search-bedrooms">
						<span></span>
						<?php esc_html_e( 'Property Bedrooms', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to have property bedrooms field in search shortcode.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="search-bathrooms">
						<input type="checkbox" class="form-item" name="bathrooms" id="search-bathrooms">
						<span></span>
						<?php esc_html_e( 'Property Bathroom', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to have property bathroom field in search shortcode.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="search-garages">
						<input type="checkbox" class="form-item" name="garages" id="search-garages">
						<span></span>
						<?php esc_html_e( 'Property Garages', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to have property garages field in search shortcode.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="search-price">
						<input type="checkbox" class="form-item" name="price" id="search-price">
						<span></span>
						<?php esc_html_e( 'Property Price', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to have property price field in search shortcode.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows">
					<label for="search-max-price"><?php esc_html_e( 'Max Price : ', 'realty-house-pl' ); ?></label>
					<input type="number" class="form-item" name="max_price" id="search-max-price">
					<div class="hint"><?php esc_html_e( 'Set the max price of price range slider.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="search-keywords">
						<input type="checkbox" class="form-item" name="keywords" id="search-keywords">
						<span></span>
						<?php esc_html_e( 'Property Keywords', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to have property keywords field in search shortcode.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="search-amenities">
						<input type="checkbox" class="form-item" name="amenities" id="search-amenities">
						<span></span>
						<?php esc_html_e( 'Property Amenities', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to have property amenities field in search shortcode.', 'realty-house-pl' ) ?></div>
				</div>
				<div class="rows hsq-checkbox">
					<label for="search-tags">
						<input type="checkbox" class="form-item" name="tags" id="search-tags">
						<span></span>
						<?php esc_html_e( 'Property Tags', 'realty-house-pl' ); ?>
					</label>
					<div class="hint"><?php esc_html_e( 'Check this checkbox if you want to have property tags field in search shortcode.', 'realty-house-pl' ) ?></div>
				</div>
			</div>
		</div>
	</form>
	<?php wp_footer(); ?>
</body>
</html>