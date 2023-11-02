# Edit player data

<highlight>Edit player</highlight>

<include from="notes.md" element-id="urlVariable"/>
<include from="notes.md" element-id="session"/>

<api-endpoint openapi-path="./../../data.yaml" endpoint="/players/{$username}" method="PATCH">
    <request>
        <sample src="players/patchBody.json"/>
    </request>
    <response type="400">
		<sample src="error.json"/>
	</response>
</api-endpoint>

## Possible errors - 400
<list>
	<li>Enter some changes</li>
	<li>Email already taken</li>
</list>
<deflist collapsible="true">
	<def title="Password errors">
		<list>
			<li>Enter password</li>
			<li>Password must be at least 6 characters long</li>
			<li>Password must contain at least one large character</li>
			<li>Password must contain at least one small character</li>
			<li>Password must contain at least one number</li>
			<li>Password must contain at least one special character</li>
		</list>
	</def>
</deflist>