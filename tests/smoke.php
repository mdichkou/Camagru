<?php
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
// Keep CLI output buffered until after reading the synthetic captcha session.
ob_start();
// Run inside web: php tests/smoke.php [--verify-persistence]
// Creates only synthetic local data; intentionally retains it for recreation checks.
require __DIR__ . '/../config/connection.inc.php';
require __DIR__ . '/../config/application.php';

function check($condition, $label) {
    if (!$condition) {
        throw new RuntimeException($label);
    }
    echo "PASS: $label\n";
}

$marker = __DIR__ . '/../uploads/.smoke.json';
if (in_array('--verify-persistence', $argv, true)) {
    check(is_file($marker), 'photo volume marker survives recreation');
    $saved = json_decode(file_get_contents($marker), true, 512, JSON_THROW_ON_ERROR);
    $query = $pdo->prepare('SELECT img_name FROM images WHERE imageid = ? AND userid = ?');
    $query->execute([$saved['imageid'], $saved['userid']]);
    $path = $query->fetchColumn();
    check($path === $saved['path'], 'database image record survives recreation');
    $file = __DIR__ . '/..' . $path;
    check(is_file($file) && hash_file('sha256', $file) === $saved['sha256'], 'saved photo bytes survive recreation');
    exit;
}

check(extension_loaded('gd') && extension_loaded('pdo_mysql'), 'GD and PDO MySQL loaded');
check(gd_info()['PNG Support'] && gd_info()['JPEG Support'], 'PNG and JPEG supported');
check(is_writable(dirname($marker)), 'photo volume writable');
$before = [];
foreach (['users', 'images', 'comment', 'likes'] as $table) {
    $before[$table] = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
}
// Exercise the actual initializer twice, including its handling of existing tables.
require __DIR__ . '/../config/setup.php';
require __DIR__ . '/../config/setup.php';
foreach ($before as $table => $count) {
    check($pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn() === $count, "initialization preserves $table records");
}

$cookie = '';
function request($path, $data = null) {
    global $cookie;
    $options = ['method' => $data === null ? 'GET' : 'POST', 'ignore_errors' => true,
        'follow_location' => 0, 'timeout' => 15,
        'header' => "Cookie: $cookie\r\nContent-Type: application/x-www-form-urlencoded\r\n"];
    if ($data !== null) {
        $options['content'] = http_build_query($data);
    }
    // Do not echo account link query tokens if the local HTTP request fails.
    $body = @file_get_contents('http://localhost' . $path, false, stream_context_create(['http' => $options]));
    foreach ($http_response_header ?? [] as $header) {
        if (preg_match('/^Set-Cookie: (PHPSESSID=[^;]+)/i', $header, $match)) {
            $cookie = $match[1];
        }
    }
    if ($body === false) {
        throw new RuntimeException('HTTP request failed');
    }
    return [$http_response_header[0], $body];
}

function mailLink($subject, $username, $route) {
    global $APP_URL;
    for ($attempt = 0; $attempt < 20; $attempt++) {
        $messages = json_decode(file_get_contents('http://mailpit:8025/api/v1/messages'), true, 512, JSON_THROW_ON_ERROR);
        foreach ($messages['messages'] as $message) {
            if ($message['Subject'] !== $subject) {
                continue;
            }
            $detail = json_decode(file_get_contents('http://mailpit:8025/api/v1/message/' . $message['ID']), true, 512, JSON_THROW_ON_ERROR);
            $prefix = $APP_URL . '/' . $route . '?log=' . urlencode($username) . '&cle=';
            if (preg_match('~' . preg_quote($prefix, '~') . '[a-z0-9]+~', $detail['Text'], $match)) {
                echo "PASS: local email contains configured $route URL\n";
                return substr($match[0], strlen($APP_URL));
            }
        }
        usleep(250000);
    }
    throw new RuntimeException('Expected local email/link missing');
}

[$status] = request('/home.php');
check(str_contains($status, '200'), 'public gallery responds');
request('/index.php');
[$status, $captcha] = request('/image.php');
check(str_contains($status, '200') && str_starts_with($captcha, "\x89PNG"), 'captcha renders through GD');
// Read this synthetic client's captcha session in the same container, without OCR.
session_id(substr($cookie, strlen('PHPSESSID=')));
session_start(['read_and_close' => true]);
$code = $_SESSION['code'];
$username = 'smoke' . bin2hex(random_bytes(5));
$password = bin2hex(random_bytes(12)) . 'Aa1';
[$status] = request('/index.php', ['btnsignup' => '1', 'username' => $username,
    'name' => 'Synthetic Smoke', 'email' => $username . '@example.test', 'pwd' => $password, 'captcha' => $code]);
check(str_contains($status, '302'), 'registration redirects');
$activation = mailLink('Activer votre compte', $username, 'activation.php');
[$status] = request($activation);
check(str_contains($status, '200'), 'activation link responds');
$query = $pdo->prepare('SELECT id, actif FROM users WHERE username = ?');
$query->execute([$username]);
$user = $query->fetch();
check((int)$user['actif'] === 1, 'activation changes account state');
request('/forgotpw.php', ['btnsend' => '1', 'username' => $username]);
$recovery = mailLink('Changer votre password', $username, 'resetpw.php');
[$status, $body] = request($recovery);
check(str_contains($status, '200') && str_contains($body, 'name="newpw"'), 'recovery link opens reset form');
// Recovery GET adds session keys; use a fresh client for the login journey.
$cookie = '';
[$status] = request('/signin.php', ['btnlogin' => '1', 'username' => $username, 'password' => $password]);
check(str_contains($status, '302'), 'login redirects');
[$status] = request('/includes/login.inc.php');
check(str_contains($status, '302'), 'active login accepted');
[$status] = request('/camera.php');
check(str_contains($status, '200'), 'camera page responds');
$image = imagecreatetruecolor(490, 390);
ob_start();
imagepng($image);
$png = ob_get_clean();
imagedestroy($image);
[$status, $body] = request('/api/save_image.php', ['data' => 'data:image/png;base64,' . base64_encode($png), 'option' => 'batman']);
$result = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
check(str_contains($status, '200') && !empty($result['success']), 'synthetic local photo upload with overlay');
check(str_starts_with($result['path'], '/uploads/'), 'new photos separate from committed samples');
[$status, $photo] = request($result['path']);
check(str_contains($status, '200') && str_starts_with($photo, "\x89PNG"), 'saved photo served');
$query = $pdo->prepare('SELECT imageid FROM images WHERE userid = ? AND img_name = ?');
$query->execute([$user['id'], $result['path']]);
$imageid = $query->fetchColumn();
check($imageid !== false, 'photo database record saved');
file_put_contents($marker, json_encode(['userid' => $user['id'], 'imageid' => $imageid,
    'path' => $result['path'], 'sha256' => hash('sha256', $photo)], JSON_THROW_ON_ERROR));
echo "Synthetic data retained. Recreate containers, then run --verify-persistence.\n";
