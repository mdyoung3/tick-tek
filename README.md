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

| Layer | Technology          |
|-------|---------------------|
| Backend | Laravel 12, PHP 8.3 |
| Database | SQLite (file-based) |
| Frontend | Vue 3, Vue Router 4 |
| Styling | Tailwind CSS 4      |
| Build | Vite 7              |
| HTTP Client | Axios               |

## Requirements

- PHP 8.3+
- Composer 2
- Node.js 22+ & yarn

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
yarn build
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

## Testing

This project uses [Pest](https://pestphp.com/) for feature testing, with an in-memory SQLite database so no additional database setup is required.

### Running Tests
```bash
php artisan test
```

Or directly via Pest:
```bash
./vendor/bin/pest
```

### Test Coverage

The test suite covers all four API endpoints:

| Test | Description |
|------|-------------|
| `can fetch all calculations` | Asserts the index endpoint returns a 200 and the correct number of records |
| `can store a calculation` | Asserts a new calculation is saved and returns a 201 |
| `can delete a calculation` | Asserts a single record is removed and returns a 204 |
| `can clear all calculations` | Asserts all records are removed and returns a 204 |

### Continuous Integration

Tests run automatically via GitHub Actions on every push and pull request to `main`. The workflow installs dependencies, configures the environment, and runs the full test suite against an in-memory SQLite database.

You can view the latest test results in the [Actions tab](../../actions) of this repository.
