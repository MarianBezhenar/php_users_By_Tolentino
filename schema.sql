-- ============================================
-- TABELLA users
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    nickname VARCHAR(200) UNIQUE NOT NULL,
    email VARCHAR(200) UNIQUE NOT NULL,
    password VARCHAR(200) NOT NULL
);

-- ============================================
-- TABELLA prodotti
-- ============================================
CREATE TABLE IF NOT EXISTS prodotti (
    id_prodotto VARCHAR(9) PRIMARY KEY,
    tipo VARCHAR(200) NOT NULL,
    data_produzione DATE NOT NULL
);

-- ============================================
-- TABELLA assegnazioni (collegamento user → prodotto)
-- ============================================
CREATE TABLE IF NOT EXISTS assegnazioni (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL,
    id_prodotto VARCHAR(9),
    CONSTRAINT fk_assegnazioni_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_assegnazioni_prodotto
        FOREIGN KEY (id_prodotto)
        REFERENCES prodotti(id_prodotto)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Indice unico condizionale: evita duplicati (user_id, id_prodotto) solo se id_prodotto non è NULL
CREATE UNIQUE INDEX IF NOT EXISTS idx_assegnazioni_unique
ON assegnazioni (user_id, id_prodotto)
WHERE id_prodotto IS NOT NULL;