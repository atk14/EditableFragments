<?php
/**
 *
 *	{editable_pupiq_image geometry="400x300"}
 *		<img src="http://i.pupiq.net/i/77/77/083/31083/600x500/5ucyEH_600x500_c834ac5ca651b903.png" class="img-fluid">
 *	{/editable_pupiq_image}
 */
function smarty_block_editable_pupiq_image($params,$content,$template,&$repeat){
	if($repeat){ return; }

	$params += array(
		"geometry" => "", //  "400x", "x300", "400x300"...
	);

	$smarty = atk14_get_smarty_from_template($template);

	Atk14Require::Helper("block.editable");

	$params["type"] = "pupiq_image";

	$image_url = "";
	if(preg_match('/<img.*?src=["\']?([^\s"\'>]+)["\']?/is',$content,$matches)){
		$image_url = $matches[1];
	}

	$image_url = smarty_block_editable($params,$image_url,$template,$repeat);

	// see app/helpers/block.editable.php
	if(preg_match('/^<(span|div) class="editable"/',$image_url)){
		$ary = explode("\n",$image_url);
		$first_line = array_shift($ary);
		$last_line = array_pop($ary);
		$image_url = join("\n",$ary);

		$first_line = preg_replace('/^<span /','<div ',$first_line);
		$last_line = str_replace('</span>','</div>',$last_line);

		$first_line = "$first_line\n";
		$last_line = "\n$last_line";
	}else{
		$first_line = "";
		$last_line = "";
	}

	//$content = smarty_block_markdown(array(),$content,$template,$repeat);

	if(!$image_url){
		$content = "";
	}else{
		$pupiq = new Pupiq($image_url);
		$_image_url = $pupiq->getUrl($params["geometry"]);
		if($_image_url){ $image_url = $_image_url; }
		$content = preg_replace('/(<img.*src=["\']?)([^\s"\'>]+)(["\']?)/is',"\\1$image_url\\3",$content);
	}

	return "$first_line$content$last_line";
}
