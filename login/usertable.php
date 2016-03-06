<?php
$link = new mysqli("127.0.0.1",'root','pikachu','cloud');

$sql = "
CREATE TABLE users (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(30) NOT NULL,
password VARCHAR(30) NOT NULL,
reg_date TIMESTAMP
)
 ";

if ($link->query($sql) === TRUE) {
    echo "Table Users created successfully";
} else {
    echo "Error creating table: " . $link->error;
}

$link->close();

?>

