<?php
	if ( ! is_active_sidebar( 'main-side-bar' ) )
	{
		return;
	}
?>
<!-- Sidebar Section -->
<aside id="side-bar" class="right-sidebar col-sm-4 col-md-3">
	<?php dynamic_sidebar( 'main-side-bar' ); ?>
</aside>
<!-- Sidebar Section -->