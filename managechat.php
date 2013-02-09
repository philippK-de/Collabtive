<?php
include("init.php");
if (!isset($_SESSION["userid"]))
{
    $template->assign("loginerror", 0);
    $template->display("login.tpl");
    die();
}

$action = getArrayVal($_GET, "action");
$userto = getArrayVal($_GET, "userto");
$userto_id = getArrayVal($_GET, "uid");
$userto_id = (int) $userto_id;
if (!$action)
{

    $now = time();
    $now = $now - 35;
    $cook = "chatstart" . $userto_id;
    $cook2 = "chatwin" . $userto_id;

    setcookie("$cook", "$now");
    setcookie("$cook2", "1");

    $template->assign("userto", $userto);
    $template->assign("userto_id", $userto_id);

    $user = new user();
    $avatar = $user->getAvatar($userto_id);

    $template->assign("avatar", $avatar);

    $template->display("chatwin.tpl");
}
if ($action == "post")
{
    if (!$userpermissions["chat"]["add"])
    {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    $content = strip_tags($_POST['content']);

    $userto_id = (int) $_POST['userto_id'];
    // $content = utf8_decode($content);
    $now = time();

    $insStmt = $conn->prepare("INSERT INTO chat (time, ufrom, ufrom_id, userto, userto_id, text) VALUES (?, ?, ?, ?, ?, ?)");
    $insStmt->execute(array($now, $username, $userid, $_POST['userto'], $userto_id, $content));
    
} elseif ($action == "pull")
{
    $cook = "chatstart" . $userto_id;
    $start = $_COOKIE["$cook"];
    $start = (int) $start;
    if (!$start)
    {
        $start = 0;
    }

    $sel = $conn->query("SELECT * FROM chat WHERE ufrom_id IN($userid,$userto_id) AND userto_id IN($userid,$userto_id) AND time > $start ORDER by time ASC");
    while ($chat = $sel->fetch())
    {
        $date = date("H:i", $chat["time"]);
        echo "[$date] <b>$chat[ufrom]:</b> $chat[text]";
        echo "<br />";
    }
} elseif ($action == "chk")
{
    $now = time();
    $now = $now - 20;

    $sel = $conn->query("SELECT ufrom_id,ufrom FROM chat WHERE userto_id  = $userid AND time > $now");

    while ($chk = $sel->fetch())
    {
        $cook = "chatwin" . $chk[0];
        if (!$_COOKIE[$cook])
        {
            echo "<script type = \"text/javascript\">openChatwin('$chk[1]',$chk[0]);</script>";
        }
    }
    $mynow = time();
    $upd = $conn->exec("UPDATE user SET lastlogin='$mynow' WHERE ID = $userid");
}

?>