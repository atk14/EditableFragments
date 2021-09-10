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

		{assign history_items EditableFragmentHistory::FindAll("editable_fragment_id",$editable_fragment,["order_by" => "created_at DESC, id DESC"])}

		<table class="table">
			<thead>
				<tr>
					<th colspan="3">{t}Revize{/t}</th>
				</tr>
			</thead>
			<tbody>
				{foreach $history_items as $history_item}
					<tr>
						<td{if $history_item->getComment()} style="padding-bottom: 0px;"{/if}>
							<a href="{link_to action="edit" id=$editable_fragment load_history_id=$history_item->getId() return_uri=$return_uri}">{$history_item->getCreatedAt()|format_datetime}</a>
						</td>
						<td{if $history_item->getComment()} style="padding-bottom: 0px;"{/if}>
							{$history_item->getCreatedByUser()|default:$mdash}
						</td>
					</tr>
					{if $history_item->getComment()}
						<tr>
							<td colspan="2" style="border-top: none; padding-top: 0px;">
								<small><em>{$history_item->getComment()}</em></small>
							</td>
						</tr>
					{/if}
				{/foreach}
				<tr>
					<td colspan="2"><a href="{link_to action="edit" id=$editable_fragment load_initial_content=1 return_uri=$return_uri}">zobrazit původní obsah</a></td>
				</tr>
			</tbody>
		</table>

	</div>

</div>
