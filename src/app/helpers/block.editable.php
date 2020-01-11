<?php
/**
 * Prihlasenemu administratorovi oznaci editovatelny obsah a zobrazi odkaz do editace tohoto obsahu
 *
 *	{* editovatelny titulek *}
 *	<h1>{h}{editable key="title" type="title"}Hello World{/editable}{/h}</h1>
 *
 *	{* editable HTML fragment *}
 *	{editable key="content"}
 *		<p>
 *			Hello World!
 *		</p>
 *	{/editable}
 *
 *	{* editovatelna osoba *}
 *	{editable key="boss" type="Person"}
 *		{render partial=""}
 *	{/editable}
 *
 * Pro markdown se pouziva helper editable_markdown
 *
 *	{editable_markdown key="info"}{$markdown}{/editable}{/editable_markdown}
 */
function smarty_block_editable($params,$content,$template,&$repeat){
	global $ATK14_GLOBAL;

	if($repeat){ return; }

	class_exists("EditableFragment"); // Constants need to be loaded

	$smarty = atk14_get_smarty_from_template($template);
	$logged_user = $smarty->getTemplateVars("logged_user");
	$namespace = $ATK14_GLOBAL->getValue("namespace");

	$params += array(
		"type" => "text", // e.g. "text", "markdown", "title", "Person", "Article"
		"key" => DEFAULT_EDITABLE_KEY, // e.g. "page_description", "notice", "promo", "company_data", "/company_data"
		"content_section" => $smarty->getTemplateVars("current_content_section"), // "content", "content/blog"
	);

	$lang = null;
	$content_type = $params["type"];
	$key = EditableFragment::NormalizeKey($params["key"],$content_type,$lang);
	$content_section = EditableFragment::NormalizeContentSection($params["content_section"]);

	$fragment = EditableFragment::FindFirst("key",$key,"content_type",$content_type,"lang",$lang);
	if(!$fragment){
		$fragment = EditableFragment::CreateNewRecord(array(
			"lang" => $lang,
			"key" => $key,
			"content_type" => $content_type,
			"initial_content" => $content,
		));
	}

	if(!$fragment->canBeEditedByUser($logged_user)){
		$logged_user = null;
	}

	if($fragment->getInitialContent()!==$content || $fragment->getContentSection()!==$content_section){
		$fragment->s(array(
			"initial_content" => $content,
			"content_section" => $content_section,
			"updated_at" => $fragment->g("updated_at"),
			"updated_by_user_id" => $fragment->g("updated_by_user_id"),
		));
	}

	$output = $fragment->getContent();

	if($logged_user){
		$url = Atk14Url::BuildLink(array("namespace" => "admin", "controller" => "editable_fragments", "action" => "edit", "id" => $fragment));
		$tag = "span";
		$icon = '<i class="icon ion-edit"></i>';
		if(defined("USING_FONTAWESOME") && USING_FONTAWESOME){
			$icon = '<i class="fas fa-pencil-alt"></i>';
		}
		$button = "<a href=\"$url\" class=\"editable-edit-link\">$icon<span class=\"editable-edit-link-text\"> editovat</span></a>";

		if(trim($output)==""){
			$output = "<i>"._("Zde je prostor pro volně editovatelný obsah...")."</i>";
		}
		
		// pokud jsou v obsahu blokove tagy, tak to obalime divem
		if(preg_match('/<(p|div|ul|ol|table|dd|form|address|pre|video|blockquote|fieldset|h[1-9]|hr|article)(|\s[^>]*)>/',$output)){
			$tag = "div";
		}

		$output = "<$tag class=\"editable\" data-edit-url=\"$url\">$button\n$output\n</$tag>";
	}


	return $output;
}
