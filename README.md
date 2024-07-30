# Product Listing App

This is a simple product listing application built with PHP and JavaScript. It allows users to filter products by price, category, and sale status. The products are displayed with pagination, and prices are converted from USD to INR.

## Setup Instructions

1. **Clone the repository**:
    ```sh
    git clone [https://github.com/Surajbhatt1/product-listing-app.git
    cd product-listing-app
    ```

2. **Set up the database**:
    - Create a MySQL database named `product_listing_app`.
    - Import the database schema from the `schema.sql` file.
    - Update the `db.php` file with your database credentials.

3. **Run the application**:
    - Make sure your PHP server is running.
    - Open `index.html` in your web browser.

## Additional Documentation

- The `fetch_products.php` file handles fetching the products from the database based on the filters and pagination parameters.
- The `index.html` file contains the frontend code to display the products and handle user interactions.

## Live Demo

You can access the live demo of the application at [Your Live Demo URL](http://your-live-demo-url.com)
