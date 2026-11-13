<?php
session_start();
if (empty($_POST) || empty($_SESSION))
    header("Location: ../index.php");
$id = $_SESSION['id'];
$folder = "/uploads/";
$destinationFolder = dirname(__DIR__) . $folder;
list($type, $data) = explode(';', $_POST['data']);
list(, $data)      = explode(',', $data);
$postdata = $_POST['data'];
$option = $_POST['option'];
if (empty($_POST['data']))
    exit(json_encode(["success" => false, "reason" => "Not a post data"]));
if (strpos($postdata,"image/png"))
    $ext = ".png";
else if (strpos($postdata,"image/jpeg"))
    $ext = ".jpeg";
else
    exit(json_encode(['success' => false, 'reason' => 'the image need to be png or jpeg']));
$filename = date("d_m_Y_H_i_s") . "-" . time() . "-" ."$id$ext";
$destinationPath = "$destinationFolder$filename";
function generateImage($img,$file)
{
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace('data:image/jpeg;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $img = base64_decode($img);
    $success = file_put_contents($file, $img);
    return $success;
}
$success = generateImage($postdata,$destinationPath);
if (!$success)
    exit(json_encode(['success' => false, 'reason' => 'the server failed in creating the image']));
switch ($option) {
    case "iron":
        $mask = imagecreatefrompng("../img/ironman.png");
        break;
    case "batman":
        $mask = imagecreatefrompng("../img/batman.png");
        break;
    case "joker":
        $mask = imagecreatefrompng("../img/joker.png");
        break;
    case "off":
        $mask = "";
        break;
}
if ($option != "off")
{
    $img2 = imagecreatefrompng($destinationPath);
    imagecopy($img2,$mask,0,0,0,0,200,200);
    $filename = date("d_m_Y_H_i_s") . "-" . time() . "-" .$id. "-edited" . $ext;
    $destinationPath = "$destinationFolder$filename";
    imagepng($img2,$destinationPath);
}
require('../config/connection.inc.php');
$pdo->prepare("INSERT INTO images SET img_name = ?, userid = ?")->execute(["$folder$filename",$id]);
exit(json_encode(['success' => "true", 'path' => "$folder$filename"]));
?>
