<?php
ob_start();
session_start();
require 'CommonMethods.php';

$debug = true;
$COMMON = new Common($debug);

//require 'users.php';
require 'Calendar.php';
require 'Advisor.php';
require 'Groups.php';

$CALENDAR = new Calendar();

$errors = array();
?>