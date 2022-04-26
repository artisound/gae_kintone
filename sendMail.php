<?php
//PHPの設定
date_default_timezone_set('Asia/Tokyo');
mb_language("ja");
mb_internal_encoding("UTF-8");

//PHPMailerの使用宣言
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//自身の環境に合わせてPHPMailer読み込みパスを修正
require_once('./vendor/autoload.php');
//PHPMailerの使用
$mailer = new PHPMailer(true);
try {
  $mailer->isSMTP();
  $mailer->CharSet    = 'UTF-8';
  $mailer->SMTPDebug  = 1;
  $mailer->SMTPAuth   = true;
  $mailer->SMTPSecure = 'ssl';
  $mailer->Port       = 465;
  $mailer->Host       = getenv('EMAIL_HOSTNAME');  //SMTPサーバ名
  $mailer->Username   = getenv('EMAIL_USERNAME');  // SMTP username
  $mailer->Password   = getenv('EMAIL_PASSWORD');  // SMTP password

  //送信者や宛先の設定
  $mailer->setFrom(getenv('EMAIL_FROM'), mb_encode_mimeheader('送信者'));
  $mailer->addAddress('k.nishizoe131@gmail.com');//送り先

  //メール内容
  $mailer->isHTML(true); // HTMLメール有効
  $mailer->Subject = mb_encode_mimeheader('件名');
  $mailer->Body    = 'HTML形式の本文 <b>太字</b>';
  $mailer->AltBody = '非HTML受信者用本文';

  $mailer->send();       //送信
  echo 'Message has been sent';

} catch (Exception $e) {
  echo 'Message could not be sent. Mailer Error: ', $mailer->ErrorInfo;
}