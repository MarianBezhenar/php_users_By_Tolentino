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

-- ASSEGNAZIONI
CREATE TABLE assegnazioni (
    nickname VARCHAR(200) NOT NULL,
    id_prodotto VARCHAR(9) NOT NULL,
    PRIMARY KEY (nickname, id_prodotto),
    CONSTRAINT fk_prodotto
        FOREIGN KEY (id_prodotto)
        REFERENCES prodotti(id_prodotto)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_user
        FOREIGN KEY (nickname)
        REFERENCES users(nickname)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

--do 