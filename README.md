# m-rpg-website

## API Reference

&nbsp;
## Players

#### Get all players (only username) :

```http
  GET /api/endpoints/players/
```

| Headers                     | Type     | Description                                 | Accepted values             |
| :-------------------------- | :------- | :------------------------------------------ | :-------------------------- |
| `Items: ${offset}-${limit}` | `int`    | **Optional**. Number of players and offset. | [0 - &infin;]-[1 - &infin;] |
| `Items: ${limit}`           | `int`    | **Optional**. Number of players.            | 1 - &infin;                 |

| Query string      | Type     | Description                                   | Accepted values           |
| :---------------- | :------- | :-------------------------------------------- | :------------------------ |
| `minLevel=${min}` | `int`    | **Optional**. Minimal player level.           | 1 - &infin;               |
| `maxLevel=${max}` | `int`    | **Optional**. Maximal player level.           | 1 - &infin;               |
| `minMoney=${max}` | `float`  | **Optional**. Minimal player capital.         | 0 - &infin;               |
| `minMoney=${max}` | `float`  | **Optional**. Maximal player capital.         | 0 - &infin;               |
| `order=${order}`  | `string` | **Optional**. Order players by level and etc. | `"level-desc"`, `"level"` |

#### Get player (only username) :

```http
  GET /api/endpoints/players/${username}
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

#### Login player :

```http
  GET /api/endpoints/players/${username}/logged
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
  GET /api/endpoints/players/${username}/session
```

| Parameter       | Type     | Description                                |
| :-------------- | :------- | :----------------------------------------- |
| `username`      | `string` | **Required**. Username of player to fetch. |

| Headers                 | Type     | Description                   |
| :---------------------- | :------- | :---------------------------- |
| `Session-Type: ${type}` | `string` | **Required**. Session type.   |
| `Session-Key: ${key}`   | `string` | **Required**. Session key. |

#### Add new player :

```http
  POST /api/endpoints/players/
```

| Body (JSON Object)          | Type     | Description                     |
| :-------------------------- | :------- | :------------------------------ |
| `"username": "${username}"` | `string` | **Required**. Name of player.   |
| `"email": "${email}"`       | `string` | **Required**. Players email.    |
| `"password": "${password}"` | `string` | **Required**. Players password. |

#### Edit player data :

```http
  PATCH /api/endpoints/players/${username}
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

---
&nbsp;
## Circles

#### Get all circles (only public ones) :

```http
  GET /api/endpoints/circles/
```

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
			<td><code>Items: ${offset}-${limit}</code></td>
			<td><code>int</code></td>
			<td><strong>Optional</strong>. Number of players and offset.</td>
			<td><strong>Value:</strong> [0 - &infin;]-[1 - &infin;]</td>
		</tr>
		<tr>
			<td><code>Items: ${limit}</code></td>
			<td><code>int</code></td>
			<td><strong>Optional</strong>. Number of players.</td>
			<td><strong>Value:</strong> 1 - &infin;</td>
		</tr>
		<tr>
			<td><code>Session-Key: ${key}</code></td>
			<td><code>string</code></td>
			<td><strong>Optional</strong>. Session key.</td>
			<td rowspan="3">Needed to access players private circles.</td>
		</tr>
		<tr>
			<td><code>Session-Type: ${type}</code></td>
			<td><code>string</code></td>
			<td><strong>Optional</strong>. Session type.</td>
		</tr>
		<tr>
			<td><code>Player: ${limit}</code></td>
			<td><code>string</code></td>
			<td><strong>Optional</strong>. Players username.</td>
		</tr>
	</tbody>
</table>

| Query string     | Type     | Description                                        | Accepted values         |
| :--------------- | :------- | :------------------------------------------------- | :---------------------- |
| `minMana=${min}` | `int`    | **Optional**. Minimal mana usage.                  | 0 - &infin;             |
| `maxMana=${max}` | `int`    | **Optional**. Maximal mana usage.                  | 0 - &infin;             |
| `order=${order}` | `string` | **Optional**. Order circles by mana usage and etc. | `"mana-desc"`, `"mana"` |

#### Get circle (only public ones) :

```http
  GET /api/endpoints/circles/${slug}
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `slug`    | `string` | **Required**. Circle slug. |

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
			<td><strong>Optional</strong>. Session key.</td>
			<td rowspan="3">Needed to access players private circles.</td>
		</tr>
		<tr>
			<td><code>Session-Type: ${type}</code></td>
			<td><code>string</code></td>
			<td><strong>Optional</strong>. Session type.</td>
		</tr>
		<tr>
			<td><code>Player: ${limit}</code></td>
			<td><code>string</code></td>
			<td><strong>Optional</strong>. Players username.</td>
		</tr>
	</tbody>
</table>

---
&nbsp;
## Skills

#### Get all players skills :

```http
  GET /api/endpoints/skills/${username}
```

| Parameter       | Type     | Description                                     |
| :-------------- | :------- | :---------------------------------------------- |
| `username`      | `string` | **Required**. Username of player to get skills. |

| Headers                 | Type     | Description                 |
| :---------------------- | :------- | :-------------------------- |
| `Session-Key: ${key}`   | `string` | **Required**. Session key.  |
| `Session-Type: ${type}` | `string` | **Required**. Session type. |

| Query string         | Type            | Description                                   | Accepted values                                 |
| :------------------- | :-------------- | :-------------------------------------------- | :---------------------------------------------- |
| `rarity[]=${rarity}` | `array[string]` | **Optional**. Skills rarity.                  | `"common"`, `"extra"`, `"unique"`, `"ultimate"` |
| `order=${order}`     | `string`        | **Optional**. Order skills by rarity and etc. | `"rarity"`, `"rarity-desc"`                     |

---
&nbsp;
## Password recovery

#### Send password recovery email :

```http
  GET /api/controllers/password-recovery/
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
  PATCH /api/controllers/password-recovery/${code}
```

| Parameter   | Type     | Description                          |
| :---------- | :------- | :----------------------------------- |
| `code`      | `string` | **Required**. Code recived in email. |

| Body (JSON Object) | Type     | Description                 |
| :----------------- | :------- | :-------------------------- |
| `password`         | `string` | **Required**. New password. |