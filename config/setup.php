<?php
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
require(__DIR__ . '/connection.inc.php');
$usereq = ("CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passowrd` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `cle` varchar(255) NOT NULL,
  `actif` int(11) NOT NULL DEFAULT '0',
  `mailing` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
)");

   $picturereq = (" CREATE TABLE IF NOT EXISTS `images` (
    `imageid` int(11) NOT NULL AUTO_INCREMENT,
    `img_name` varchar(255) NOT NULL,
    `userid` int(11) NOT NULL,
    `creating_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`imageid`)
  )");
   
   $commentreq = (" CREATE TABLE IF NOT EXISTS `comment` (
    `comment` varchar(255) NOT NULL,
    `imageid` int(11) NOT NULL,
    `userid` int(11) NOT NULL,
    `creating_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
  )");
   
   $likereq = (" CREATE TABLE IF NOT EXISTS `likes` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `picture_id` int(11) NOT NULL,
    `liked_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
  )");
   
   if ($pdo->query($usereq) && $pdo->query($picturereq) && $pdo->query($commentreq) && $pdo->query($likereq))
   {
    echo "Schema ready (existing tables and data preserved).\n";
   }
?>
