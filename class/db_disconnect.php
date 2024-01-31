<?php
// db_disconnect.php
if (isset($dbConnect) && $dbConnect) {
    $dbConnect->close();
}
?>