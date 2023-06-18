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