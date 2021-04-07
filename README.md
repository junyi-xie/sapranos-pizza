# Soparanos Pizzabar - ALA P7

I will be creating an online webshop where you can buy multiple/different types of pizzas in your preferred size and toppings of your choice. The shop backend will be written in **Object-Oriented PHP** and **MySQL**, as for the frontend, it will be done with **HTML/CSS**, and some **JavaScript/JQuery/AJAX** as well.

## Folder Structure ##

```
📦sopranos
 ┣ 📂assets
 ┃ ┣ 📂css
 ┃ ┃ ┣ 📜fontawesome.css
 ┃ ┃ ┣ 📜style.css
 ┃ ┃ ┣ 📜style.css.map
 ┃ ┃ ┣ 📜style.scss
 ┃ ┃ ┣ 📜_admin.scss
 ┃ ┃ ┣ 📜_cart.scss
 ┃ ┃ ┣ 📜_checkout.scss
 ┃ ┃ ┣ 📜_footer.scss
 ┃ ┃ ┣ 📜_general.scss
 ┃ ┃ ┣ 📜_header.scss
 ┃ ┃ ┣ 📜_index.scss
 ┃ ┃ ┣ 📜_modal.scss
 ┃ ┃ ┣ 📜_reset.scss
 ┃ ┃ ┣ 📜_shop.scss
 ┃ ┃ ┗ 📜_variable.scss
 ┃ ┣ 📂fonts
 ┃ ┃ ┣ 📜fa-brands-400.eot
 ┃ ┃ ┣ 📜fa-brands-400.svg
 ┃ ┃ ┣ 📜fa-brands-400.ttf
 ┃ ┃ ┣ 📜fa-brands-400.woff
 ┃ ┃ ┣ 📜fa-brands-400.woff2
 ┃ ┃ ┣ 📜fa-regular-400.eot
 ┃ ┃ ┣ 📜fa-regular-400.svg
 ┃ ┃ ┣ 📜fa-regular-400.ttf
 ┃ ┃ ┣ 📜fa-regular-400.woff
 ┃ ┃ ┣ 📜fa-regular-400.woff2
 ┃ ┃ ┣ 📜fa-solid-900.eot
 ┃ ┃ ┣ 📜fa-solid-900.svg
 ┃ ┃ ┣ 📜fa-solid-900.ttf
 ┃ ┃ ┣ 📜fa-solid-900.woff
 ┃ ┃ ┗ 📜fa-solid-900.woff2
 ┃ ┣ 📂images
 ┃ ┃ ┣ 📂favicon
 ┃ ┃ ┃ ┣ 📜android-chrome-192x192.png
 ┃ ┃ ┃ ┣ 📜android-chrome-512x512.png
 ┃ ┃ ┃ ┣ 📜apple-touch-icon.png
 ┃ ┃ ┃ ┣ 📜favicon-16x16.png
 ┃ ┃ ┃ ┣ 📜favicon-32x32.png
 ┃ ┃ ┃ ┗ 📜favicon.ico
 ┃ ┃ ┗ 📂layout
 ┃ ┃ ┃ ┣ 📜check.svg
 ┃ ┃ ┃ ┣ 📜double_caret.png
 ┃ ┃ ┃ ┣ 📜empty_cart.svg
 ┃ ┃ ┃ ┣ 📜pizza-pepperoni.png
 ┃ ┃ ┃ ┣ 📜pizza-quattro-formaggio.png
 ┃ ┃ ┃ ┣ 📜pizza-sopranos-deluxe.png
 ┃ ┃ ┃ ┣ 📜pizza-tonno.png
 ┃ ┃ ┃ ┣ 📜pizza-vegetariano.png
 ┃ ┃ ┃ ┣ 📜sopranos-logo-footer.png
 ┃ ┃ ┃ ┗ 📜sopranos-logo-header.png
 ┃ ┗ 📂js
 ┃ ┃ ┣ 📜jquery.min.js
 ┃ ┃ ┗ 📜main.js
 ┣ 📂inc
 ┃ ┣ 📂class
 ┃ ┃ ┣ 📂Mollie
 ┃ ┃ ┣ 📂PHPMailer
 ┃ ┣ 📜ajax.php
 ┃ ┣ 📜class.php
 ┃ ┣ 📜connect.php
 ┃ ┣ 📜footer.php
 ┃ ┣ 📜functions.php
 ┃ ┗ 📜header.php
 ┣ 📜.htaccess
 ┣ 📜cart.php
 ┣ 📜checkout.php
 ┣ 📜dashboard.php
 ┣ 📜index.php
 ┣ 📜README.md
 ┣ 📜shop.php
 ┗ 📜sopranos.sql
```

## Requirements ##
- Able to order online...
- Able to checkout online...
- Able to view the products...
- Able to see their order...
- Able to receive an invoice/confirmation mail...

## Login to Admin Panel ##
Although  not _required_, I will include an admin panel, which can be used to view your orders, edit products, and more. 

**Note:** to add an admin account, you will have to manually insert/create a new account into the database.

```php
$pdo->exec("INSERT INTO accounts SET id = NULL, image_id = NULL, username = 'admin', password = password_hash('admin', PASSWORD_DEFAULT), email = 'admin@admin.com', phone = '0', admin = 1, account_created = date("YmdHis"), last_login = 0");
```

Now that you have inserted a new account, you can simply login to the admin panel with admin/admin.

## Database ## 
📃 [sopranos.sql](https://github.com/junyi-xie/sopranos/blob/main/sopranos.sql), feel free to import it into MySQL.

**Note:** Be sure to change the database name to match your own, you can configurate the database settings [here!](https://github.com/junyi-xie/sopranos/blob/main/inc/connect.php)
