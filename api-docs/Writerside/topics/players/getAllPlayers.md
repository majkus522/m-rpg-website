# Get all players

<highlight>Get all players</highlight>

<api-endpoint openapi-path="./../../data.yaml" endpoint="/players" method="GET">
	<response type="206">
		<sample src="players/getSimpleAll.json"/>
	</response>
	<response type="400">
		<sample src="error.json"/>
	</response>
	<response type="404">
		<sample lang="JSON">
			{
				"message": "Can't find any player matching conditions"
			}
		</sample>
	</response>
</api-endpoint>

## Possible errors - 400
<list>
	<li>Unknown query string parameter <format color="BlueViolet">{$parameter}</format></li>
	<li>Unknown order parameter <format color="BlueViolet">{$value}</format></li>
</list>