{assign history_items EditableFragmentHistory::FindAll("editable_fragment_id",$editable_fragment,["order_by" => "created_at DESC, id DESC"])}

<h4>{t}Revize{/t}</h4>

<table class="table">
	{if $history_items}
	<thead>
		<tr>
			<th>{t}Datum{/t}</th>
			<th>{t}Autor{/t}</th>
		</tr>
	</thead>
	{/if}
	<tbody>
		{foreach $history_items as $history_item}
			<tr{if $history_item->getId()===$loaded_history_id} style="font-weight: bold;"{/if}>
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
		<tr{if $loaded_initial_content} style="font-weight: bold;"{/if}>
			<td colspan="2"><a href="{link_to action="edit" id=$editable_fragment load_initial_content=1 return_uri=$return_uri}">{t}zobrazit původní obsah{/t}</a></td>
		</tr>
	</tbody>
</table>
