# m-rpg-website

## API Reference

#### Get all players (only username)

```http
  GET /api/endpoints/players/
```

| Headers                     | Type     | Description                                 | Accepted values             |
| :-------------------------- | :------- | :------------------------------------------ | :-------------------------- |
| `Items: ${offset}-${limit}` | `int`    | **Optional**. Number of players and offset. | [0 - &infin;]-[1 - &infin;] |
| `Items: ${limit}`           | `int`    | **Optional**. Number of players.            | 1 - &infin;                 |

| Query string      | Type     | Description                                   | Accepted values           |
| :---------------- | :------- | :-------------------------------------------- | :------------------------ |
| `minLevel=${min}` | `int`    | **Optional**. Minimal player level.           | 0 - &infin;               |
| `maxLevel=${max}` | `int`    | **Optional**. Maximal player level.           | 0 - &infin;               |
| `order=${order}`  | `string` | **Optional**. Order players by level and etc. | `"level-desc"`, `"level"` |

#### Get player

```http
  GET /api/endpoints/players/${username}
```

| Parameter       | Type     | Description                                |
| :-------------- | :------- | :----------------------------------------- |
| `username`      | `string` | **Required**. Username of player to fetch. |

| Headers                 | Type     | Description                                      |
| :---------------------- | :------- | :----------------------------------------------- |
| `Password: ${password}` | `string` | **Optional**. Needed to access full player data. |

#### Get all circles (only public ones)

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

#### Send password recovery email

```http
  GET /api/controllers/password-recovery/
```

<table data-sourcepos="47:1-51:58">
	<thead>
		<tr data-sourcepos="47:1-47:110">
			<th>Query string</th>
			<th>Type</th>
			<th>Notes</th>
		</tr>
	</thead>
	<tbody>
		<tr data-sourcepos="49:1-49:110">
			<td><code>username=${username}</code></td>
			<td><code>string</code></td>
			<td rowspan="2"><strong>Required</strong>. Needs one of this to work.</td>
		</tr>
		<tr data-sourcepos="49:1-49:110">
			<td><code>email=${email}</code></td>
			<td><code>string</code></td>
		</tr>
	</tbody>
</table>