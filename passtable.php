<?php
$link = new mysqli("127.0.0.1",'root','pikachu','cloud');

$sql = "
CREATE TABLE filepass (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
filename VARCHAR(1000) NOT NULL,
password VARCHAR(30) NOT NULL,
reg_date TIMESTAMP
)
 ";

if ($link->query($sql) === TRUE) {
    echo "Table Filepass created successfully";
} else {
    $query="delete from filepass";
    $link->query($query);
    echo "Everything gone!";

}

$link->close();

?>

