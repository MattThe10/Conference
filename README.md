# Conference

## Project setup

1. ### Copy the environment file
Copy the example environment file **`.env.example`** to create a new **`.env`** file:
```
cp .env.example .env
```

2. ### Install backend dependencies
Navigate to the backend folder, install the dependencies using Composer, and set up the environment file:
```
cd backend
composer install
cp .env.example .env
```

3. ### Install frontend dependencies
Navigate to the frontend folder, install the dependencies using npm, and set up the environment file:
```
cd frontend
npm install
cp .env.example .env
```

4. ### Build and start the project
In the root directory of the project, run the following command to build and start the Docker containers:
```
docker-compose up -d
```

5. ### Access the application
- Backend API: http://localhost:8080
- Frontend application: http://localhost:3000

## Additional Commands

### Stop the containers
```
docker-compose down
```