CREATE TABLE roles
(
    role VARCHAR(64) PRIMARY KEY
);

INSERT INTO roles(role)
VALUES ('user'),
       ('bibliotecar') ('administrator');

CREATE TABLE users
(
    user_id          INT                NOT NULL AUTO_INCREMENT,
    email            VARCHAR(64) UNIQUE NOT NULL,
    password         VARCHAR(128)       NOT NULL,
    first_name       VARCHAR(64)        NOT NULL,
    last_name        VARCHAR(64)        NOT NULL,
    role             VARCHAR(64),
    sign_up_date     DATETIME           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_online_date DATETIME           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id),
    FOREIGN KEY (role) REFERENCES roles (role)
);

CREATE TABLE authors
(
    author_id  INT         NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(64) NOT NULL,
    last_name  VARCHAR(64) NOT NULL,
    birth_year INT,
    death_year INT,
    PRIMARY KEY (author_id),
    UNIQUE KEY unique_name (first_name, last_name)
);

CREATE TABLE publishers
(
    publisher_id INT         NOT NULL AUTO_INCREMENT,
    name         VARCHAR(64) NOT NULL,
    PRIMARY KEY (publisher_id),
    UNIQUE (name)
);

CREATE TABLE books
(
    book_id      INT          NOT NULL AUTO_INCREMENT,
    title        VARCHAR(128) NOT NULL,
    edition      VARCHAR(128),
    author_id    INT          NOT NULL,
    publisher_id INT          NOT NULL,
    pages_count  INT,
    PRIMARY KEY (book_id),
    FOREIGN KEY (author_id) REFERENCES authors (author_id),
    FOREIGN KEY (publisher_id) REFERENCES publishers (publisher_id),
    UNIQUE KEY unique_book (title, author_id, publisher_id)
)

CREATE TABLE book_instances
(
    book_instance_id INT      NOT NULL AUTO_INCREMENT,
    book_id          INT      NOT NULL,
    date_added       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    comments         TEXT,
    PRIMARY KEY (book_instance_id),
    FOREIGN KEY (book_id) REFERENCES books (book_id)
)

CREATE TABLE borrows
(
    borrow_id        INT NOT NULL AUTO_INCREMENT,
    user_id          INT NOT NULL,
    book_instance_id INT NOT NULL,
    borrow_date      DATE,
    return_due_date  DATE,
    return_date      DATE,
    PRIMARY KEY (borrow_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id),
    FOREIGN KEY (book_instance_id) REFERENCES book_instances (book_instance_id)
);