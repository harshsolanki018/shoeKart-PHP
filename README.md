# ShoeMart

A simple PHP and MySQL footwear shopping website with a clean Tailwind-based UI, product browsing, cart, wishlist, orders, and an admin dashboard.

## Live Demo

[View Live Demo](https://your-live-demo-link.com)

> Replace the link above with your deployed project URL.

## Features

- Modern, simple storefront layout
- Category pages for men, women, kids, collections, sale, and trending items
- Product cards with add to cart and wishlist actions
- Buy now checkout modal
- Cart, wishlist, and order history pages
- Admin dashboard for managing products, users, and orders
- Responsive design for desktop and mobile

## Tech Stack

- PHP
- MySQL
- Tailwind CSS via CDN
- JavaScript
- Composer with PHPDotenv

## Requirements

- PHP 8 or later
- MySQL
- XAMPP, WAMP, or any PHP/MySQL local server
- Composer

## Installation

1. Clone the repository into your local server folder.
2. Create a MySQL database for the project.
3. Import the project database schema and data if you have the SQL file.
4. Update the `.env` file with your database credentials.
5. Install PHP dependencies:

```bash
composer install
```

6. Start Apache and MySQL.
7. Open the project in your browser through the local server URL.

## Environment Variables

Create or update `.env` with values like:

```env
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=your_database_name
```

## Project Structure

```text
.
├── admin_dashboard.php
├── admin_login.php
├── add_to_cart.php
├── add_to_wishlist.php
├── cart.php
├── catalog-actions.js
├── catalog-page.php
├── collections.php
├── contact.php
├── db.php
├── footer.php
├── header.php
├── index.php
├── kids.php
├── login.php
├── mens.php
├── order.php
├── order_submit.php
├── privacy.php
├── sale.php
├── terms.php
├── trending.php
├── wishlist.php
└── womens.php
```

## How It Works

- `header.php` and `footer.php` provide the shared site layout.
- `catalog-page.php` renders the reusable storefront layout.
- `catalog-actions.js` handles product card actions and the buy-now modal.
- Database calls are handled through `db.php`.

## Notes

- Some product data is loaded directly from MySQL.
- The UI uses Tailwind via CDN for a lightweight setup.
- Backend action files return JSON for cart and wishlist updates.

## License

This project is for educational and personal use unless you add a different license.
