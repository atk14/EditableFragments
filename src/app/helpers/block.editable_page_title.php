<?php
/**
 * Pouziva se pro editovatelny $page_title
 *
 *	<h1>{editable_page_title}{$page_title}{/editable_page_title}</h1>
 */
function smarty_block_editable_page_title($params,$content,$template,&$repeat){
	if($repeat){ return; }

	$page_title_original = $content;

	$smarty = atk14_get_smarty_from_template($template);

	$params += array(
		"content_section" => $smarty->getTemplateVars("current_content_section"), // "content", "content/blog"
	);
	$params["key"] = "page_title";
	$params["type"] = "title";

	Atk14Require::Helper("block.editable");
	$content = smarty_block_editable($params,$content,$template,$repeat);

	$lang = null;
	$content_type = $params["type"];
	$key = EditableFragment::NormalizeKey($params["key"],$content_type,$lang);
	$content_section = EditableFragment::NormalizeContentSection($params["content_section"]);
	$fragment = EditableFragment::FindFirst("key",$key,"content_type",$content_type,"lang",$lang);
	if($fragment){
		$page_title = $fragment->getContent();
	}else{
		$page_title = $page_title_original;
	}

	// Here, the original page_title is silently replaced
	$smarty->assign("page_title",$page_title);

	return $content;
}
