<?php
/**
 * Funguje jako {render partial} s tim, ze prvni parametr (krome partial) musi byt objekt a tento objekt je editovatelny
 *
 * {editable_render partial="person_info" person=$person key="vip_person"}
 *
 * TODO: refaktoring spolecnych veci s block.editable
 */
function smarty_function_editable_render($params,$template){
	$smarty = atk14_get_smarty_from_template($template);
	Atk14Require::Helper("function.render",$smarty);

	$logged_user = $smarty->getTemplateVars("logged_user");

	$params += array(
		"key" => null,
		"content_section" => $smarty->getTemplateVars("current_content_section"), // "obsah/o-nas/postoje"
	);

	$key = $params["key"];
	unset($params["key"]);

	$content_section = EditableFragment::NormalizeContentSection($params["content_section"]);
	unset($params["content_section"]);

	$object_name = "";
	$object = null;
	foreach($params as $k => $v){
		if($k=="partial"){
			continue;
		}
		$object_name = $k;
		$object = $v;
		break;
	}

	if(!is_object($object)){
		throw new Exception("Parameter $object_name has to be an object (TableRecord)");
	}

	$content_type = $class_name = get_class($object); // "Person";
	if(!$key){ $key = strtolower($content_type); } // "person"
	$key = EditableFragment::NormalizeKey($key,$content_type,$lang);

	$fragment = EditableFragment::FindFirst("key",$key,"content_type",$content_type,"lang",$lang);
	if(!$fragment){
		$fragment = EditableFragment::CreateNewRecord(array(
			"lang" => $lang,
			"key" => $key,
			"content_type" => $content_type,
			"initial_content" => (string)$object->getId(),
		));
	}

	if(!$fragment->canBeEditedByUser($logged_user)){
		$logged_user = null;
	}

	if($fragment->getInitialContent()!==(string)$object->getId() || $fragment->getContentSection()!==$content_section){
		$fragment->s(array(
			"initial_content" => (string)$object->getId(),
			"content_section" => $content_section,
			"updated_at" => $fragment->g("updated_at"),
			"updated_by_user_id" => $fragment->g("updated_by_user_id"),
		));
	}

	if($_object = Cache::Get("$class_name",$fragment->getContent())){
		$object = $_object;
	}

	$params[$object_name] = $object;

	$output = smarty_function_render($params,$template);

	if($logged_user){
		$url = Atk14Url::BuildLink(array("namespace" => "admin", "controller" => "editable_fragments", "action" => "edit", "id" => $fragment));
		$tag = "div";
		$output = "<$tag class=\"editable\" data-edit-url=\"$url\">$output</$tag>";
	}

	return $output;
}
