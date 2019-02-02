<?php
defined("DEFAULT_EDITABLE_CONTENT_TYPE") || define("DEFAULT_EDITABLE_CONTENT_TYPE","text");
defined("DEFAULT_EDITABLE_CONTENT_SECTION") || define("DEFAULT_EDITABLE_CONTENT_SECTION","content");
defined("DEFAULT_EDITABLE_KEY") || define("DEFAULT_EDITABLE_KEY","content");

class EditableFragment extends ApplicationModel{

	static function NormalizeKey($key,$content_type,&$lang,$options = array()){
		global $ATK14_GLOBAL;

		$options += array(
			"lang" => $ATK14_GLOBAL->getLang(),
			"namespace" => $ATK14_GLOBAL->getValue("namespace"),
			"controller" => $ATK14_GLOBAL->getValue("controller"),
			"action" => $ATK14_GLOBAL->getValue("action"),
		);

		if(!strlen($key)){
			$key = DEFAULT_EDITABLE_KEY;
		}

		if(!preg_match('/^\//',$key)){
			$key = "/$options[controller]/$options[action]/$key"; // "/main/index/content"
			if($options["namespace"]){
				$key = "/$options[namespace]$key"; // "/admin/main/index/content"
			}
		}

		$lang = $options["lang"];

		if(preg_match('/^[A-Z]/',$content_type)){ // class name, e.g. Person, Author
			$lang = null;
		}

		return $key;
	}

	static function NormalizeContentSection($content_section){
		global $ATK14_GLOBAL;

		if($content_section){
			return $content_section;
		}

		return $namespace ? DEFAULT_EDITABLE_CONTENT_SECTION."/$namespace" : DEFAULT_EDITABLE_CONTENT_SECTION; // "content/blog", "content"
	}

	function getContent(){
		return is_null($this->g("content")) ? $this->g("initial_content") : $this->g("content");
	}

	function canBeEditedByUser($user){
		if(!$user){ return false; }
		if(method_exists($user,"canEditContentSection")){
			return (bool)$user->canEditContentSection($this->getContentSection());
		}
		if(method_exists($user,"isAdmin")){
			return (bool)$user->isAdmin();
		}
		return false;
	}

	function toString(){
		return (string)$this->getContent();
	}
}
