# m-rpg-website

## API Reference

&nbsp;
## Players

#### Get all players (only username) :

```http
  GET /api/players/
```

| Headers                     | Type     | Description                                 | Accepted values             |
| :-------------------------- | :------- | :------------------------------------------ | :-------------------------- |
| `Items: ${offset}-${limit}` | `int`    | **Optional**. Number of players and offset. | [0 - &infin;]-[1 - &infin;] |
| `Items: ${limit}`           | `int`    | **Optional**. Number of players.            | 1 - &infin;                 |

| Query string      | Type     | Description                                   | Accepted values |
| :---------------- | :------- | :-------------------------------------------- | :-------------- |
| `minLevel=${min}` | `int`    | **Optional**. Minimal player level.           | 1 - &infin;     |
| `maxLevel=${max}` | `int`    | **Optional**. Maximal player level.           | 1 - &infin;     |
| `minMoney=${max}` | `float`  | **Optional**. Minimal player capital.         | 0 - &infin;     |
| `minMoney=${max}` | `float`  | **Optional**. Maximal player capital.         | 0 - &infin;     |
| `minStr=${max}`   | `int`    | **Optional**. Minimal player strength.        | 0 - &infin;     |
| `maxStr=${max}`   | `int`    | **Optional**. Maximal player strength.        | 0 - &infin;     |
| `minAgl=${max}`   | `int`    | **Optional**. Minimal player agility.         | 0 - &infin;     |
| `maxAgl=${max}`   | `int`    | **Optional**. Maximal player agility.         | 0 - &infin;     |
| `minChr=${max}`   | `int`    | **Optional**. Minimal player charisma.        | 0 - &infin;     |
| `maxChr=${max}`   | `int`    | **Optional**. Maximal player charisma.        | 0 - &infin;     |
| `minIntl=${max}`  | `int`    | **Optional**. Minimal player intelligence.    | 0 - &infin;     |
| `maxIntl=${max}`  | `int`    | **Optional**. Maximal player intelligence.    | 0 - &infin;     |
| `order=${order}`  | `string` | **Optional**. Order players by level and etc. | `"level-desc"`, `"level"`, `"money"`, `"money-desc"`, `"str"`, `"str-desc"`, `"agl"`, `"agl-desc"`, `"chr"`, `"chr-desc"`, `"intl"`, `"intl-desc"` |

#### Get player (only username) :

```http
  GET /api/players/${username}
```

| Parameter       | Type     | Description                                |
| :-------------- | :------- | :----------------------------------------- |
| `username`      | `string` | **Required**. Username of player to fetch. |

<table>
	<thead>
		<tr>
			<th>Headers</th>
			<th>Type</th>
			<th>Description</th>
			<th>Notes</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><code>Session-Key: ${key}</code></td>
			<td><code>string</code></td>
			<td><strong>Optional</strong>. Session key</td>
			<td rowspan="2">Needed to access full data.</td>
		</tr>
		<tr>
			<td><code>Session-Type: ${type}</code></td>
			<td><code>string</code></td>
			<td><strong>Optional</strong>. Session type</td>
		</tr>
	</tbody>
</table>

#### Login player (recive session key) :

```http
  GET /api/players/${username}/logged
```

| Parameter       | Type     | Description                                |
| :-------------- | :------- | :----------------------------------------- |
| `username`      | `string` | **Required**. Username of player to fetch. |

| Headers                 | Type     | Description                         |
| :---------------------- | :------- | :---------------------------------- |
| `Session-Type: ${type}` | `string` | **Required**. Session type.         |
| `Password: ${password}` | `string` | **Required**. Player pasword.       |
| `Temp: ${temp}`         | `bool`   | **Optional**. Is session temporary. |

#### Check player session :

```http
  GET /api/players/${username}/session
```

| Parameter       | Type     | Description                                |
| :-------------- | :------- | :----------------------------------------- |
| `username`      | `string` | **Required**. Username of player to fetch. |

| Headers                 | Type     | Description                 |
| :---------------------- | :------- | :-------------------------- |
| `Session-Type: ${type}` | `string` | **Required**. Session type. |
| `Session-Key: ${key}`   | `string` | **Required**. Session key.  |

#### Add new player :

```http
  POST /api/players/
```

| Body (JSON Object)          | Type     | Description                     |
| :-------------------------- | :------- | :------------------------------ |
| `"username": "${username}"` | `string` | **Required**. Name of player.   |
| `"email": "${email}"`       | `string` | **Required**. Players email.    |
| `"password": "${password}"` | `string` | **Required**. Players password. |

#### Edit player data :

```http
  PATCH /api/players/${username}
```

| Parameter       | Type     | Description                               |
| :-------------- | :------- | :---------------------------------------- |
| `username`      | `string` | **Required**. Username of player to edit. |

| Headers                 | Type     | Description                 |
| :---------------------- | :------- | :-------------------------- |
| `Session-Key: ${key}`   | `string` | **Required**. Session key.  |
| `Session-Type: ${type}` | `string` | **Required**. Session type. |

<table>
	<thead>
		<tr>
			<th>Body (JSON Object)</th>
			<th>Type</th>
			<th>Description</th>
			<th>Notes</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><code>"password": "${password}"</code></td>
			<td><code>string</code></td>
			<td><strong>Optional</strong>. New password</td>
			<td rowspan="4">Needs one of this to work.</td>
		</tr>
		<tr>
			<td><code>"email": "${email}"</code></td>
			<td><code>string</code></td>
			<td><strong>Optional</strong>. New email</td>
		</tr>
		<tr>
			<td><code>"level": ${level}</code></td>
			<td><code>int</code></td>
			<td><strong>Optional</strong>. New level</td>
		</tr>
		<tr>
			<td><code>"exp": ${exp}</code></td>
			<td><code>int</code></td>
			<td><strong>Optional</strong>. New exp</td>
		</tr>
	</tbody>
</table>

#### Delete player :

```http
  DELETE /api/players/${username}
```

| Parameter       | Type     | Description                                 |
| :-------------- | :------- | :------------------------------------------ |
| `username`      | `string` | **Required**. Username of player to delete. |

| Headers                 | Type     | Description                 |
| :---------------------- | :------- | :-------------------------- |
| `Session-Type: ${type}` | `string` | **Required**. Session type. |
| `Session-Key: ${key}`   | `string` | **Required**. Session key.  |

---
&nbsp;
## Skills

#### Get all players skills :

```http
  GET /api/skills/${username}
```

| Parameter       | Type     | Description                                     |
| :-------------- | :------- | :---------------------------------------------- |
| `username`      | `string` | **Required**. Username of player to get skills. |

| Headers                     | Type     | Description                                |
| :-------------------------- | :------- | :----------------------------------------- |
| `Session-Key: ${key}`       | `string` | **Required**. Session key.                 |
| `Session-Type: ${type}`     | `string` | **Required**. Session type.                |
| `Items: ${offset}-${limit}` | `int`    | **Optional**. Number of skills and offset. |
| `Items: ${limit}`           | `int`    | **Optional**. Number of skills.            |

| Query string         | Type            | Description                                      | Accepted values                                              |
| :------------------- | :-------------- | :----------------------------------------------- | :----------------------------------------------------------- |
| `rarity[]=${rarity}` | `array<string>` | **Optional**. Skills rarity.                     | `"common"`, `"extra"`, `"unique"`, `"ultimate"`, `"unknown"` |
| `order=${order}`     | `string`        | **Optional**. Order skills by rarity and etc.    | `"rarity"`, `"rarity-desc"`                                  |
| `toggle=${toggle}`   | `bool`          | **Optional**. Check if skill is enabled or not.  | `true`, `false`                                              |
| `search=%{search}`   | `string`        | **Optional**. Search skill by it's label or name |

#### Check if player has skill :

```http
  GET /api/skills/${username}/${skill}
```

| Parameter       | Type     | Description                       |
| :-------------- | :------- | :-------------------------------- |
| `username`      | `string` | **Required**. Username of player. |
| `skill`         | `string` | **Required**. Skill to check.     |

| Headers                     | Type     | Description                 |
| :-------------------------- | :------- | :-------------------------- |
| `Session-Key: ${key}`       | `string` | **Required**. Session key.  |
| `Session-Type: ${type}`     | `string` | **Required**. Session type. |

#### Toggle skill :

```http
  PATCH /api/skills/${username}/${skill}
```

| Parameter       | Type     | Description                       |
| :-------------- | :------- | :-------------------------------- |
| `username`      | `string` | **Required**. Username of player. |
| `skill`         | `string` | **Required**. Skill to toggle.    |

| Headers                     | Type     | Description                 |
| :-------------------------- | :------- | :-------------------------- |
| `Session-Key: ${key}`       | `string` | **Required**. Session key.  |
| `Session-Type: ${type}`     | `string` | **Required**. Session type. |

| Body (bool) | Type     | Description                           |
| :---------- | :------- | :------------------------------------ |
| `${value}`  | `bool`   | **Required**. New skill toggle value. |

---
&nbsp;
## Fake status

#### Get player fake status :

```http
  GET /api/fake-status/${username}
```

| Parameter       | Type     | Description                       |
| :-------------- | :------- | :-------------------------------- |
| `username`      | `string` | **Required**. Username of player. |

| Headers                     | Type     | Description                 |
| :-------------------------- | :------- | :-------------------------- |
| `Session-Key: ${key}`       | `string` | **Required**. Session key.  |
| `Session-Type: ${type}`     | `string` | **Required**. Session type. |

#### Create players fake status :

```http
  POST /api/fake-status/${username}
```

| Parameter       | Type     | Description                       |
| :-------------- | :------- | :-------------------------------- |
| `username`      | `string` | **Required**. Username of player. |

| Headers                     | Type     | Description                 |
| :-------------------------- | :------- | :-------------------------- |
| `Session-Key: ${key}`       | `string` | **Required**. Session key.  |
| `Session-Type: ${type}`     | `string` | **Required**. Session type. |

| Body (JSON Object)  | Type     | Description                                |
| :------------------ | :------- | :----------------------------------------- |
| `"level": ${level}` | `int`    | **Required**. Fake level of player.        |
| `"money": ${money}` | `float`  | **Required**. Fake money of player.        |
| `"str": ${str}`     | `int`    | **Required**. Fake strength of player.     |
| `"agl": ${agl}`     | `int`    | **Required**. Fake agility of player.      |
| `"chr": ${chr}`     | `int`    | **Required**. Fake charisma of player.     |
| `"intl": ${intl}`   | `int`    | **Required**. Fake intelligence of player. |

#### Change players fake status :

```http
  PATCH /api/fake-status/${username}
```

| Parameter       | Type     | Description                       |
| :-------------- | :------- | :-------------------------------- |
| `username`      | `string` | **Required**. Username of player. |

| Headers                     | Type     | Description                 |
| :-------------------------- | :------- | :-------------------------- |
| `Session-Key: ${key}`       | `string` | **Required**. Session key.  |
| `Session-Type: ${type}`     | `string` | **Required**. Session type. |

<table>
	<thead>
		<tr>
			<th>Body (JSON Object)</th>
			<th>Type</th>
			<th>Description</th>
			<th>Notes</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><code>"level": ${level}</code></td>
			<td><code>int</code></td>
			<td><strong>Optional</strong>. New fake level of player</td>
			<td rowspan="6">Needs one of this to work.</td>
		</tr>
		<tr>
			<td><code>"money": ${money}</code></td>
			<td><code>float</code></td>
			<td><strong>Optional</strong>. New fake money of player</td>
		</tr>
		<tr>
			<td><code>"str": ${str}</code></td>
			<td><code>int</code></td>
			<td><strong>Optional</strong>. New fake strength of player</td>
		</tr>
		<tr>
			<td><code>"agl": ${agl}</code></td>
			<td><code>int</code></td>
			<td><strong>Optional</strong>. New fake agility of player</td>
		</tr>
		<tr>
			<td><code>"chr": ${chr}</code></td>
			<td><code>int</code></td>
			<td><strong>Optional</strong>. New fake charisma of player</td>
		</tr>
		<tr>
			<td><code>"intl": ${intl}</code></td>
			<td><code>int</code></td>
			<td><strong>Optional</strong>. New fake intelligence of player</td>
		</tr>
	</tbody>
</table>

---
&nbsp;
## Password recovery

#### Send password recovery email :

```http
  GET /api/password-recovery/
```

<table>
	<thead>
		<tr>
			<th>Query string</th>
			<th>Type</th>
			<th>Notes</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><code>username=${username}</code></td>
			<td><code>string</code></td>
			<td rowspan="2"><strong>Required</strong>. Needs one of this to work.</td>
		</tr>
		<tr>
			<td><code>email=${email}</code></td>
			<td><code>string</code></td>
		</tr>
	</tbody>
</table>

#### Set players new password :

```http
  PATCH /api/password-recovery/${code}
```

| Parameter   | Type     | Description                          |
| :---------- | :------- | :----------------------------------- |
| `code`      | `string` | **Required**. Code recived in email. |

| Body (JSON Object) | Type     | Description                 |
| :----------------- | :------- | :-------------------------- |
| `password`         | `string` | **Required**. New password. |
