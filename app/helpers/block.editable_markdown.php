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

	// see app/helpers/block.editable.php
	if(preg_match('/^<(span|div) class="editable"/',$content)){
		$ary = explode("\n",$content);
		$first_line = array_shift($ary);
		$last_line = array_pop($ary);
		$content = join("\n",$ary);

		$first_line = str_replace('<span ','<div ',$first_line);
		$last_line = str_replace('</span>','</div>',$last_line);

		$first_line = "$first_line\n";
		$last_line = "\n$last_line";
	}else{
		$first_line = "";
		$last_line = "";
	}

	$content = smarty_block_markdown(array(),$content,$template,$repeat);

	return "$first_line$content$last_line";
}
