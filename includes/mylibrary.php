<?php
class User {
    public function Login($username,$pwd,$pdo) {
        $req = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $req->execute([$username]);
        $res = $req->fetch();
        if (password_verify($pwd, $res['passowrd'])) {
            return $res;
        } else {
            return false;
        }
    }
    public function Registre($username,$pwd,$email,$name,$key,$pdo) {
        $pwdhash = password_hash($pwd, PASSWORD_BCRYPT);
        $req = $pdo->prepare("INSERT INTO users SET username = ?, name = ?, passowrd = ?, email = ? , cle = ?");
        $req->execute([$username,$name,$pwdhash,$email,$key]);
        $id = $pdo->lastInsertId();
        return $id;
    }
    public function checkPassword($pwd) {
        $error = true;
        if (strlen($pwd) < 8) {
            $error = false;
        }

        if (!preg_match("#[0-9]+#", $pwd)) {
            $error = false;
        }

        if (!preg_match("#[a-zA-Z]+#", $pwd)) {
            $error = false;
        }     
        return ($error);
    }
    public function checkUsername($usernam) {
        $error = true;
        if (strlen($usernam) < 5) {
            $error = false;
        }
        if (!preg_match("#[a-zA-Z]+#", $usernam)) {
            $error = false;
        }     
        return ($error);
    }
    public function Update_profile($username,$pwd,$newpwd,$email,$mailing,$pdo) {
        $req = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $req->execute([$_SESSION['id']]);
        $res = $req->fetch();
        $message = "";
        if (password_verify($pwd, $res['passowrd'])) {
            if ($username != $res['username'])
            {
                if ($this->isUsername($username,$pdo))
                    return false;
                $message .= "\nyour username are modified";
                $pdo->prepare("UPDATE users SET username = ? WHERE id = ?")->execute([$username,$_SESSION['id']]);
                $_SESSION['username'] = $username;
            }
            if ($email != $res['email']) {
                if ($this->isEmail($email,$pdo))
                    return false;
                $message .= "\nyour email are modified";
                $pdo->prepare("UPDATE users SET email = ? WHERE id = ?")->execute([$email,$_SESSION['id']]);
                $_SESSION['email'] = $email;
            }
            if (empty($mailing) && $_SESSION['mailing'] !== 0)
            {
                $message .= "\nyour mailing option are modified";
                $pdo->prepare("UPDATE users SET mailing = 0 WHERE id = ?")->execute([$_SESSION['id']]);
                $_SESSION['mailing'] = 0;
            } else if (!empty($mailing) && $_SESSION['mailing'] != 1){
                $message .= "\nyour mailing option are modified";
                $pdo->prepare("UPDATE users SET mailing = 1 WHERE id = ?")->execute([$_SESSION['id']]);
                $_SESSION['mailing'] = 1;
            }
            if (!empty($newpwd))
            {
                $message .= "\nyour password are modified";
                $pwdhash = password_hash($newpwd, PASSWORD_BCRYPT);
                $pdo->prepare("UPDATE users SET passowrd = ? WHERE id = ?")->execute([$pwdhash,$_SESSION['id']]);
            }
            if ($message != "")
            {
                $destinataire =  $res['email'];
                $sujet = "Information changed";
                $entete = "From: mdichkou@camagru.com" ;
                mail($destinataire, $sujet, $message, $entete);
            }
            return true;
        } else {
            return false;
        }
    }
    public function find_user($userid,$pdo)
    {
        $query = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $query->execute([$userid]);
        $res = $query->fetch();
        return $res;
    }
    public function save_comment($imageid,$comment,$userid,$pdo) {
        $pdo->prepare("INSERT INTO comment SET imageid = ?, userid = ?, comment = ?")->execute([$imageid,$userid,$comment]);
    }
    public function save_like($imageid,$userid,$pdo) {
        $pdo->prepare("INSERT INTO likes SET `user_id` = ?, picture_id = ?")->execute([$userid,$imageid]);
    }
    public function delete_like($imageid,$userid,$pdo) {
        $pdo->prepare("DELETE FROM likes WHERE `user_id` = ? AND picture_id = ?")->execute([$userid,$imageid]);
    }
    public function delete_image($imageid,$pdo) {
        $req = $pdo->prepare("SELECT * FROM images WHERE `imageid` = ?");
        $req->execute([$imageid]);
        $res = $req->fetch();
        if (!empty($res['img_name']))
            unlink(dirname(__DIR__) . $res['img_name']);
        $pdo->prepare("DELETE FROM likes WHERE  picture_id = ?")->execute([$imageid]);
        $pdo->prepare("DELETE FROM comment WHERE  imageid = ?")->execute([$imageid]);
        $pdo->prepare("DELETE FROM images WHERE `imageid` = ?")->execute([$imageid]);
    }
    public function isUsername($username,$pdo)
    {
        try {
            $query = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $query->execute([$username]);
            if ($query->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
    public function isEmail($email,$pdo)
    {
        try {
            $query = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $query->execute([$email]);
            if ($query->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
    public function isLiked($userid,$imageid,$pdo)
    {
        try {
            $query = $pdo->prepare("SELECT id FROM likes WHERE `user_id` = ? AND picture_id = ?");
            $query->execute([$userid,$imageid]);
            if ($query->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}
?>
