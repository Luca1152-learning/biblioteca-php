DROP TABLE IF EXISTS borrows;
DROP TABLE IF EXISTS copies;
DROP TABLE IF EXISTS books_categories;
DROP TABLE IF EXISTS books_authors;
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS publishers;
DROP TABLE IF EXISTS authors;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;

CREATE TABLE roles
(
    role VARCHAR(64) PRIMARY KEY
);

INSERT INTO roles(role)
VALUES ('user'),
       ('bibliotecar'),
       ('administrator');

CREATE TABLE users
(
    user_id          INT                NOT NULL AUTO_INCREMENT,
    email            VARCHAR(64) UNIQUE NOT NULL,
    password         VARCHAR(128)       NOT NULL,
    first_name       VARCHAR(64)        NOT NULL,
    last_name        VARCHAR(64)        NOT NULL,
    role             VARCHAR(64)        NOT NULL DEFAULT 'user',
    sign_up_date     DATETIME           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_online_date DATETIME           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id),
    FOREIGN KEY (role) REFERENCES roles (role)
);

CREATE TABLE authors
(
    author_id INT                NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name      VARCHAR(64) UNIQUE NOT NULL
);

CREATE TABLE publishers
(
    publisher_id INT                NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(64) UNIQUE NOT NULL
);

CREATE TABLE categories
(
    category_id INT                NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(64) UNIQUE NOT NULL
);

CREATE TABLE books
(
    book_id                INT                 NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title                  VARCHAR(128) UNIQUE NOT NULL,
    description            TEXT,
    cover_url              VARCHAR(256),
    publisher_id           INT                 NOT NULL,
    first_publication_year INT,
    pages_count            INT,
    FOREIGN KEY (publisher_id) REFERENCES publishers (publisher_id)
);

CREATE TABLE books_authors
(
    book_id        INT  NOT NULL,
    author_id      INT  NOT NULL,
    is_main_author BOOL NOT NULL DEFAULT FALSE,
    qualifier      VARCHAR(64),
    PRIMARY KEY (book_id, author_id),
    FOREIGN KEY (book_id) REFERENCES books (book_id),
    FOREIGN KEY (author_id) REFERENCES authors (author_id)
);

CREATE TABLE books_categories
(
    book_id     INT NOT NULL,
    category_id INT NOT NULL,
    list_index  INT NOT NULL,
    PRIMARY KEY (book_id, category_id),
    FOREIGN KEY (book_id) REFERENCES books (book_id),
    FOREIGN KEY (category_id) REFERENCES categories (category_id)
);

CREATE TABLE copies
(
    copy_id    INT      NOT NULL AUTO_INCREMENT,
    book_id    INT      NOT NULL,
    date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    comments   TEXT,
    PRIMARY KEY (copy_id),
    FOREIGN KEY (book_id) REFERENCES books (book_id)
);

CREATE TABLE borrows
(
    borrow_id       INT NOT NULL AUTO_INCREMENT,
    user_id         INT NOT NULL,
    copy_id         INT NOT NULL,
    borrow_date     DATE,
    return_due_date DATE,
    return_date     DATE,
    PRIMARY KEY (borrow_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id),
    FOREIGN KEY (copy_id) REFERENCES copies (copy_id)
);

-- Test values
INSERT INTO authors(name)
VALUES ('Jane Austen');

INSERT INTO publishers(name)
VALUES ('Modern Library Classics, USA / CAN');

INSERT INTO publishers(name)
VALUES ('Penguin Books');

INSERT INTO categories(name)
VALUES ('Classics'),
       ('Fiction'),
       ('Romance');


-- Pride and Prejudice
INSERT INTO books(title, description, cover_url, publisher_id, first_publication_year, pages_count)
VALUES ('Pride and Prejudice',
        'Since its immediate success in 1813, Pride and Prejudice has remained one of the most popular novels in the English language. Jane Austen called this brilliant work "her own darling child" and its vivacious heroine, Elizabeth Bennet, "as delightful a creature as ever appeared in print." The romantic clash between the opinionated Elizabeth and her proud beau, Mr. Darcy, is a splendid performance of civilized sparring. And Jane Austen''s radiant wit sparkles as her characters dance a delicate quadrille of flirtation and intrigue, making this book the most superb comedy of manners of Regency England.',
        'https://i.gr-assets.com/images/S/compressed.photo.goodreads.com/books/1320399351l/1885.jpg', 5, 1813, 279);

INSERT INTO books_authors(book_id, author_id, is_main_author)
VALUES (5, 5, TRUE);

INSERT INTO books_categories(book_id, category_id, list_index)
VALUES (5, 5, 0),
       (5, 15, 1),
       (5, 25, 2);

INSERT INTO copies(book_id)
VALUES (5);


-- Sense and Sensibility
INSERT INTO books(title, description, cover_url, publisher_id, first_publication_year, pages_count)
VALUES ('Sense and Sensibility',
        '\'The more I know of the world, the more am I convinced that I shall never see a man whom I can really love. I require so much!\'

Marianne Dashwood wears her heart on her sleeve, and when she falls in love with the dashing but unsuitable John Willoughby she ignores her sister Elinor''s warning that her impulsive behaviour leaves her open to gossip and innuendo. Meanwhile Elinor, always sensitive to social convention, is struggling to conceal her own romantic disappointment, even from those closest to her. Through their parallel experience of love—and its threatened loss—the sisters learn that sense must mix with sensibility if they are to find personal happiness in a society where status and money govern the rules of love.

This edition includes explanatory notes, textual variants between the first and second editions, and Tony Tanner\'s introduction to the original Penguin Classic edition.',
        'https://i.gr-assets.com/images/S/compressed.photo.goodreads.com/books/1397245675l/14935._SY475_.jpg', 15, 1811,
        409);

INSERT INTO books_authors(book_id, author_id, is_main_author)
VALUES (15, 5, TRUE);

INSERT INTO books_categories(book_id, category_id, list_index)
VALUES (15, 5, 0),
       (15, 15, 1),
       (15, 25, 2);

COMMIT;