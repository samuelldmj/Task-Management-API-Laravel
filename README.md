# Laravel API + SPA Starter

A Laravel 10+ backend that exposes a JSON API for a simple tasks application suitable for a SPA frontend. It includes:

- Versioned APIs (v1 and v2) with Task management
- Authentication (registration, login, logout) using Laravel Sanctum
- Policies for authorization
- Request validation and API Resources/Transformers
- Database factories, seeders, and migrations for Posts, Tasks, Task Priorities, and Tokens
- Example frontend scaffolding via Vite (resources/js, resources/css)

This README documents local setup, environment, API usage, testing, and common workflows.

## Requirements

- PHP 8.2+
- Composer 2.x
- Node.js 18+ and npm 9+
- A database supported by Laravel (e.g., MySQL/MariaDB, SQLite, PostgreSQL). The project is configured by default for MySQL via .env.

If you use Laragon (Windows) this project is already placed under c:\laragon-two\laragon\www.

## Quick Start

1) Install dependencies

- PHP deps
  - composer install

- Node deps (only required if you will use the sample SPA assets via Vite)
  - npm install

2) Environment

- Copy .env.example to .env
- Generate application key
  - php artisan key:generate
- Configure DB connection in .env
  - DB_CONNECTION=mysql
  - DB_HOST=127.0.0.1
  - DB_PORT=3306
  - DB_DATABASE=laravel_api_spa
  - DB_USERNAME=your_user
  - DB_PASSWORD=your_password

3) Database

- Run migrations
  - php artisan migrate
- (Optional) Seed sample data
  - php artisan db:seed

4) Serve API

- Start Laravel dev server
  - php artisan serve
  - Default: http://127.0.0.1:8000

5) (Optional) Build/serve assets for the demo SPA skeleton

- Start Vite
  - npm run dev

## Project Structure (high level)

app/
- Http/
  - Controllers/
    - Api/
      - Auth/ LoginController.php, RegisterController.php, LogoutController.php
      - V1/ TaskController.php, CompleteTaskController.php
      - V2/ TaskController.php, CompleteTaskController.php, SummaryController.php
  - Requests/ (validation): LoginRequest.php, RegisterRequest.php, StoreTaskRequest.php, UpdateTaskRequest.php, etc.
  - Resources/ (API transformers): TaskResource.php, TaskPriorityResource.php, TaskSummaryResource.php
- Models/: Task.php, TaskPriority.php, Post.php, User.php
- Policies/: TaskPolicy.php

routes/
- api.php (registers versioned route groups)
- api/v1.php, api/v2.php (per-version routes)

resources/
- js/, css/, views/

database/
- migrations/ (users, posts, tasks, task_priorities, personal access tokens, etc.)
- seeders/ (DatabaseSeeder.php, TaskSeeder.php)

## Authentication

The API uses Laravel Sanctum for token-based auth.

- Register: POST /api/register
- Login: POST /api/login
- Logout: POST /api/logout (requires Bearer token)

Responses include a token you can use as an Authorization: Bearer <token> header for subsequent requests.

Relevant files:
- app/Http/Controllers/Api/Auth/*.php
- config/sanctum.php
- database/migrations/*_create_personal_access_tokens_table.php

## API Overview

There are versioned endpoints under /api/v1 and /api/v2. Authenticated routes require a valid Bearer token created on login/registration.

Headers for protected routes:
- Accept: application/json
- Authorization: Bearer <token>

### v1 Endpoints (Tasks basics)

- GET /api/v1/tasks – list tasks for the authenticated user
- POST /api/v1/tasks – create a task
- GET /api/v1/tasks/{task} – show a task
- PUT/PATCH /api/v1/tasks/{task} – update a task
- DELETE /api/v1/tasks/{task} – delete a task
- POST /api/v1/tasks/{task}/complete – mark as completed

Code references:
- routes/api/v1.php
- app/Http/Controllers/Api/V1/TaskController.php
- app/Http/Controllers/Api/V1/CompleteTaskController.php
- app/Http/Requests/StoreTaskRequest.php, UpdateTaskRequest.php
- app/Http/Resources/TaskResource.php
- app/Policies/TaskPolicy.php

### v2 Endpoints (Tasks + priorities + summaries)

- GET /api/v2/tasks – list tasks
- POST /api/v2/tasks – create a task (supports task_priorities_id)
- GET /api/v2/tasks/{task} – show
- PUT/PATCH /api/v2/tasks/{task} – update
- DELETE /api/v2/tasks/{task} – delete
- POST /api/v2/tasks/{task}/complete – mark as completed
- GET /api/v2/tasks/summary – aggregated summary of tasks

Enhancements in v2 include TaskPriority relations and TaskSummaryResource.

Code references:
- routes/api/v2.php
- app/Http/Controllers/Api/V2/TaskController.php
- app/Http/Controllers/Api/V2/CompleteTaskController.php
- app/Http/Controllers/Api/V2/SummaryController.php
- app/Http/Resources/TaskPriorityResource.php
- app/Http/Resources/TaskSummaryResource.php

### Example Request/Response

Create Task (v2)
- POST /api/v2/tasks
- Headers:
  - Accept: application/json
  - Authorization: Bearer <token>
- Body (JSON):
  {
    "title": "My task",
    "description": "Optional details",
    "due_date": "2025-12-31",
    "task_priorities_id": 1
  }
- Response (201): TaskResource payload including priority information

List Tasks (v2)
- GET /api/v2/tasks
- Auth required
- Returns a collection of TaskResource

Summary (v2)
- GET /api/v2/tasks/summary
- Returns aggregate counts (e.g., total, completed, pending), as shaped by TaskSummaryResource

## Data Model

Key tables and relations:

- users: authenticatable users
- tasks: belongsTo user; may include title, description, completed_at, due_date, task_priorities_id
- task_priorities: priority levels for tasks (e.g., Low, Medium, High)
- personal_access_tokens: for Sanctum

Models:
- app/Models/User.php
- app/Models/Task.php
- app/Models/TaskPriority.php

Authorization:
- app/Policies/TaskPolicy.php guards access so users can only manage their own tasks.

## Validation & Resources

- Requests validate inputs (StoreTaskRequest, UpdateTaskRequest, LoginRequest, RegisterRequest)
- Resources format output (TaskResource, TaskPriorityResource, TaskSummaryResource)

Follow these patterns when adding new endpoints: create Request for validation, use Policy for authorization, return Resource or ResourceCollection for output shape.

## Migrations & Seeding

Run all migrations:
- php artisan migrate

Seed sample data:
- php artisan db:seed

Factories:
- database/factories/* provide test data generation for users, posts, tasks.

## Testing

- Run feature and unit tests
  - php artisan test
  - or ./vendor/bin/pest (if using Pest) – this project includes tests under tests/

Configure a testing database in your .env.testing as needed.

## Common Artisan Commands

- php artisan migrate:fresh --seed – rebuild DB with seeds
- php artisan tinker – interactive REPL
- php artisan route:list – inspect routes
- php artisan make:controller, make:model, make:request, make:resource – scaffolding

## Environment & Configuration

- .env controls application config: DB, MAIL, QUEUE, CACHE, SANCTUM, CORS, etc.
- config/ contains all app configurations; review config/cors.php for API usage from a SPA

CORS
- Adjust allowed origins in config/cors.php or corresponding .env variables when calling the API from a separate frontend origin.

## Error Handling

- Validation errors return 422 with field messages
- Auth failures return 401
- Authorization failures return 403
- Not found returns 404

## Deployment Notes

- Ensure APP_KEY is set and APP_ENV=production, APP_DEBUG=false
- Run database migrations during deploy
- Configure queue, cache, and session drivers to production-grade backends
- Configure CORS to allow your SPA origin
- Use HTTPS and secure cookies where applicable

## Contributing

- Use PSR-12 coding standards
- Add tests for new features
- Keep controllers thin; move business logic to services or models where appropriate
- Use Request classes for validation and Policies for authorization

## License

This project is open-sourced software licensed under the MIT license.
