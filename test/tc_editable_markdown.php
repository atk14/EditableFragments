<?php
class TcEditableMarkdown extends TcBase {	

	function test(){
		Atk14Require::Helper("block.editable_markdown");
		
		$smarty = Atk14Utils::GetSmarty();
		$repeat = false;

		$src = "- Line 1\n- Line 2";
		$exp = "<ul>
<li>Line 1</li>
<li>Line 2</li>
</ul>";
		$output = smarty_block_editable_markdown(["key" => "testing"],$src,$smarty,$repeat);
		$this->assertEquals($exp,$output);

		$admin = User::FindById(1);
		$smarty->assign("logged_user",$admin);

		$output = smarty_block_editable_markdown(["key" => "testing"],$src,$smarty,$repeat);
		$this->assertStringContains($exp,$output);
		$this->assertTrue(!!preg_match('/^<div class="editable"/',$output));
		$this->assertTrue(!!preg_match('/<\/div>$/',$output));
	}
}
