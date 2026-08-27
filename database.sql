CREATE TABLE IF NOT EXISTS utenti (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cognome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO utenti (nome, cognome, email) VALUES
('Mario', 'Rossi', 'mario.rossi@example.com'),
('Luigi', 'Verdi', 'luigi.verdi@example.com');