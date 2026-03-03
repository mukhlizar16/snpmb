# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 application for **SNPMB** (Seleksi Nasional Penerimaan Mahasiswa Baru) — an Indonesian national university admissions system. The core feature is importing and processing student data from CSV/Excel files.

## Common Commands

### Development
```bash
composer run dev        # Starts Laravel server, queue listener, and Vite concurrently
npm run dev             # Vite dev server only
php artisan serve       # Laravel dev server only
```

### Setup (fresh install)
```bash
composer run setup      # Install deps, copy .env, generate key, migrate, npm install & build
```

### Testing
```bash
composer run test                          # Run all tests (clears config first)
php artisan test                           # Run all tests directly
php artisan test --filter=TestName         # Run a single test class or method
php artisan test tests/Feature/SomeTest.php  # Run a specific test file
vendor/bin/pest                            # Run tests directly with Pest
```
Tests use SQLite in-memory via `phpunit.xml` — no separate test database setup needed.

### Code Quality
```bash
vendor/bin/pint         # Format PHP code (Laravel Pint)
vendor/bin/pint --test  # Check formatting without writing changes
```

### Database
```bash
php artisan migrate
php artisan migrate:fresh --seed
```

## Architecture

### Import Pipeline

The central feature is a structured import pipeline for processing SNPMB data files:

```
ImportController
  └── ImportService::handle($filePath, $type)
        ├── Reads Excel/CSV via maatwebsite/excel
        ├── Dispatches to row validator by type:
        │     - 'data_siswa'   → SiswaRowValidator
        │     - 'data_pilihan' → PilihanRowValidator
        └── BulkInsertHandler::insert($type, $rows)
              └── DB::table($type)->insert($rows)  ← direct table name, no Eloquent
```

**Adding a new import type** requires:
1. A new `*RowValidator` class in `app/Services/Import/Validators/` — implement `validate(array $row): array` mapping positional `$row` columns to named fields
2. Register it in `ImportService::$validators` array
3. Add the table name to `BulkInsertHandler::$tables`
4. Add a route in `routes/web.php` and handler in `ImportController`

### Key Design Decisions
- **Row validators** use positional array access (`$row[0]`, `$row[1]`, etc.) — column order in the uploaded file is fixed and must match
- **Bulk inserts** bypass Eloquent and write directly to tables via `DB::table()` for performance
- **Error files** are written to `storage/app/public/` and served via `Storage::disk('public')->download()`
- The `ImportService` wraps the entire import in a DB transaction — any exception causes a full rollback

### Models & Database

Domain models (all in `app/Models/`):
- `DataSiswa` — master student records, primary key via `nomor_pendaftaran`
- `DataPilihan` — student program choices (up to 2), linked to `DataSiswa`
- `DataPrestasi` — student achievements
- `DataNilai` / `DataNilaiTka` — student grades/scores
- `DataPortofolio` — student portfolios
- `DataStatusTambahan` — additional student status flags
- `RefJurusan` / `RefMataPelajaran` / `RefPortofolio` — reference/lookup tables

### Frontend Stack
- Blade templates with `x-app-layout` (Laravel Breeze layout)
- Tailwind CSS v3 + Alpine.js
- Vite for asset bundling

### Auth
Standard Laravel Breeze authentication. All import routes are behind `auth` middleware.
