-- Create databases for microservices
CREATE DATABASE IF NOT EXISTS microservices_users;
CREATE DATABASE IF NOT EXISTS microservices_products;

-- Grant privileges to root user for all databases
GRANT ALL PRIVILEGES ON microservices_users.* TO 'root'@'%';
GRANT ALL PRIVILEGES ON microservices_products.* TO 'root'@'%';

-- Flush privileges
FLUSH PRIVILEGES;
