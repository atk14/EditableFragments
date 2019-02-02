<?php
class EditableFragmentsController extends AdminController{

	function _before_filter(){
		$this->_find("editable_fragment");
	}

	function edit(){
		$this->page_title = _("Editace obsahu části stránky");

		$ef = $this->editable_fragment;
		$c_type = $ef->getContentType();

		//if(!$this->logged_user->canEditContentSection($ef->getKey())){
		//	$this->_execute_action("error403");
		//	return;
		//}

		$has_iobjects = false;
		$create_new_record_url = $edit_record_url = "";
		$create_new_record_title = _("Vytvořit nový záznam");
		$edit_record_title = _("Editovat záznam");
		switch($c_type){
			case "title":
				$this->form->add_field("content",new CharField(array(
					"label" => _("Obsah"),
					"initial" => $ef->getContent(),
					"null_empty_output" => false,
				)));
				break;
			case "text":
				$this->form->add_field("content",new TextField(array(
					"label" => _("Obsah"),
					"initial" => $ef->getContent(),
					"null_empty_output" => false,
					"required" => false,
				)));
				break;
			case "Person":
					$this->form->add_field("content",new PersonField(array(
						"label" => _("Vyberte osobu"),
						"initial" => $ef->getContent(),
					)));
					$create_new_record_url = $this->_link_to("people/create_new");
					$create_new_record_title = _("Založit novou osobu");
					$edit_record_url = $this->_link_to(array("action" => "people/edit", "id" => $ef->getContent()));
					$edit_record_title = _("Editovat vybranou osobu");
					break;
			case "markdown":
				$has_iobjects = true;
				$this->form->add_field("content",new MarkdownField(array(
					"label" => _("Obsah"),
					"initial" => $ef->getContent(),
					"null_empty_output" => false,
					"required" => false,
				)));
				break;
			
			default:

				throw new Exception("Unknown content type: ".$ef->getContentType());
		}
		$this->form->add_comment_field(); // chceme, aby byl komentar jako posledni pole

		$this->tpl_data["has_iobjects"] = $has_iobjects;
		$this->tpl_data["create_new_record_url"] = $create_new_record_url;
		$this->tpl_data["create_new_record_title"] = $create_new_record_title;
		$this->tpl_data["edit_record_url"] = $edit_record_url;
		$this->tpl_data["edit_record_title"] = $edit_record_title;

		$this->_save_return_uri();

		if($this->request->post() && ($d = $this->form->validate($this->params))){

			if(is_object($d["content"])){
				$d["content"] = (string)$d["content"]->getId();
			}

			if((string)$d["content"]!==$ef->getContent()){
				$ef->s(array(
					"content" => $d["content"],
				));
				EditableFragmentHistory::CreateNewRecord(array(
					"editable_fragment_id" => $ef,
					"content" => $d["content"],
					"comment" => $d["comment"],
				));
				$this->flash->notice(_("Změny byly uloženy"));
			}else{
				$this->flash->notice(_("Obsah byl ponechán beze změn"));
			}

			$this->_redirect_back();
		}
	}
}
