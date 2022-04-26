<?php
require('./vendor/autoload.php');
include_once('./kintoneAPI.php');
include_once('./sendMail.php');
// dotenv
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$kintone = new kintoneAPI(
  getenv('KINTONE_SUBDOMAIN'),
  getenv('KINTONE_LOGIN_ID'),
  getenv('KINTONE_PASSWORD')
);