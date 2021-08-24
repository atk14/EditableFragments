<?php
class EditForm extends AdminForm{

	function set_up(){
		$this->set_button_text(_("Uložit změny"));
	}

	function add_comment_field(){
		$this->add_field("comment",new CharField(array(
			"label" => _("Zde můžete volitelně napsat důvod úpravy"),
			"max_length" => 255,
			"required" => false,
			"initial" => "",
		)));
	}

	function clean(){
		list($err,$d) = parent::clean();

		if(!$this->has_errors()){
			$d["content"] = (string)$d["content"];
		}

		return array($err,$d);
	}
}
