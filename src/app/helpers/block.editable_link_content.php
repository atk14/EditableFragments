<?php
/**
 * Block helper editable_link_content lets edit just the content between tag <a></a>
 *
 * Normally it returns tag <a> unmodified,
 * but for a logged administrator it removes ugly <a> tags nesting.
 *
 *	{editable_link_content key="important_link"}<a href="https://www.atk14/net/" class="link link--important_link">Let's do it with the ATK14 Framework</a>{/editable_link_content}
 *
 *	{editable_link_content key="about_us/title"}
 *		<a href="{"about_us"|link_to_page}">
 *			About us
 *		</a>
 *	{/editable_link_content}
 */
function smarty_block_editable_link_content($params,$content,$template,&$repeat){
	if($repeat){ return; }

	$params += array(
		"type" => "string",
	);

	$opening_tag = $closing_tag = "";
	if(preg_match('/^(\s*<a\b[^>]*>\s*)(.*?)(\s*<\/a>\s*)$/si',$content,$matches)){
		$opening_tag = $matches[1];
		$content = $matches[2];
		$closing_tag = $matches[3];
	}

	Atk14Require::Helper("block.editable");
	$link_content = smarty_block_editable($params,$content,$template,$repeat);
	if(preg_match('/<a\b/',$link_content)){
		// In the $link_content, is there already a link? -> It's certainly a content for an administrator.
		return $link_content;
	}
	return $opening_tag.$link_content.$closing_tag;
}
