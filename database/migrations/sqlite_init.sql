PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    level VARCHAR(255) NOT NULL,
    rattachement VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS level (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS statement (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    state VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    dateInscription DATE NOT NULL,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    pseudo VARCHAR(255) NOT NULL,
    mail VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    photo VARCHAR(255) NOT NULL DEFAULT '',
    recovery VARCHAR(255) NOT NULL DEFAULT '',
    token VARCHAR(255) NOT NULL,
    id_level INTEGER NOT NULL,
    id_statement INTEGER NOT NULL,
    FOREIGN KEY (id_level) REFERENCES level(id),
    FOREIGN KEY (id_statement) REFERENCES statement(id)
);

CREATE TABLE IF NOT EXISTS stucture (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(255) NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    codePostal VARCHAR(255) NOT NULL,
    ville VARCHAR(255) NOT NULL,
    mail VARCHAR(255) NOT NULL,
    telephone VARCHAR(255) NOT NULL,
    referent VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS ressources (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    date DATE NOT NULL,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255) NOT NULL DEFAULT '',
    deroule VARCHAR(255) NOT NULL DEFAULT '',
    tuto VARCHAR(255) NOT NULL DEFAULT '',
    slug VARCHAR(255) NOT NULL DEFAULT '',
    id_categories INTEGER NOT NULL,
    id_users INTEGER NOT NULL,
    id_stucture INTEGER NOT NULL,
    FOREIGN KEY (id_categories) REFERENCES categories(id),
    FOREIGN KEY (id_users) REFERENCES users(id),
    FOREIGN KEY (id_stucture) REFERENCES stucture(id)
);

CREATE TABLE IF NOT EXISTS commentaires (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    comment TEXT NOT NULL,
    date DATE NOT NULL,
    id_users INTEGER NOT NULL,
    id_ressources INTEGER NOT NULL,
    FOREIGN KEY (id_users) REFERENCES users(id),
    FOREIGN KEY (id_ressources) REFERENCES ressources(id)
);
