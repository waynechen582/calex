<?php
require_once 'config.php';
require_once ('ruby_class/ruby_function.php');

$ruby_fn = new rubyfunction();

//$host = $_SERVER['HTTP_HOST'];
//$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
//if ($ruby_fn->is_SSL()) {
//    $httpStr = "https://";
//} else {
//    $httpStr = "http://";
//}

session_start();

    if (!isset($_SESSION['valid_lang'])) {
        $lang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
        if ($lang == "zh") {
            $lang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5));    
        }
        $acceptLang = ['zh-tw', 'zh-cn', 'en']; 
        $lang = in_array($lang, $acceptLang) ? $lang : 'en';    
        $_SESSION['valid_lang'] = $lang;
    }
    $cklang = $_SESSION['valid_lang'];

    $langarray = array (
        'zh-tw' => '繁體中文',
        'zh-cn' => '简体中文',
        'en' => 'English',
    );

    if ($cklang == "zh-tw") {        
        $deflangid = "zh-tw";
        $deflang = $langarray["zh-tw"]; 
    } elseif ($cklang == "zh-cn") {
        $deflangid = "zh-cn";
        $deflang = $langarray["zh-cn"];
    } elseif ($cklang == "en") {
        $deflangid = "en";
        $deflang = $langarray["en"];
    }
 
    $tpl_index->assign('langarray',$langarray);
    $tpl_index->assign('deflangid',$deflangid);
    $tpl_index->assign('deflang',$deflang);

$sessiontimeout = 3600;

if (isset($_SESSION['valid_memberid'])) {
    $memberid = $_SESSION['valid_memberid'];
    $memberaccount = $_SESSION['valid_memberacc'];

    $sqlsmember = "select memberName,uniformNo from member where memberid=?";
    $paramsmem = array($memberid);
    $stmtsmember = sqlsrv_query($conn, $sqlsmember, $paramsmem);
    if (sqlsrv_fetch($stmtsmember)) {
        $memberName = sqlsrv_get_field($stmtsmember, 0);
        $uniformNo = sqlsrv_get_field($stmtsmember, 1);
    }

    $tpl_index->assign('memberName',$memberName);

    $thistime = time();
    $sqlexploration = "SELECT starttime from memberlog where memberid=? and endtime is NULL";
    $paramsexploration = array($memberid);
    $stmtexploration = sqlsrv_query($conn, $sqlexploration, $paramsexploration);
    if (sqlsrv_fetch($stmtexploration)) {
        $logstarttime = sqlsrv_get_field($stmtexploration, 0);
    }

    if ($logstarttime) {
        $logintime = $logstarttime->getTimestamp();

        if (($thistime - $logintime) > $sessiontimeout) {
            header("Location: login.php?logout=1");
        } else {
            $sqlupuserlog = "update memberlog set starttime=getdate(),endtime=NULL where memberid=?";
            $paramsUpdate = array($memberid);
            $stmtupuserlog = sqlsrv_query($conn, $sqlupuserlog, $paramsUpdate);
        }
    } else {
        header("Location: login.php?logout=1");
    }
}

        $sqluser = "select socialid,socialName,socialLink,socialIcon,disabled from social where disabled = '0' order by sort ";
        $stmtuser = sqlsrv_query($conn, $sqluser, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
        $user_rows = sqlsrv_num_rows($stmtuser);
        for ($i=0; $i<$user_rows; $i++) {
            $row = sqlsrv_fetch_array($stmtuser, SQLSRV_FETCH_ASSOC);
            $socialid = $row['socialid'];
            $socialidlist[$i] = $row['socialid'];
            $socialNamelist[$i] = $row['socialName'];
            $socialLinklist[$i] = $row['socialLink'];
            $socialIconlist[$i] = $row['socialIcon'];

        }

        $tpl_index->assign('socialidlist',$socialidlist);
        $tpl_index->assign('socialNamelist',$socialNamelist);
        $tpl_index->assign('socialLinklist',$socialLinklist);
        $tpl_index->assign('socialIconlist',$socialIconlist);

    if (isset($_POST['setlang'])) {
        $_SESSION['valid_lang'] = $_POST['lang'];
        //header("Location: index.php");
    }

?>
