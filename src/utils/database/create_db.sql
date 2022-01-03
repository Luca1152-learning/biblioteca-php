DROP TABLE IF EXISTS borrows;
DROP TABLE IF EXISTS book_instances;
DROP TABLE IF EXISTS books;
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
    book_id          INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title            VARCHAR(128) NOT NULL,
    cover_url        VARCHAR(128),
    publisher_id     INT          NOT NULL,
    publication_year INT,
    isbn             INT          NOT NULL,
    pages_count      INT,
    FOREIGN KEY (publisher_id) REFERENCES publishers (publisher_id)
);

CREATE TABLE books_authors
(
    book_id   INT NOT NULL,
    author_id INT NOT NULL,
    PRIMARY KEY (book_id, author_id),
    FOREIGN KEY (book_id) REFERENCES books (book_id),
    FOREIGN KEY (author_id) REFERENCES authors (author_id)
);

CREATE TABLE books_categories
(
    book_id     INT NOT NULL,
    category_id INT NOT NULL,
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