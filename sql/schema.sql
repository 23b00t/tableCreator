CREATE TABLE main (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE mainAttributes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mainId INT NOT NULL,
    attributeName VARCHAR(255) NOT NULL,
    
    FOREIGN KEY (mainId) REFERENCES main(id)
);

INSERT INTO main (id, name) VALUES 
  ( 1, 'PHP' ),
  ( 2, 'Linux' );

INSERT INTO mainAttributes (id, mainId, attributeName) VALUES 
  ( 1, 1, 'Code Snippet' ),
  ( 2, 1, 'Example' ),
  ( 3, 2, 'Command' ),
  ( 4, 2, 'Usage' ),
  ( 5, 2, 'Example' );
