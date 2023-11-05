# m-rpg-website

[![view - Documentation](https://img.shields.io/badge/view-Documentation-blue?style=for-the-badge)](https://majkus522.github.io/m-rpg-docs/ 'Go to project documentation')

## API Reference

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