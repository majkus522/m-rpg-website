# Create new player

<highlight>Create new player</highlight>

<api-endpoint openapi-path="./../../data.yaml" endpoint="/players" method="POST">
    <request>
        <sample src="players/postBody.json"/>
    </request>
    <response type="201">
		<sample src="players/loginPlayer.json"/>
	</response>
    <response type="400">
		<sample src="error.json"/>
	</response>
</api-endpoint>

## Possible errors - 400
<deflist collapsible="true">
	<def title="Username errors">
		<list>
			<li>Enter username</li>
			<li>Player already exists</li>
			<li>Username is too short</li>
			<li>Username is too long</li>
			<li>Username can only contain letters, numbers and underscore</li>
		</list>
	</def>
	<def title="Email errors">
		<list>
			<li>Enter email</li>
			<li>Email already taken</li>
			<li>Incorrect email</li>
		</list>
	</def>
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