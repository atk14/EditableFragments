<?php
class EditableFragmentsController extends AdminController{

	var $editable_fragment;

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

		$initial = $ef->getContent();
		if($this->params->getString("load_initial_content")){
			$initial = $ef->getInitialContent();
			$this->tpl_data["loaded_initial_content"] = true;
		}
		if(!is_null($hist_id = $this->params->getInt("load_history_id")) && ($ef_history = EditableFragmentHistory::FindFirst("editable_fragment_id",$ef,"id",$hist_id))){
			$initial = $ef_history->getContent();
			$this->tpl_data["loaded_history_id"] = $ef_history->getId();
		}

		switch($c_type){
			case "title":
			case "string":
				$this->form->add_field("content",new CharField(array(
					"label" => _("Obsah"),
					"initial" => $initial,
					"required" => false,
				)));
				break;
			case "text":
				$this->form->add_field("content",new TextField(array(
					"label" => _("Obsah"),
					"initial" => $initial,
					"required" => false,
				)));
				break;
			case "Person":
					$this->form->add_field("content",new PersonField(array(
						"label" => _("Vyberte osobu"),
						"initial" => $initial,
					)));
					$create_new_record_url = $this->_link_to("people/create_new");
					$create_new_record_title = _("Založit novou osobu");
					$edit_record_url = $this->_link_to(array("action" => "people/edit", "id" => $initial));
					$edit_record_title = _("Editovat vybranou osobu");
					break;
			case "markdown":
				$has_iobjects = true;
				$this->form->add_field("content",new MarkdownField(array(
					"label" => _("Obsah"),
					"initial" => $initial,
					"required" => false,
				)));
				break;
			case "pupiq_image":
				$this->form->add_field("content", new PupiqImageField(array(
					"label" => _("Obrázek"),
					"initial" => $initial,
					"required" => false,
				)));
				break;
			
			default:

				throw new Exception("Unknown content type: ".$ef->getContentType());
		}
		$this->form->add_comment_field(); // chceme, aby byl komentar jako posledni pole

		if($has_iobjects && !class_exists("Iobject")){
			$has_iobjects = false;
		}

		$this->tpl_data["has_iobjects"] = $has_iobjects;
		$this->tpl_data["create_new_record_url"] = $create_new_record_url;
		$this->tpl_data["create_new_record_title"] = $create_new_record_title;
		$this->tpl_data["edit_record_url"] = $edit_record_url;
		$this->tpl_data["edit_record_title"] = $edit_record_title;

		$this->_save_return_uri();
		$this->tpl_data["return_uri"] = $this->_get_return_uri();

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
