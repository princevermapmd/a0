<?php
require("db.php");

// Check if the required POST keys are set
    $id = $_POST['sid'];
    $status = $_POST['s_status'];
    $table = $_POST['s_folder'];

    $update = "UPDATE $table SET star='$status' WHERE id ='$id'";

    if ($db->query($update)) 
    {
        echo "success";
    } 
    else
    {
        echo "failed";
    }
?>
