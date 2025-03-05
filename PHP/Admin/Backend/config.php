<?php
    // セッションが無いときだけ開始
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    };

    // if(!isset($_SESSION['admin'])) {

    // }

?>