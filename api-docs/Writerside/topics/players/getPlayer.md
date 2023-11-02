# Get single player

<highlight>Get single player</highlight>

<note title="Url variable">
	If the variable in the url is not present it will be redirected to <a href="getAllPlayers.md">this endpoint</a>.
</note>

<api-endpoint openapi-path="./../../data.yaml" endpoint="/players/{$username}" method="GET">
	<response type="200">
		<sample src="players/getSingle.json"/>
	</response>
	<response type="206">
		<sample src="players/getSimpleSingle.json"/>
	</response>
	<response type="404">
		<sample lang="JSON">
			{
				"message": "Player doesn't exists"
			}
		</sample>
	</response>
</api-endpoint>