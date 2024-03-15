<?php // server_FormToken.php
/**
 * A server side script that responds to an AJAX request
 * This script gets a form token object and encodes it into a JSON string
 * It stores the JSON string in the PHP session and echos it to the client
 */
error_reporting(E_ALL);
require_once('class_FormToken.php');
session_start();


// Get, save, and return a new form token object
$token = FormToken::get();
$_SESSION[$token->name] = json_encode($token);
session_write_close();
echo $_SESSION[$token->name];
