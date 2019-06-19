<?php
/**
 * MY ACCOUNT TEMPLATE
 */
if ( ! defined( 'YITH_WCMAP' ) ) {
    exit;
} // Exit if accessed directly

global $woocommerce, $wp, $post;

// check active endpoint key
$active_endpoint = yith_wcmap_get_endpoint_active( $endpoints );
$first = true;
?>

<div class="user-profile">

    <div class="user-image">
        <?php
            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;
            echo get_avatar( $user_id, 120 );
        ?>
        <?php if( $avatar ) : ?>
            <a href="#yith-wcmap-avatar-form" id="load-avatar" data-rel="prettyPhoto[yith-wcmap-avatar-form]">
                <i class="fa fa-camera"></i>
            </a>
        <?php endif; ?>
    </div>
    <div class="user-info">
        <p class="username"><?php echo $current_user->display_name ?> </p>
        <?php if( isset( $current_user ) && $current_user->ID != 0 ) : ?>
            <span class="logout"><a href="<?php echo wc_get_endpoint_url( 'customer-logout', '',  $my_account_url ); ?>"><?php _e( 'Logout', 'yith-woocommerce-customize-myaccount-page' ) ?></a></span>
        <?php endif; ?>
    </div>

</div>

<ul class="myaccount-menu">

    <?php foreach( $endpoints as $endpoint => $options ) :

        $url = $endpoint == 'dashboard' ? $my_account_url : wc_get_endpoint_url( $options['slug'], '', $my_account_url );

        yit_wpml_register_string( 'Plugins', 'plugin_yit_wcmap_' . $endpoint, $options['label'] );
        $localized_label_text = yit_wpml_string_translate( 'Plugins', 'plugin_yit_wcmap_' . $endpoint, $options['label'] );

        $active_class = ( ( $active_endpoint && $active_endpoint == $endpoint ) || ( ! $active_endpoint && $first && $endpoint == 'dashboard' ) ) ? ' class="active"' : '';
    ?>

        <li <?php echo $active_class ?>>

            <a class="<?php echo $options['slug'] ?>" href="<?php echo esc_url( $url ) ?>" title="<?php echo esc_attr( $options['label'] ) ?>">
                <?php if( ! empty( $options['icon'] ) ) : ?>
                    <i class="fa fa-<?php echo $options['icon'] ?>"></i>
                <?php endif; ?>
                <span><?php echo $localized_label_text ?></span>
            </a>

        </li>

    <?php $first = false;
    endforeach; ?>

</ul>

<?php if( $avatar ) : ?>
    <div id="yith-wcmap-avatar-form" style="display: none;">
        <h3><?php _e( 'Upload your avatar', 'yith-woocommerce-customize-myaccount-page') ?></h3>
        <form enctype="multipart/form-data" method="post" action="#">
            <p>
                <input type="file" name="ywcmap_user_avatar" id="ywcmap_user_avatar" accept="image/*">
            </p>
            <p class="submit">
                <input type="submit" class="button" value="<?php _e( 'Upload', 'yith-woocommerce-customize-myaccount-page' ) ?>">
            </p>
            <input type="hidden" name="action" value="wp_handle_upload">
            <input type="hidden" name="_nonce" value="<?php echo wp_create_nonce('wp_handle_upload') ?>">
        </form>
    </div>
<?php endif;