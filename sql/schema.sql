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
