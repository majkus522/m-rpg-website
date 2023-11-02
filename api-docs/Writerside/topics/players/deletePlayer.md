# Delete player

<highlight>Delete player</highlight>

<include from="notes.md" element-id="urlVariable"/>
<include from="notes.md" element-id="session"/>

<api-endpoint openapi-path="./../../data.yaml" endpoint="/players/{$username}" method="DELETE">
	<response type="400">
		<sample src="error.json"/>
	</response>
</api-endpoint>