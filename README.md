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

#### Get player :

```http
  GET /api/endpoints/players/${username}
```

| Parameter       | Type     | Description                                |
| :-------------- | :------- | :----------------------------------------- |
| `username`      | `string` | **Required**. Username of player to fetch. |

| Headers                 | Type     | Description                               |
| :---------------------- | :------- | :---------------------------------------- |
| `Password: ${password}` | `string` | **Optional**. Needed to access full data. |

#### Add new player :

```http
  POST /api/endpoints/players/
```

| Body (JSON Object) | Type     | Description                     |
| :----------------- | :------- | :------------------------------ |
| `username`         | `string` | **Required**. Name of player.   |
| `email`            | `string` | **Required**. Players email.    |
| `password`         | `string` | **Required**. Players password. |

#### Edit player data :

```http
  PATCH /api/endpoints/players/${username}
```

| Parameter       | Type     | Description                               |
| :-------------- | :------- | :---------------------------------------- |
| `username`      | `string` | **Required**. Username of player to edit. |

| Headers                 | Type     | Description                              |
| :---------------------- | :------- | :--------------------------------------- |
| `Password: ${password}` | `string` | **Required**. Player's current password. |

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
			<td>New password</td>
			<td rowspan="4"><strong>Required</strong>. Needs one of this to work.</td>
		</tr>
		<tr>
			<td><code>"email": "${email}"</code></td>
			<td><code>string</code></td>
			<td>New email</td>
		</tr>
		<tr>
			<td><code>"level": ${level}</code></td>
			<td><code>int</code></td>
			<td>New level</td>
		</tr>
		<tr>
			<td><code>"exp": ${exp}</code></td>
			<td><code>int</code></td>
			<td>New exp</td>
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

| Headers                     | Type     | Description                                 | Accepted values             |
| :-------------------------- | :------- | :------------------------------------------ | :-------------------------- |
| `Items: ${offset}-${limit}` | `int`    | **Optional**. Number of players and offset. | [0 - &infin;]-[1 - &infin;] |
| `Items: ${limit}`           | `int`    | **Optional**. Number of players.            | 1 - &infin;                 |

| Query string     | Type     | Description                                        | Accepted values         |
| :--------------- | :------- | :------------------------------------------------- | :---------------------- |
| `minMana=${min}` | `int`    | **Optional**. Minimal mana usage.                  | 0 - &infin;             |
| `maxMana=${max}` | `int`    | **Optional**. Maximal mana usage.                  | 0 - &infin;             |
| `order=${order}` | `string` | **Optional**. Order circles by mana usage and etc. | `"mana-desc"`, `"mana"` |

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