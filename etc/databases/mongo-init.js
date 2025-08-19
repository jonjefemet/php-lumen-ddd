// MongoDB initialization script

// Switch to finger_auth database
db = db.getSiblingDB('finger_auth');

// Create users collection with indexes
db.createCollection('users');

// Create indexes
db.users.createIndex({ "email": 1 }, { unique: true });
db.users.createIndex({ "created_at": 1 });

// Insert sample user (optional)
db.users.insertOne({
    "_id": "550e8400-e29b-41d4-a716-446655440000",
    "email": "admin@finger.com",
    "password": "$argon2id$v=19$m=65536,t=4,p=3$example$hash", // This would be a real hash
    "name": "Admin User",
    "created_at": new Date().toISOString(),
    "updated_at": null
});

// Switch to finger_backoffice database
db = db.getSiblingDB('finger_backoffice');

// Create collections for backoffice
db.createCollection('analytics');
db.createCollection('logs');

print("MongoDB initialization completed for Finger microservices");
