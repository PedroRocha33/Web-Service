CREATE DATABASE IF NOT EXISTS db_webservice;
USE db_webservice;

-- Categories
CREATE TABLE categories (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products
CREATE TABLE products (
    id CHAR(36) PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    category_id CHAR(36),
    unit VARCHAR(10),
    cost_price DECIMAL(10, 2),
    sale_price DECIMAL(10, 2),
    min_stock INT,
    max_stock INT,
    active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Warehouses
CREATE TABLE warehouses (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    address TEXT,
    manager VARCHAR(100),
    active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Stock
CREATE TABLE stock (
    id CHAR(36) PRIMARY KEY,
    product_id CHAR(36),
    warehouse_id CHAR(36),
    current_quantity INT DEFAULT 0,
    reserved_quantity INT DEFAULT 0,
    available_quantity INT GENERATED ALWAYS AS (current_quantity - reserved_quantity) STORED,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id)
);

-- Stock Movements
CREATE TABLE stock_movements (
    id CHAR(36) PRIMARY KEY,
    product_id CHAR(36),
    warehouse_id CHAR(36),
    movement_type ENUM('IN', 'OUT', 'TRANSFER', 'ADJUSTMENT') NOT NULL,
    quantity INT NOT NULL,
    previous_quantity INT,
    new_quantity INT,
    reason TEXT,
    notes TEXT,
    user_id CHAR(36),
    reference_doc VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id)
);

-- Suppliers
CREATE TABLE suppliers (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    tax_id VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    contact VARCHAR(100),
    active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Stock Entries
CREATE TABLE stock_entries (
    id CHAR(36) PRIMARY KEY,
    document_number VARCHAR(100) NOT NULL,
    supplier_id CHAR(36),
    warehouse_id CHAR(36),
    entry_date DATE NOT NULL,
    total_value DECIMAL(12, 2),
    status ENUM('PENDING', 'PROCESSED', 'CANCELLED') DEFAULT 'PENDING',
    notes TEXT,
    user_id CHAR(36),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id)
);

-- Stock Entry Items
CREATE TABLE stock_entry_items (
    entry_id CHAR(36),
    product_id CHAR(36),
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    total_price DECIMAL(12, 2) GENERATED ALWAYS AS (quantity * unit_price) STORED,
    PRIMARY KEY (entry_id, product_id),
    FOREIGN KEY (entry_id) REFERENCES stock_entries(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Stock Exits
CREATE TABLE stock_exits (
    id CHAR(36) PRIMARY KEY,
    document_number VARCHAR(100) NOT NULL,
    client_id CHAR(36),
    warehouse_id CHAR(36),
    exit_date DATE NOT NULL,
    total_value DECIMAL(12, 2),
    status ENUM('PENDING', 'PROCESSED', 'CANCELLED') DEFAULT 'PENDING',
    notes TEXT,
    user_id CHAR(36),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id)
    -- client_id pode ser adicionado depois com uma tabela de clientes
);

-- Stock Exit Items
CREATE TABLE stock_exit_items (
    exit_id CHAR(36),
    product_id CHAR(36),
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    total_price DECIMAL(12, 2) GENERATED ALWAYS AS (quantity * unit_price) STORED,
    PRIMARY KEY (exit_id, product_id),
    FOREIGN KEY (exit_id) REFERENCES stock_exits(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Transfers
CREATE TABLE transfers (
    id CHAR(36) PRIMARY KEY,
    product_id CHAR(36),
    origin_warehouse_id CHAR(36),
    destination_warehouse_id CHAR(36),
    quantity INT NOT NULL,
    status ENUM('PENDING', 'PROCESSED', 'CANCELLED') DEFAULT 'PENDING',
    notes TEXT,
    user_id CHAR(36),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (origin_warehouse_id) REFERENCES warehouses(id),
    FOREIGN KEY (destination_warehouse_id) REFERENCES warehouses(id)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idType INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    photo VARCHAR(255) DEFAULT NULL
);



INSERT INTO products (id, code, name, description, sale_price, max_stock, category_id, created_at, updated_at) VALUES
(1, 'CI-001', 'Cimento CP-II 50kg', 'Saco de cimento para construção civil.', 36.90, 100, 'ID_CATEGORIA_1', NOW(), NOW()),
(2, 'AR-001', 'Areia Média 20kg', 'Saco de areia peneirada para alvenaria.', 12.50, 80, 'ID_CATEGORIA_1', NOW(), NOW()),
(3, 'BR-001', 'Brita 1 20kg', 'Brita para concreto.', 14.90, 60, 'ID_CATEGORIA_1', NOW(), NOW()),
(4, 'TI-001', 'Tijolo Baiano', 'Tijolo de 9 furos para alvenaria.', 1.20, 1000, 'ID_CATEGORIA_1', NOW(), NOW()),
(5, 'BL-001', 'Bloco de Concreto', 'Bloco estrutural para construção.', 3.50, 500, 'ID_CATEGORIA_1', NOW(), NOW()),
(6, 'MA-001', 'Martelo de Borracha', 'Martelo para assentamento de cerâmica.', 19.90, 50, 'ID_CATEGORIA_2', NOW(), NOW()),
(7, 'CO-001', 'Colher de Pedreiro', 'Ferramenta usada em alvenaria.', 14.00, 70, 'ID_CATEGORIA_2', NOW(), NOW()),
(8, 'TR-001', 'Trena 5m', 'Trena retrátil para medições.', 24.50, 40, 'ID_CATEGORIA_2', NOW(), NOW());

INSERT INTO users (idType, name, email, password, photo) VALUES
(1, 'Pedro', 'pedro@gmail.com', '$2y$10$abcdefghijklmnopqrstuvxyz1234567890abcdefghi', 'fotos/joao.jpg');

INSERT INTO categories (id, name, description, active, created_at, updated_at)
VALUES (
    1,
    'Materiais de Construção',
    'Produtos como cimento, areia, tijolos, etc.',
    1,
    NOW(),
    NOW()
);

