<?php
// Hapus semua cookie
setcookie('user_id', '', time() - 3600, '/', '', true, true);
setcookie('nama', '', time() - 3600, '/', '', true, false);
setcookie('email', '', time() - 3600, '/', '', true, false);
setcookie('role', '', time() - 3600, '/', '', true, false);

header("Location: /landing_page.php");
exit;
?>