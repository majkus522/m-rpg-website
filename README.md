# m-rpg-website

## API Reference

#### Get all players (only username)

```http
  GET /api/endpoints/players/
```

| Headers                     | Type     | Description                                 |
| :-------------------------- | :------- | :------------------------------------------ |
| `Items: ${offset}-${limit}` | `int`    | **Optional**. Number of players and offset. |
| `Items: ${limit}`           | `int`    | **Optional**. Number of players.            |

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

| Headers                     | Type     | Description                                 |
| :-------------------------- | :------- | :------------------------------------------ |
| `Items: ${offset}-${limit}` | `int`    | **Optional**. Number of players and offset. |
| `Items: ${limit}`           | `int`    | **Optional**. Number of players.            |

| Query string     | Type     | Description                       |
| :--------------- | :------- | :-------------------------------- |
| `minMana=${min}` | `int`    | **Optional**. Minimal mana usage. |
| `maxMana=${max}` | `int`    | **Optional**. Maximum mana usage. |