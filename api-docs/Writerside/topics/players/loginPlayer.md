# Login player

<highlight>Login player</highlight>

<include from="notes.md" element-id="urlVariable"/>

<api-endpoint openapi-path="./../../data.yaml" endpoint="/players/{$username}/login" method="GET">
	<response type="200">
		<sample src="players/loginPlayer.json"/>
	</response>
	<response type="400">
		<sample src="error.json"/>
	</response>
	<response type="401">
		<sample lang="JSON">
			{
				"message": "Wrong password"
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
	<li>Enter player password</li>
	<li>Enter session type</li>
</list>