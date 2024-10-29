USE tableCreator;

DROP TABLE IF EXISTS datasetAttributes;
DROP TABLE IF EXISTS dataset ;

CREATE TABLE dataset (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE datasetAttribute (
    id INT AUTO_INCREMENT PRIMARY KEY,
    datasetId INT NOT NULL,
    attributeName VARCHAR(255) NOT NULL,
    
    FOREIGN KEY (datasetId) REFERENCES dataset(id) ON DELETE CASCADE
);

INSERT INTO dataset (id, name) VALUES 
  ( 1, 'PHP' ),
  ( 2, 'Linux' );

INSERT INTO datasetAttribute (id, datasetId, attributeName) VALUES 
  ( 1, 1, 'Code Snippet' ),
  ( 2, 1, 'Example' ),
  ( 3, 2, 'Command' ),
  ( 4, 2, 'Usage' ),
  ( 5, 2, 'Example' );
