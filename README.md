Name, Surname: Halil İbrahim Demir
Student Number: 20232022938


# 🛍️ Modern E-Commerce Platform (Laravel)

This project is a fully-featured, production-ready E-Commerce web application developed using the Laravel framework. It is built in accordance with modern web design trends, featuring a premium dark mode, subtle glassmorphism effects, a sophisticated color palette, and dynamic micro-interactions.

The application includes both a polished customer-facing storefront and a comprehensive, robust administrative control panel.

---

## ✨ Features

### 👤 User & Role Management
- **Role-Based Access Control (RBAC):** Strict permission checks enforced for `Admin` and `Standard User` roles.
- **Secure Authentication:** Built on top of the native Laravel Authentication system, featuring a sleek, secure custom login portal.
- **Dashboard Security Middleware:** Access restriction to the administrative zone (`/admin`), accessible only by verified users with the `admin` role.

### 🏠 Storefront Experience
- **Premium UI/UX:** HSL-based premium dark mode interface, dynamic gradient **Hero Banner**, and seamless micro-animations.
- **Persistent Categories Sidebar:** A clean, fixed-position category list rendered on both the home page and category detail routes (`/category/{id}`).
- **4-Column Grid Layout:** Responsive item grid scaling to a modern 4-column layout on desktop viewports.
- **Rich Product Details Page:** Immersive item showcase displaying key attributes, images, detailed specifications, real-time pricing, and stock levels.
- **Dynamic Cart Indicator:** Context-aware navigation bar badge hidden when the basket is empty, displaying real-time item counts dynamically in a highlighted green parenthetical structure once an item is added (e.g., **Cart (3)**).

### 🛒 Cart & Order Processing
- **Seamless Add-to-Cart:** Simple quantity selection inputs on product pages to pass items smoothly into the shopping cart state.
- **Concurrent Stock Protection:** Real-time stock validations triggered upon adding items to the basket and proceeding through checkout. Utilizes database row-level locking (`lockForUpdate`) to guarantee absolute data consistency under heavy traffic.
- **Out of Stock Architecture:** Automatically swaps active buttons to "Out of Stock" indicators on product pages and overlays a "Sold Out" badge across grid items when inventories hit zero.
- **Dynamic Checkout Engine:** Seamless, state-driven checkout wizard capturing shipping specifics (Full Name, Address, Phone Number) alongside diverse gateway mechanisms (Credit Card / Cash on Delivery).
- **Post-Purchase Success Hub:** Premium receipt generation page displaying unique tracking codes alongside comprehensive transaction tables.

### ⚙️ Administrative Command Center (Admin Dashboard)
- **High-Level Analytics Matrix:** Aggregated metric widgets showcasing total inventory counts, successful order processing pipelines, and active notifications for items hitting critical low stock levels (stock < 10).
- **Visual Inventory Management:** Critical resource alerts with color-coded warning structures (Red/Orange/Green) for rapid warehouse sorting.
- **Asynchronous Product CRUD:** Completely dynamic catalog maintenance (Create, Read, Update, Delete) driven by modern AJAX/Fetch API layers for instantaneous mutations alongside reactive image URL previews.
- **Modular Category Structuring:** Clean multi-tier management facilitating recursive nested categories (`parent_id`) and visibility state toggles.
- **Fulfillment Pipeline:** Unified operational board allowing administrators to review, modify shipment processing stages (Accepted, Shipped, Cancelled, etc.), and append tracking tracking IDs.
