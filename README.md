# Tick-Tek Calculator

An API-driven calculator web application with a persistent calculation history ("ticker tape"). Built with Laravel 12 and Vue 3 as part of a CalcTek interview exercise.

## Features

- Basic arithmetic: addition, subtraction, multiplication, division
- Division-by-zero error handling
- Persistent calculation history stored in a database
- Ticker tape display with timestamps for each calculation
- Delete individual calculations or clear the entire history
- WCAG 2.1 accessible — proper ARIA labels, contrast ratios, and keyboard navigation

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 12, PHP 8.2 |
| Database | SQLite (file-based) |
| Frontend | Vue 3, Vue Router 4 |
| Styling | Tailwind CSS 4 |
| Build | Vite 7 |
| HTTP Client | Axios |

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+ & npm

## Installation

```bash
# Clone the repository and enter the project directory
git clone <repo-url> tick-tek
cd tick-tek

# Install PHP and JavaScript dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Create and migrate the database
touch database/database.sqlite
php artisan migrate
```

## Running the Application

```bash
# Start all dev services (Laravel, Vite, queue listener, log watcher)
composer run dev
```

This starts:
- PHP development server on `http://localhost:8000`
- Vite dev server on `http://localhost:5173` (HMR)
- Queue listener
- Log watcher (Pail)

For a production build:

```bash
npm run build
php artisan serve
```

## API Endpoints

All endpoints are prefixed with `/api`.

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/calculations` | List all calculations (newest first) |
| `POST` | `/api/calculations` | Save a new calculation |
| `DELETE` | `/api/calculations/{id}` | Delete a single calculation |
| `DELETE` | `/api/calculations` | Delete all calculations |

### Example Request

```json
POST /api/calculations
{
    "expression": "9 + 3",
    "result": 12
}
```

### Example Response

```json
{
    "id": 1,
    "expression": "9 + 3",
    "result": 12,
    "created_at": "2026-02-24T12:30:45.000000Z",
    "updated_at": "2026-02-24T12:30:45.000000Z"
}
```

## Project Structure

```
├── app/
│   ├── Http/Controllers/CalculationController.php
│   └── Models/Calculations.php
├── resources/js/
│   ├── App.vue                   # Root component, manages API calls and state
│   ├── components/
│   │   ├── Calculator.vue        # Calculator UI and logic
│   │   ├── TickerTape.vue        # Calculation history list
│   │   └── CalculationBubble.vue # Individual history entry
│   └── Pages/
│       └── HomeRoute.vue         # Home route
├── routes/
│   ├── api.php                   # API routes
│   └── web.php                   # Catch-all for Vue Router
└── database/
    └── migrations/               # Database schema
```

## Component Overview

- **App.vue** — Orchestrates data fetching and event handling between child components
- **Calculator.vue** — Handles user input, evaluates expressions, emits results
- **TickerTape.vue** — Displays the full calculation history; emits delete events
- **CalculationBubble.vue** — Renders a single calculation entry with a timestamp and delete button

## Database Schema

```sql
CREATE TABLE calculations (
    id          BIGINT PRIMARY KEY,
    expression  VARCHAR(255) NOT NULL,
    result      DECIMAL(15, 8) NOT NULL,
    created_at  TIMESTAMP,
    updated_at  TIMESTAMP
);
```

## Running Tests

```bash
php artisan test
```
