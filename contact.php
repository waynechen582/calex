<?php
    include ('main.php');
    include ('ruby_class/page_bar.php');
    include_once 'ruby_class/securimage/securimage.php';
    
    $webpath = "upload";
    $today = date("Y-m-d");

    $securimage = new Securimage();
          
    //程式內容
    if (isset($_POST['sendMsg'])) {
        if ($securimage->check($_POST['captcha']) == false) {
            $msgerrsnarr = array (
                'zh-tw' => '驗證碼錯誤，請重新發送！',
                'zh-cn' => '验证码错误，请重新发送！',
                'en' => 'The verification code is wrong, please send again.'
            );
            echo "<meta http-equiv='content-type' content='text/html; charset=UTF-8' />";
            echo "<script language='javascript'>alert('".$msgerrsnarr[$cklang]."');location='contact.php';</script>";
        } else {
            $conName = trim($_POST['con_name']);
            $conEmail = trim($_POST['con_email']);
            $conSubject = trim($_POST['con_subject']);
            $conMsg = trim($_POST['con_message']);

            if ($conn) {
                // 預備 SQL 語句
                $sql = "INSERT INTO contact (conName, conEmail, conSubject, conMsg, newdate) VALUES (?, ?, ?, ?, GETDATE())";

                // 預備 SQL 指令
                $params = array($conName, $conEmail, $conSubject, $conMsg);
                $stmt = sqlsrv_query($conn, $sql, $params);

                // 釋放資源
                sqlsrv_free_stmt($stmt);
                sqlsrv_close($conn);
            } else {
                echo "連線失敗。錯誤訊息：" . print_r(sqlsrv_errors(), true);
            }

            $msgerrsnarr = array (
                'zh-tw' => '訊息已送出！',
                'zh-cn' => '讯息已送出！',
                'en' => 'Message sent.'
            );
            echo "<meta http-equiv='content-type' content='text/html; charset=UTF-8' />";
            echo "<script language='javascript'>alert('".$msgerrsnarr[$cklang]."');location='contact.php';</script>";
        }
    } else {
        $tpl_index->assign('contact',1);
        $tpl_index->display('main.html');
    }



?>
