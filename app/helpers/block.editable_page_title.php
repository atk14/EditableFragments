<?php
/**
 * Pouziva se pro editovatelny $page_title
 *
 *	<h1>{editable_page_title}{$page_title}{/editable_page_title}</h1>
 */
function smarty_block_editable_page_title($params,$content,$template,&$repeat){
	if($repeat){ return; }

	$smarty = atk14_get_smarty_from_template($template);

	$params["key"] = "page_title";
	$params["type"] = "title";

	Atk14Require::Helper("block.editable");
	$content = smarty_block_editable($params,$content,$template,$repeat);

	$page_title = preg_replace('/^<(span|div).*?>(.*)<\/(span|div)>/s','\2',$content);
	$smarty->assign("page_title",$page_title);

	return $content;
}
