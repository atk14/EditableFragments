<?php
/**
 *
 *	{editable_pupiq_image geometry="400x300"}
 *		<img src="http://i.pupiq.net/i/77/77/083/31083/600x500/5ucyEH_600x500_c834ac5ca651b903.png" class="img-fluid">
 *	{/editable_pupiq_image}
 *
 *	{editable_pupiq_image geometry="400x300"}
 *		<picture>
 *			<source srcset="http://i.pupiq.net/i/77/77/083/31083/600x500/5ucyEH_600x500_c834ac5ca651b903.webp" type="image/webp">
 *			<img src="http://i.pupiq.net/i/77/77/083/31083/600x500/5ucyEH_600x500_c834ac5ca651b903.png" class="img-fluid">
 *		</picture>
 *	{/editable_pupiq_image}
 */
function smarty_block_editable_pupiq_image($params,$content,$template,&$repeat){
	if($repeat){ return; }

	$params += array(
		"geometry" => "", //  "400x", "x300", "400x300"...
	);

	$geometry = $params["geometry"];
	$smarty = atk14_get_smarty_from_template($template);

	Atk14Require::Helper("block.editable");

	$params["type"] = "pupiq_image";

	$image_url = "";
	if(preg_match('/<img\b.*?src=["\']?([^\s"\'>]+)["\']?/is',$content,$matches)){
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
		$_image_url = $pupiq->getUrl($geometry);
		if(!$_image_url){
			return "$first_line$content$last_line";
		}
		$image_url = $_image_url;
		preg_match('/\.([a-z]+)$/',$image_url,$matches);
		$image_format = $matches[1]; // "jpg", "png", "svg", "webp"
		myAssert($image_format);

		if($_image_url && preg_match('/<picture\b/i',$content)){
			// e.g.
			//	<picture>
			//		<source srcset="http://i.pupiq.net/i/77/77/083/31083/600x500/5ucyEH_600x500_c834ac5ca651b903.webp" type="image/webp">
			//		<img src="http://i.pupiq.net/i/77/77/083/31083/600x500/5ucyEH_600x500_c834ac5ca651b903.png" alt="" class="img-fluid instructions__image">
			//	</picture>
			preg_match('/(<img\b.*?>)/is',$content,$matches);
			$img_tag = $matches[1];
			myAssert($img_tag);
			$fallback_image_format = $image_format==="webp" ? "jpg" : $image_format;
			$fallback_image_url = $pupiq->getUrl("$geometry,format=$fallback_image_format");
			$img_tag = preg_replace('/(<img\b.*src=["\']?)([^\s"\'>]+)(["\']?)/is',"\\1$fallback_image_url\\3",$img_tag);
			$sources = [];
			if($image_format=="svg"){
				$sources[] = sprintf('<source srcset="%s" type="%s">',$pupiq->getUrl("$geometry,format=svg"),"image/svg+xml");
			}else{
				$sources[] = sprintf('<source srcset="%s" type="%s">',$pupiq->getUrl("$geometry,format=webp"),"image/webp");
			}

			$content = preg_replace('/(<picture\b.*?>).*?(\s*<\/picture>)/is','\1'."\n".join("\n",$sources)."\n".$img_tag."\n".'\2',$content);
		}else{
			$content = preg_replace('/(<img\b.*src=["\']?)([^\s"\'>]+)(["\']?)/is',"\\1$image_url\\3",$content);
		}
	}

	return "$first_line$content$last_line";
}
