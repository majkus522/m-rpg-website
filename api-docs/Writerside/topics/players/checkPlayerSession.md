# Check player session

<highlight>Check player session</highlight>

<include from="notes.md" element-id="urlVariable"/>

<api-endpoint openapi-path="./../../data.yaml" endpoint="/players/{$username}/session" method="GET">
	<response type="400">
		<sample src="error.json"/>
	</response>
	<response type="401">
		<sample lang="JSON">
			{
				"message": "Incorrect session key"
			}
		</sample>
	</response>
	<response type="404">
		<sample lang="JSON">
			{
				"message": "Player doesn't exists"
			}
		</sample>
	</response>
</api-endpoint>

## Possible errors - 400
<list>
	<li>Enter player session</li>
	<li>Enter session type</li>
</list>