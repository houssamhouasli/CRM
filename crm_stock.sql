SET FOREIGN_KEY_CHECKS = 0;

-- =========================
-- REGIONS
-- =========================
CREATE TABLE regions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(255) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =========================
-- CLIENTS
-- =========================
CREATE TABLE clients (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    region_id BIGINT UNSIGNED,
    company_name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(255),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_clients_region FOREIGN KEY (region_id) REFERENCES regions(id) ON DELETE SET NULL
);

-- =========================
-- DEPOTS
-- =========================
CREATE TABLE depots (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =========================
-- USERS
-- =========================
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','commercial','depositaire','livreur') NOT NULL DEFAULT 'admin',
    region_id BIGINT UNSIGNED,
    depot_id BIGINT UNSIGNED,
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_users_region FOREIGN KEY (region_id) REFERENCES regions(id) ON DELETE SET NULL,
    CONSTRAINT fk_users_depot FOREIGN KEY (depot_id) REFERENCES depots(id) ON DELETE SET NULL
);
-- =========================
-- CATEGORIES
-- =========================
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =========================
-- PRODUCTS
-- =========================
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED,
    name VARCHAR(255) NOT NULL,
    sku VARCHAR(255) UNIQUE,
    price_ht DECIMAL(15,2) NOT NULL,
    tva_rate DECIMAL(5,2) DEFAULT 20.00,
    weight DECIMAL(8,2) DEFAULT 0.00,
    unit VARCHAR(255),
    promo_type ENUM('percentage','fixed'),
    promo_value DECIMAL(15,2) DEFAULT 0,
    promo_min_qty INT DEFAULT 1,
    promo_start_date DATE,
    promo_end_date DATE,
    is_refundable TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- =========================
-- TRUCKS
-- =========================
CREATE TABLE trucks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    livreur_id BIGINT UNSIGNED UNIQUE,
    name VARCHAR(255),
    registration VARCHAR(255),
    capacity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_trucks_livreur FOREIGN KEY (livreur_id) REFERENCES users(id) ON DELETE CASCADE
);

-- =========================
-- DEPOT STOCKS
-- =========================
CREATE TABLE depot_stocks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    depot_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    quantity INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_depotstocks_depot FOREIGN KEY (depot_id) REFERENCES depots(id) ON DELETE CASCADE,
    CONSTRAINT fk_depotstocks_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- =========================
-- TRUCK STOCKS
-- =========================
CREATE TABLE truck_stocks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    truck_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    quantity INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_truckstocks_truck FOREIGN KEY (truck_id) REFERENCES trucks(id) ON DELETE CASCADE,
    CONSTRAINT fk_truckstocks_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- =========================
-- ORDERS
-- =========================
CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type ENUM('sale','restock') NOT NULL,
    client_id BIGINT UNSIGNED,
    created_by BIGINT UNSIGNED NOT NULL,
    status ENUM('pending','confirmed','validated','proposition','livrer','annuler') DEFAULT 'pending',
    total_ht DECIMAL(15,2) DEFAULT 0.00,
    total_tva DECIMAL(15,2) DEFAULT 0.00,
    total_ttc DECIMAL(15,2) DEFAULT 0.00,
    notes TEXT,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_client FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE SET NULL,
    CONSTRAINT fk_orders_user FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

-- =========================
-- ORDER ITEMS
-- =========================
CREATE TABLE order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    quantity INT NOT NULL,
    price_unit_ht DECIMAL(15,2) NOT NULL,
    tva_rate DECIMAL(5,2) NOT NULL,
    total_ht DECIMAL(15,2) NOT NULL,
    total_tva DECIMAL(15,2) NOT NULL,
    total_ttc DECIMAL(15,2) NOT NULL,
    final_price_ht DECIMAL(15,2),
    promo_type ENUM('percentage','fixed'),
    promo_value DECIMAL(15,2) DEFAULT 0,
    discount_amount DECIMAL(15,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_orderitems_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    CONSTRAINT fk_orderitems_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- =========================
-- DELIVERIES
-- =========================
CREATE TABLE deliveries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    livreur_id BIGINT UNSIGNED NOT NULL,
    depot_id BIGINT UNSIGNED NOT NULL,
    status ENUM('pending','proposition','livrer','annuler') DEFAULT 'pending',
    has_substitution TINYINT(1) DEFAULT 0,
    delivery_date DATE,
    total_ht DECIMAL(15,2) DEFAULT 0.00,
    total_tva DECIMAL(15,2) DEFAULT 0.00,
    total_ttc DECIMAL(15,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_deliveries_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    CONSTRAINT fk_deliveries_livreur FOREIGN KEY (livreur_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_deliveries_depot FOREIGN KEY (depot_id) REFERENCES depots(id) ON DELETE CASCADE
);

-- =========================
-- DELIVERY ITEMS
-- =========================
CREATE TABLE delivery_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    delivery_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    qty_ordered INT NOT NULL,
    qty_delivered INT DEFAULT 0,
    returned_quantity INT DEFAULT 0,
    is_substitution TINYINT(1) DEFAULT 0,
    original_product_id BIGINT UNSIGNED NULL,
    unit_price_ht DECIMAL(15,2),
    promo_type ENUM('percentage','fixed'),
    promo_value DECIMAL(15,2) DEFAULT 0,
    tva_rate DECIMAL(5,2) DEFAULT 20.00,
    total_ht DECIMAL(15,2),
    total_tva DECIMAL(15,2),
    total_ttc DECIMAL(15,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_deliveryitems_delivery FOREIGN KEY (delivery_id) REFERENCES deliveries(id) ON DELETE CASCADE,
    CONSTRAINT fk_deliveryitems_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_deliveryitems_original FOREIGN KEY (original_product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- =========================
-- RETURNS
-- =========================
CREATE TABLE returns (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    delivery_id BIGINT UNSIGNED NOT NULL,
    livreur_id BIGINT UNSIGNED NOT NULL,
    depot_id BIGINT UNSIGNED,
    validated_by BIGINT UNSIGNED,
    status ENUM('pending','validated','rejected') DEFAULT 'pending',
    reason TEXT,
    rejected_reason TEXT,
    validated_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_returns_delivery FOREIGN KEY (delivery_id) REFERENCES deliveries(id) ON DELETE CASCADE,
    CONSTRAINT fk_returns_livreur FOREIGN KEY (livreur_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_returns_depot FOREIGN KEY (depot_id) REFERENCES depots(id) ON DELETE SET NULL,
    CONSTRAINT fk_returns_validated_by FOREIGN KEY (validated_by) REFERENCES users(id) ON DELETE SET NULL
);

-- =========================
-- RETURN ITEMS
-- =========================
CREATE TABLE return_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    return_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    delivery_item_id BIGINT UNSIGNED NOT NULL,
    quantity INT NOT NULL,
    condition_type ENUM('unsold','damaged','expired') NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_returnitems_return FOREIGN KEY (return_id) REFERENCES returns(id) ON DELETE CASCADE,
    CONSTRAINT fk_returnitems_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_returnitems_deliveryitem FOREIGN KEY (delivery_item_id) REFERENCES delivery_items(id) ON DELETE CASCADE
);

-- =========================
-- STOCK MOVEMENTS
-- =========================
CREATE TABLE stock_movements (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    depot_id BIGINT UNSIGNED,
    order_id BIGINT UNSIGNED,
    return_id BIGINT UNSIGNED,
    truck_id BIGINT UNSIGNED,
    type ENUM('in','out') NOT NULL,
    quantity INT NOT NULL,
    reason VARCHAR(255),
    moved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_stockmovements_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_stockmovements_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_stockmovements_depot FOREIGN KEY (depot_id) REFERENCES depots(id) ON DELETE CASCADE,
    CONSTRAINT fk_stockmovements_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
    CONSTRAINT fk_stockmovements_return FOREIGN KEY (return_id) REFERENCES returns(id) ON DELETE SET NULL,
    CONSTRAINT fk_stockmovements_truck FOREIGN KEY (truck_id) REFERENCES trucks(id) ON DELETE SET NULL
);

SET FOREIGN_KEY_CHECKS = 1;