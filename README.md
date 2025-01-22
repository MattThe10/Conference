# Conference

## Project setup

1. ### Create the environment file
In the root directory, create a new .env file by copying the example file:
```
cp .env.example .env
```

2. ### Set up the backend environment
Navigate to the backend directory and create a new .env file by copying the example file:
```
cd backend
cp .env.example .env
```

3. ### Set up the frontend environment
Navigate to the frontend directory and create a new .env file by copying the example file:
```
cd frontend
cp .env.example .env
```

4. ### Start Docker containers
In the root directory, start the Docker containers:
```
docker-compose up -d
```

5. ### Install Composer dependencies
Run the following command to install Composer dependencies in the backend:
```
docker-compose exec backend composer install
```

6. ### Install npm dependencies
Navigate to the frontend directory and install npm dependencies:
```
cd frontend
npm install
```

7. ### Restart Docker containers
Stop and restart the Docker containers to ensure proper setup:
```
docker-compose down
docker-compose up -d
```

8. ### Update the database schema
Run database migrations to update the schema:
```
docker-compose exec backend php artisan migrate
```

9. ### Access the application
- Backend API: http://localhost:8080
- Frontend application: http://localhost:3000
- Mailpit Interface: http://localhost:8025

## Additional Commands

### Populate the database with initial data:
```
docker-compose exec backend php artisan db:seed
```
### If necessary, reset the database and apply migrations from scratch (this will delete existing data in the database):
```
docker-compose exec backend php artisan migrate --fresh
```