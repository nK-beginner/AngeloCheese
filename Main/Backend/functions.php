




<?php
    /********** Login **********/
    // レートリミットチェック
    function fncLoginRateLimit($ip) {
        $failedLoginKey = 'failed_login_' . $ip;
    
        if (isset($_SESSION[$failedLoginKey]) && $_SESSION[$failedLoginKey]['count'] >= 5) {
            if (time() - $_SESSION[$failedLoginKey]['last_attempt'] < 900) {
                return true;
                
            } else {
                unset($_SESSION[$failedLoginKey]);
            }
        }
        return null;
    }
    
    // ログイン失敗回数
    function fncRecordFailedLogins($ip) {
        $failedLoginKey = 'failed_login_' . $ip;
    
        $_SESSION[$failedLoginKey]['count'] = ($_SESSION[$failedLoginKey]['count'] ?? 0) + 1;
        $_SESSION[$failedLoginKey]['last_attempt'] = time();
    }

    /********** Register **********/
    // レートリミットチェック
    function fncRegisterRateLimit($ip) {
        $failedRegisterKey = 'register_attempt_' . $ip;

        if(isset($_SESSION[$failedRegisterKey]) && $_SESSION[$failedRegisterKey]['count'] >= 3) {
            if(time() - $_SESSION[$failedRegisterKey]['last_attempt'] < 3600) {
                return true;

            } else {
                unset($_SESSION[$failedRegisterKey]);
            }
        }
        return null;
    }
?>