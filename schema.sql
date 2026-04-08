-- USERS
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    nickname VARCHAR(200) UNIQUE NOT NULL,
    email VARCHAR(200) UNIQUE NOT NULL,
    password VARCHAR(200) NOT NULL
);

-- PRODOTTI
CREATE TABLE prodotti (
    id_prodotto VARCHAR(9) PRIMARY KEY,
    tipo VARCHAR(200) NOT NULL,
    data_produzione DATE NOT NULL
);
