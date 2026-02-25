<?php
class EditableFragmentsUtils {

	static function RenderIcon($icon){
		Atk14Require::Helper("modifier.icon");
		if(function_exists("smarty_modifier_icon")){
			return smarty_modifier_icon($icon);
		}
		return sprintf('<i class="glyphicon glyphicon-%s"></i>',$icon);
	}
}
