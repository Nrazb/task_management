# Task Management

Task Management System berbasis web yang dibuat untuk technical test.

## Tech Stack

* Laravel 12
* PHP 8.2+
* MySQL
* Blade
* Tailwind CSS
* JavaScript
* REST API

## Features

* Responsive dashboard
* Task management (CRUD)
* Search task
* Pagination
* Task statistics
* Loading, empty, dan error state
* REST API
* Request validation & error handling
* API logging

## Installation

Clone repository:

```bash
git clone https://github.com/USERNAME/task-management.git
cd task-management
```

Install dependencies:

```bash
composer install
npm install
```

Copy `.env.example` menjadi `.env`, lalu sesuaikan konfigurasi database.

Generate application key:

```bash
php artisan key:generate
```

Run migration dan seeder:

```bash
php artisan migrate:fresh --seed
```

Jalankan aplikasi:

```bash
php artisan serve
npm run dev
```

Open:

```text
http://127.0.0.1:8000/dashboard
```

## API Endpoints

| Method | Endpoint                 | Description        |
| ------ | ------------------------ | ------------------ |
| GET    | `/api/tasks`             | Get all tasks      |
| GET    | `/api/tasks/{id}`        | Get task detail    |
| POST   | `/api/tasks`             | Create task        |
| PUT    | `/api/tasks/{id}`        | Update task        |
| PATCH  | `/api/tasks/{id}/status` | Update task status |
| DELETE | `/api/tasks/{id}`        | Delete task        |

Search:

```text
GET /api/tasks?search=dashboard
```

## Task Status

* `pending`
* `in_progress`
* `completed`

## Priority

* `low`
* `medium`
* `high`

## Author

Naura Azzahra Budiyono
