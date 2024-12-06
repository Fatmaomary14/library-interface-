<?php
$conn = pg_connect("host=localhost dbname=retail user=postgres password=msfety1234");
if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

?>
