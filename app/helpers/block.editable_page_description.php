<?php
/**
 * Pouziva se pro editovatelny $page_description
 *
 *	<meta name="description" content="{h}{editable_page_description}{!$page_description}{/editable_page_description}{/h}">
 */
function smarty_block_editable_page_description($params,$content,$template,&$repeat){
	if($repeat){ return; }

	$smarty = atk14_get_smarty_from_template($template);

	$params["key"] = "page_description";
	$params["type"] = "text";

	Atk14Require::Helper("block.editable");
	$content = smarty_block_editable($params,$content,$template,$repeat);

	if(preg_match('/^<(span|div)/',$content)){
		// takze prihlaseny uzivatel muze editovat page_description ->
		// vytiskneme mu to na konec stranky (viz app/shared/helpers/_block_editable_page_description.tpl)
		$original_smarty_vars = $smarty->getTemplateVars();
		$smarty->assign("content",$content);
		$smarty->fetch("shared/helpers/_block_editable_page_description.tpl");
		$smarty->clearAllAssign();
		$smarty->assign($original_smarty_vars);
	}

	// musime vratit $page_description bez linku do editace
	$page_description = preg_replace('/^<(span|div).*?>(.*)<\/(span|div)>/s','\2',$content);
	return $page_description;
}
