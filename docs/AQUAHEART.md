# AQUAHEART

AQUAHEART: A Web-Based Sales, Refill, and Customer Management System for Water Refilling Stations

Quick setup and notes

- Migrations: `database/migrations/*_create_customers_table.php`, `*_create_products_table.php`, `*_create_refills_table.php`.
- Models: `app/Models/Customer.php`, `app/Models/Product.php`, `app/Models/Refill.php`.
- Controllers: `app/Http/Controllers/CustomerController.php`, `ProductController.php`, `RefillController.php`.
- Views: `resources/views/aquaheart/*` (dashboard, lists).

To run locally:

1. Configure `.env` with DB connection.
2. Run migrations: `php artisan migrate`
3. Start dev server: `php artisan serve`

Notes and next steps:

- Add authentication and policies for multi-user access.
- Add data validation UI and form pages for creating customers, products and refills.
- Add reporting (sales, refill history) and export features.
