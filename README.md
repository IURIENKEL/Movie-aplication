
## 📡 Documentação da API

Abaixo estão as principais rotas disponíveis na API para autenticação de usuários e gerenciamento de filmes.

---

### 🔐 Autenticação de Usuário

#### POST `/register`

Realiza o cadastro de um novo usuário.

##### Corpo (JSON)

```json
{
  "name": "iuri",
  "email": "iuri@gmail.com",
  "password": "12345678"
}
```

##### Resposta (200 OK)

```json
{
  "success": true,
  "message": "Cadastro realizado com sucesso!",
  "data": {
    "name": "iuri",
    "email": "iuri@gmail.com",
    "updated_at": "2025-07-02T14:36:43.000000Z",
    "created_at": "2025-07-02T14:36:43.000000Z",
    "id": 7
  }
}
```

---

#### POST `/login`

Realiza o login do usuário e retorna o token de autenticação Sanctum.

##### Corpo (JSON)

```json
{
  "email": "iuri@gmail.com",
  "password": "12345678"
}
```

##### Resposta (200 OK)

```json
{
  "success": true,
  "message": "Login realizado com sucesso!",
  "data": {
    "access_token": "seu_token_aqui",
    "user": {
      "id": 1,
      "name": "iuri",
      "email": "iuri@gmail.com",
      "email_verified_at": null,
      "created_at": "2025-07-01T00:47:10.000000Z",
      "updated_at": "2025-07-01T00:47:10.000000Z"
    }
  }
}
```

---

### 🔒 Rotas Protegidas  
> Necessário token no header: `Authorization: Bearer {token}`

#### POST `/logout`

Realiza logout do usuário autenticado.

---

#### GET `/user`

Retorna os dados do usuário autenticado.

---

#### POST `/movies/favorite`

Adiciona um filme à lista de favoritos do usuário.

##### Corpo (JSON)

```json
{
  "movie_id": 552524,
  "movie_details": {
    "adult": false,
    "backdrop_path": "/7Zx3wDG5bBtcfk8lcnCWDOLM4Y4.jpg",
    "genre_ids": [10751, 878, 35, 12],
    "id": 552524,
    "original_language": "en",
    "original_title": "Lilo & Stitch",
    "overview": "Stitch, um alienígena, chega ao planeta Terra após fugir de sua prisão e tenta se passar por um cachorro para se camuflar...",
    "popularity": 385.8026,
    "poster_path": "/bLQN6DUNYN4NVzSY3Q53JwBRlgV.jpg",
    "release_date": "2025-05-17",
    "title": "Lilo & Stitch",
    "video": false,
    "vote_average": 7.093,
    "vote_count": 761
  }
}
```

##### Resposta (200 OK)

```json
{
  "success": true,
  "message": "Filme registrado como favorito",
  "data": {
    "movie_id": 1,
    "user_id": 1,
    "movie_details": "...",
    "updated_at": "2025-07-02T03:59:16.000000Z",
    "created_at": "2025-07-02T03:59:16.000000Z",
    "id": 50
  }
}
```

---

#### DELETE `/movies/favorite/{movie_id}`

Remove um filme da lista de favoritos.

##### Parâmetro URL

- `movie_id`: ID do filme a ser removido.

##### Resposta (200 OK)

```json
{
  "success": true,
  "message": "Filme removido como favorito"
}
```

---

#### GET `/movies/favorites`

Lista todos os filmes favoritos do usuário autenticado.

---

#### GET `/movies/popular`

Lista os filmes populares.

##### Resposta (exemplo)

```json
{
  "page": 1,
  "results": [
    {
      "id": 574475,
      "title": "Premonição 6: Laços de Sangue",
      "overview": "...",
      "genre_ids": [27, 9648],
      "release_date": "2025-05-14",
      "vote_average": 7.185
    }
  ]
}
```

---

#### GET `/movies/top_rated`

Lista os filmes com melhores avaliações.

---

#### GET `/movies/search?query=batman`

Busca filmes pelo título (query param `query`).

---

#### API Resource `/movies`

Oferece operações REST completas para filmes:

- `GET /movies`
- `GET /movies/{id}`
- `POST /movies`
- `PUT /movies/{id}`
- `DELETE /movies/{id}`
