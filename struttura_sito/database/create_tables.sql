CREATE TABLE utenti (
    id INT AUTO_INCREMENT,
    email VARCHAR(50) NOT NULL,
    pword VARCHAR(256) NOT NULL,
    username VARCHAR(20) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (id),
    UNIQUE (username)
);

CREATE TABLE ricette (
    id INT AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL,
    is_vegan BOOLEAN DEFAULT FALSE,
    tipo VARCHAR(20) NOT NULL, 
    tag VARCHAR(20),
    ingredienti TEXT NOT NULL,
    procedimento TEXT NOT NULL,
    autore INT NOT NULL,
    PRIMARY KEY (id),
    UNIQUE (nome),
    FOREIGN KEY (autore) REFERENCES utenti (id)
);

CREATE TABLE commenti (
    id INT AUTO_INCREMENT,
    autore INT,
    ricetta INT,
    contenuto TEXT NOT NULL,
    data_ora DATETIME,
    PRIMARY KEY (id),
    FOREIGN KEY (autore) REFERENCES utenti (id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (ricetta) REFERENCES ricette (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE likes (
    utente INT,
    ricetta INT,
    PRIMARY KEY (utente, ricetta),
    FOREIGN KEY (utente) REFERENCES utenti (id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (ricetta) REFERENCES ricette (id) ON DELETE CASCADE ON UPDATE CASCADE
);