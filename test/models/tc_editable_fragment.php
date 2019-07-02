<?php
class TcEditableFragment extends TcBase {

	function test_NormalizeKey(){
		global $ATK14_GLOBAL;

		$ATK14_GLOBAL->setValue("namespace","");
		$ATK14_GLOBAL->setValue("controller","promotions");
		$ATK14_GLOBAL->setValue("action","index");
		$ATK14_GLOBAL->setValue("lang","cs");

		$key = EditableFragment::NormalizeKey("content","text",$lang);
		$this->assertEquals("/promotions/index/content",$key);
		$this->assertEquals("cs",$lang);

		$key = EditableFragment::NormalizeKey("boss","Person",$lang);
		$this->assertEquals("/promotions/index/boss",$key);
		$this->assertEquals(null,$lang);

		$key = EditableFragment::NormalizeKey("/boss","Person",$lang);
		$this->assertEquals("/boss",$key);
		$this->assertEquals(null,$lang);

		$key = EditableFragment::NormalizeKey("","text",$lang);
		$this->assertEquals("/promotions/index/content",$key);
		$this->assertEquals("cs",$lang);
	}
}
