<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "hospital_recruitment"
);

if(!$conn){
    die("Connection Failed");
}
?>