CREATE TABLE Ticket (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
    titel VARCHAR(255) NOT NULL, 
    beschreibung VARCHAR(255) NOT NULL, 
    status VARCHAR(255) NOT NULL 
);

CREATE TABLE Benutzer (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL, 
    mail VARCHAR(255) NOT NULL 
);

CREATE TABLE Zuweisung (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
    ticket_id INT(11) NOT NULL, 
    user_id INT(11) NOT NULL

);