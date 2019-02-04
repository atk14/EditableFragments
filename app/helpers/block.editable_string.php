<?php
/**
 *
 *	{editable_string key="title"}Very nice title{/editable_string}
 */
function smarty_block_editable_string($params,$content,$template,&$repeat){
	$params["type"] = "string";
	Atk14Require::Helper("block.editable");
	return smarty_block_editable($params,$content,$template,$repeat);
}
