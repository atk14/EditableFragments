<?php
class EditableFragmentHistory extends ApplicationModel{

	function __construct(){
		parent::__construct("editable_fragment_history");
	}

	function getCreatedByUser(){
		return Cache::Get("User",$this->getCreatedByUserId());
	}
}
