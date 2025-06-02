CREATE TABLE IF NOT EXISTS image_sizes
(
    id        INT AUTO_INCREMENT PRIMARY KEY,
    size_code VARCHAR(10) NOT NULL,
    width     INT         NOT NULL,
    height    INT         NOT NULL
);

INSERT INTO image_sizes (size_code, width, height)
VALUES ('big', 800, 600),
       ('min', 300, 200)
ON DUPLICATE KEY UPDATE width=VALUES(width),
                        height=VALUES(height);