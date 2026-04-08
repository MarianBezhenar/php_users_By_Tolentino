-- Tabella users
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    nickname VARCHAR(200) UNIQUE NOT NULL,
    email VARCHAR(200) UNIQUE NOT NULL,
    password VARCHAR(200) NOT NULL
);

-- Tabella prodotti
CREATE TABLE IF NOT EXISTS prodotti (
    id_prodotto VARCHAR(9) PRIMARY KEY,
    tipo VARCHAR(200) NOT NULL,
    data_produzione DATE NOT NULL
);

-- Tabella assegnazioni (con supporto a NULL per id_prodotto)
CREATE TABLE IF NOT EXISTS assegnazioni (
    id SERIAL PRIMARY KEY,
    nickname VARCHAR(200) NOT NULL,
    id_prodotto VARCHAR(9),
    CONSTRAINT fk_user FOREIGN KEY (nickname) REFERENCES users(nickname) ON DELETE CASCADE,
    CONSTRAINT fk_prodotto FOREIGN KEY (id_prodotto) REFERENCES prodotti(id_prodotto) ON DELETE CASCADE
);

-- Indice unico per evitare duplicati (solo quando id_prodotto non è NULL)
CREATE UNIQUE INDEX IF NOT EXISTS idx_assegnazioni_unique 
ON assegnazioni (nickname, id_prodotto) 
WHERE id_prodotto IS NOT NULL;