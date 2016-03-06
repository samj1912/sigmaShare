<?php
$link = new mysqli("127.0.0.1",'root','pikachu','cloud');

$sql = "
CREATE TABLE shortner (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
link VARCHAR(10000) NOT NULL,
short VARCHAR(30) NOT NULL,
reg_date TIMESTAMP
)
 ";

if ($link->query($sql) === TRUE) {
    echo "Table shortlink created successfully";
} else {
    $query="delete from shortner";
    $link->query($query);
    echo "Everything gone!";

}

$link->close();

?>

