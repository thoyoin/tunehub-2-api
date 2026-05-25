# TuneHub Symfony Backend

Backend API for the TuneHub music streaming platform built with Symfony.

## Local setup

### 1. Clone the repository

```bash
git clone <repository-url>
cd tunehub-symfony-backend
```

### 2. Configure environment variables

Copy the example environment file:

```bash
cp .env.example .env
```

Adjust variables if necessary.

## 3. Install dependencies

```bash
composer install
```

## 4. Start Docker containers

```bash
docker compose up -d --build
```

## 5. Generate JWT keys

```bash
php bin/console lexik:jwt:generate-keypair
```

## 6. Run database migrations

```bash
php bin/console doctrine:migrations:migrate
```

## 7. Verify the setup

```bash
php bin/console about
php bin/console debug:router
```

## Main environment variables

### Database

```env
DATABASE_URL=
```

### JWT

```env
JWT_SECRET_KEY=
JWT_PUBLIC_KEY=
JWT_PASSPHRASE=
JWT_TOKEN_TTL=
REFRESH_TOKEN_SECRET=
REFRESH_TOKEN_TTL=
```

### MinIO / S3

```env
AWS_ENDPOINT=
AWS_PUBLIC_ENDPOINT=
AWS_BUCKET=
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_REGION=
```

### Frontend / CORS

```env
FRONTEND_URL=
CORS_ALLOW_ORIGIN=
```

## API endpoints

### Authentication

- `POST /api/register`
- `POST /api/token/refresh`
- `POST /api/login`

### User

- `GET /api/me`
- `POST /api/me/update`

### Library

- `GET /api/libraryItems`
- `GET /api/libraryItems/{id}`

### Playlists

- `POST /api/playlist`
- `GET /api/playlist/{id}`
- `POST /api/playlist/{id}`
- `PATCH /api/playlist/{id}`
- `DELETE /api/playlist/{id}`

### Releases

- `GET /api/releases/latest`
