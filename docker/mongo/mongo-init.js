db = db.getSiblingDB(_getEnv('MONGO_INITDB_DATABASE'));
db.createCollection(_getEnv('MONGO_INITDB_DATABASE'));
