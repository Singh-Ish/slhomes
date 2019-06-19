<?php
$dir_path = plugin_dir_path (__FILE__);
$style="<style>".file_get_contents( $dir_path.'style.css')."
</style>";
$list_item_template = '
<div class="imc-logo-wrapper tool_tip_set" title="IMC_TOOL_TIP" style="padding-top: ITEM_HEIGHT%;">
 <a IMC_LINK><div class="imc-logo" style="background-image: url(IMC_LOGO);"></div></a>
 <div class="imc-client-name">
 	IMC_NAME
 </div>
</div>
';
?>