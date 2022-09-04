<?php
class TcEditablePupiqImage extends TcBase {

	function test(){
		Atk14Require::Helper("block.editable_pupiq_image");

		$smarty = Atk14Utils::GetSmarty();
		$repeat = false;

		$src = '<img src="https://i.pupiq.net/i/65/65/27e/2927e/1272x920/9cUpr1_800x578_26254b6a433fc4a9.jpg" class="img-fluid">';

		// geometry: 200
		$output = smarty_block_editable_pupiq_image(["key" => "/footer/image", "geometry" => "200"],$src,$smarty,$repeat);
		$this->assertEquals('<img src="http://i.pupiq.net/i/65/65/27e/2927e/1272x920/9cUpr1_200x144_6cbea9b1e8df0b1b.jpg" class="img-fluid">',$output);

		// geometry: 300x300xcrop
		$output = smarty_block_editable_pupiq_image(["key" => "/footer/image", "geometry" => "300x300xcrop"],$src,$smarty,$repeat);
		$this->assertEquals('<img src="http://i.pupiq.net/i/65/65/27e/2927e/1272x920/9cUpr1_300x300xc_f066d11e41f75230.jpg" class="img-fluid">',$output);

		// set new image
		$ef = EditableFragment::FindFirst("key","/footer/image");
		$ef->s("content","https://i.pupiq.net/i/65/65/27c/2927c/1272x920/JuSG6C_800x578_0cecc732df82ad65.jpg");

		// geometry: 200
		$output = smarty_block_editable_pupiq_image(["key" => "/footer/image", "geometry" => "200"],$src,$smarty,$repeat);
		$this->assertEquals('<img src="http://i.pupiq.net/i/65/65/27c/2927c/1272x920/JuSG6C_200x144_f70c6726873b8607.jpg" class="img-fluid">',$output);
	}
}
