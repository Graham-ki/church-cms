<?php
session_start();
if (isset($_SESSION)) {
    session_unset();
}
header("Location: login");
exit();