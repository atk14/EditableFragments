<?php
/**
 * Pro editovatelny markdown
 *
 *	{editable_markdown key="contact_info"}
 *	## Address
 *
 *	NTV AGE  
 *	Domazlicka 1  
 *	Praha 3  
 *	{/editable_markdown}
 */
function smarty_block_editable_markdown($params,$content,$template,&$repeat){
	if($repeat){ return; }

	$smarty = atk14_get_smarty_from_template($template);

	Atk14Require::Helper("block.markdown");
	Atk14Require::Helper("block.editable");

	$params["type"] = "markdown";

	$content = trim($content);
	$content = smarty_block_editable($params,$content,$template,$repeat);

	$opening_tag = $closing_tag = "";

	if(preg_match('/^(<.*?>)(.*)(<.*?>)$/s',$content,$matches)){
		$opening_tag = $matches[1];
		$closing_tag = $matches[3];
		$content = $matches[2];
	}

	$content = smarty_block_markdown(array(),$content,$template,$repeat);

	$opening_tag = preg_replace('/<span/','<div',$opening_tag);
	$closing_tag = preg_replace('/<\/span/','</div',$closing_tag);

	return "$opening_tag$content$closing_tag";
}
