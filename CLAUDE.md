# DbOnlineStore — Phase 2

Online store web app for a database class. Phase 2 covers user-facing PHP features built on top of a Phase 1 schema that already exists on the department MySQL server (`classdb`).

- **Phase 1 (done):** schema design, stored procedures, triggers, sample data.
- **Phase 2 (in progress):** PHP front-end + business logic. Worth **260 points** total.
- **Team:** two-person project. Both members commit to this repo; both connect to ONE shared database using the same credentials.

## Deliverables and points

| Area | Points | Notes |
|---|---|---|
| Customer functions | 130 | Demo'd to TA |
| — Registration | 10 | |
| — Login / logout / password change | 15 | |
| — Product browsing | 10 | Works without login; cart needs auth |
| — View orders | 10 | Orders list + order-item detail |
| — Shopping cart + checkout | 75 | Transactional, stock verified |
| — Interface usability | 10 | Clear nav, self-explanatory labels |
| Employee functions | 50 | Demo'd to TA |
| — Forced password reset on first login | 10 | Uses `employee.password_reset_required` |
| — Main page (restock / price change / histories / optional new product) | 40 | |
| Phase 2 Report | 80 | URLs, creds, DB/table names, code snippets (transactions, SQL-injection prevention), password-encryption explanation, reflections |

**Due:** TA demo final week Tuesday 11:50pm; project submission final week Wednesday 11:59pm.

## Tech stack

- **Language:** PHP, served from each team member's department web space (`/local/my_web_files/<user>/DbOnlineStore/`).
- **DB:** MySQL via PDO. Connection via `db.php` → reads `db.ini` next to it (`__DIR__ . "/db.ini"`).
- **State:** PHP `$_SESSION` after login. Forms use POST.
- **Security required by rubric:** prepared statements (no hardcoded inputs), bcrypt password hashing, transactions for checkout, no plaintext passwords.

## Repo layout

```
db.php              # connectDB() → PDO, reads db.ini (gitignored or committed per team choice)
db.ini              # LOCAL credentials file, NOT the same path for each team member's deploy
common.php          # Shared helpers: authenticateCustomer/Employee, registerCustomer, changePassword
login.php           # Role-based login (customer/employee)
registration.php    # Customer registration form
customer.php        # Customer landing page (logout + password-change scaffolded)
employee.php        # Employee landing page (empty stub)
```

## Database schema (Phase 1, already on classdb)

### Tables

- **employee** (`employee_id` PK auto, `username` uniq, `email` uniq, `hashed_password`, `password_reset_required` tinyint default 1)
- **customer** (`customer_id` PK auto, `hashed_password`, `username` uniq, `first_name`, `last_name`, `email` uniq, `shipping_address`)
- **category** (`category_name` PK varchar(100), `description`) — **string PK, no surrogate id**
- **product** (`product_id` PK auto, `name`, `product_desc`, `price`, `advised_stock_quantity`, `actual_stock_quantity`, `image` varchar(255), `is_discontinued` tinyint, `category` FK → category.category_name)
- **`order`** (`order_id` PK auto, `customer_id` FK, `order_date`, `order_status`, `total_order_price`) — **reserved word, must be backticked in every query**
- **cart** (`cart_id` PK auto, `customer_id` UNIQUE FK) — **one cart per customer**
- **cart_item** (composite PK `(cart_id, product_id)`, `quantity`, `price`) — duplicate adds must `ON DUPLICATE KEY UPDATE quantity = quantity + ?`
- **order_item** (composite PK `(order_id, product_id)`, `quantity`, `price`)
- **history_record** (composite PK `(product_id, timestamps)`, `action` ENUM('INSERT','UPDATE','DELETE'), `old_price`, `new_price`, `old_stock`, `new_stock`, `details`, `employee_id`, `customer_id`, `order_id`)

### Stored procedures (call these instead of writing the logic in PHP)

- `create_employee(username, email, temp_password)` — **caller must bcrypt the password before calling**; the proc just inserts whatever you pass into `hashed_password`.
- `insert_category(category_name, description)`
- `insert_product(name, price, image, desc, actual_qty, advised_qty, is_discontinued, category)`
- `log_product_update(product_id, action, old_price, new_price, old_stock, new_stock, details, employee_id, customer_id, order_id)` — inserts into `history_record`.
- `checkout(IN customer_id, OUT order_id, OUT out_of_stock_product)` — **does the entire cart → order transaction**: stock check, rollback on OOS, order creation, order_item inserts, stock decrement, cart_item clearing. PHP just `CALL`s it and reads the OUT vars. Does **NOT** log to history_record.

### Triggers

- `prevent_product_id_change` — blocks any UPDATE that changes `product_id`.
- `prevent_product_delete` — blocks any DELETE on `product`. "Discontinue" = `UPDATE product SET is_discontinued = 1 WHERE ...`.

## Conventions / gotchas

- Backtick `` `order` `` in every SQL statement.
- Use `CALL checkout(?, @o, @oos)` + `SELECT @o, @oos` for checkout — don't roll your own transaction in PHP.
- Employees created via `create_employee` proc start with `password_reset_required = 1`. On successful employee login, if that flag is 1, redirect to password-change BEFORE showing the employee main page. After successful change, set the flag to 0.
- Customer cart is lazy: first add-to-cart does `INSERT IGNORE INTO cart (customer_id) VALUES (?)` then looks up cart_id. Alternative: create an empty cart row in `registerCustomer`.
- For stock-history display: `history_record` captures explicit restocks/price changes, but the `checkout` proc does NOT log stock decrements. If the rubric's "stock history" needs to show customer-purchase-driven changes, either (a) modify checkout to call `log_product_update`, or (b) compose the history view in PHP by UNION-ing `history_record` with a query against `order_item`+`order`.
- No `admin` table — "admin" role is just an existing employee who creates other employees via `create_employee`. Phase 2 requires at least one seeded employee to bootstrap this.

## Setup for a new team member

1. Clone the repo to `/local/my_web_files/<yourname>/DbOnlineStore/` (department web space).
2. Create `db.ini` in the project root with the shared classdb credentials (DSN, username, password) — same credentials both members use so both hit the same schema.
3. Open the app at the URL that maps to your web space.

## Status tracker

### Done
- `db.php` — PDO connection via `__DIR__ . "/db.ini"` (portable across both partners).
- `common.php` — `authenticateCustomer`, `authenticateEmployee`, `registerCustomer`, `changePassword` using prepared statements + bcrypt.
- `login.php` — role-based login, redirects to `customer.php` or `employee.php`.
- `registration.php` — customer registration form (form action fixed).
- `customer.php` — auth guard, logout button, customer password-change form.

### In progress / not started
- **Task 2** — Employee forced-reset on first login; employee password-change form; optional dedicated `logout.php`.
- **Task 3** — Product browsing: landing page listing categories → product list (name, price, image, stock status). No login required to browse; add-to-cart requires auth.
- **Task 4** — Shopping cart + checkout (**75 pts, biggest**). Lazy cart creation; `cart.php` for view/update/remove; add-to-cart with `ON DUPLICATE KEY UPDATE`; checkout via `CALL checkout(...)` reading OUT params; display order number on success, OOS product on failure.
- **Task 5** — View orders page (orders list with number/date/total → drill-down to order_items).
- **Task 6** — Employee main page: restock (UPDATE + `CALL log_product_update`), change price (same pattern), stock history view, price history view with % change, optional insert-new-product via `insert_product` proc.
- **Task 7** — Navigation polish + end-to-end testing (golden path + edge cases) before the TA demo.

### Phase 2 Report (separate deliverable, 80 pts)
Not code — written doc required at submission. Must include: URLs, test credentials, database/table names, code snippets showing transaction handling + SQL-injection prevention, explanation of password encryption (bcrypt via `password_hash` / `password_verify`), and reflections. Each section is 5–15 pts.
