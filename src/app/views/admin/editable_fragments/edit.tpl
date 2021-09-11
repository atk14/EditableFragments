<div class="row">

	<div class="col-sm-8">

		<h1>{$page_title}</h1>

		<ul>
			<li>{t}Klíč{/t}: {$editable_fragment->getKey()}</li>
			<li>{t}Umístění{/t}: {$editable_fragment->getContentSection()}</li>
			<li>{t}Jazyk{/t}: {!$editable_fragment->getLang()|h|default:"&mdash;"}</li>
		</ul>

		{render partial="shared/form"}

		{if $create_new_record_url || $edit_record_url}
			<hr>
			<ul>
				{if $create_new_record_url}
				<li><a href="{$create_new_record_url}"><i class="glyphicon glyphicon-plus"></i> {$create_new_record_title}</a></li>
				{/if}

				{if $edit_record_url}
				<li><a href="{$edit_record_url}"><i class="glyphicon glyphicon-edit"></i> {$edit_record_title}</a></li>
				{/if}
			</ul>
		{/if}

		{if $has_iobjects}
			{render partial="shared/iobjects" object=$editable_fragment}
		{/if}

	</div>
	<div  class="col-sm-4">

		{render partial="sidebar"}

	</div>

</div>
