<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo '<pre>';  // Formats the output in a readable way
    print_r($_POST);  // Prints all POST data
    echo '</pre>';
    exit();
}
?>