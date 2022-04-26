<?php
require('./vendor/autoload.php');
include_once('./kintoneAPI.php');
include_once('./sendMail.php');
// dotenv
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$kintone = new \CybozuHttp\Api\KintoneApi(new \CybozuHttp\Client([
  'domain'    => 'cybozu.com',
  'subdomain' => getenv('KINTONE_SUBDOMAIN'),
  'login'     => getenv('KINTONE_LOGIN_ID'),
  'password'  => getenv('KINTONE_PASSWORD'),
]));