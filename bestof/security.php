<?php
session_start();    // for $_SESSION

if (!isset($_SESSION['user']))
{
    die();
}

?>
