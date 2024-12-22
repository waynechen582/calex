<?php
    include ('main.php');
    include ('ruby_class/page_bar.php');
    
    $webpath = "upload";
    $today = date("Y-m-d");
          
    //程式內容

            //關於松技
        $sqlabout = "select bannerid,t_title,s_title,e_title,t_content,s_content,e_content,t_imagepath,s_imagepath,e_imagepath from banner where category='2'";
        
        $stmtabout = sqlsrv_query($conn, $sqlabout);
        if (sqlsrv_fetch($stmtabout)) {
            $aboutBannerid = sqlsrv_get_field($stmtabout, 0);
            $aboutt_title = sqlsrv_get_field($stmtabout, 1);
            $abouts_title = sqlsrv_get_field($stmtabout, 2);
            $aboute_title = sqlsrv_get_field($stmtabout, 3);
            $aboutt_content = sqlsrv_get_field($stmtabout, 4);
            $abouts_content = sqlsrv_get_field($stmtabout, 5);
            $aboute_content = sqlsrv_get_field($stmtabout, 6);
            $aboutt_imagepath = sqlsrv_get_field($stmtabout, 7);
            $abouts_imagepath = sqlsrv_get_field($stmtabout, 8);
            $aboute_imagepath = sqlsrv_get_field($stmtabout, 9);
    if ($cklang == "zh-tw") {        
        $tpl_index->assign('aboutTitle',$aboutt_title);
        $tpl_index->assign('aboutContent',$aboutt_content);
        $tpl_index->assign('aboutImagepath', preg_replace('/\.\.\//', '', $aboutt_imagepath)."?".rand(1000,9999));
    } elseif ($cklang == "zh-cn") {
        $tpl_index->assign('aboutTitle',$abouts_title);
        $tpl_index->assign('aboutContent',$abouts_content);
        $tpl_index->assign('aboutImagepath',  preg_replace('/\.\.\//', '', $abouts_imagepath)."?".rand(1000,9999));
    } elseif ($cklang == "en") {
        $tpl_index->assign('aboutTitle',$aboute_title);
        $tpl_index->assign('aboutContent',$aboute_content);
        $tpl_index->assign('aboutImagepath',  preg_replace('/\.\.\//', '', $aboute_imagepath)."?".rand(1000,9999));
    }
            $tpl_index->assign('aboutBannerid',$aboutBannerid);

        }
        $tpl_index->assign('aboutCate',$cateArray[2]);

        //產品項目
        $sqlproduct = "select prodid,t_prodName,s_prodName,e_prodName,t_prodIntro,s_prodIntro,e_prodIntro,t_introImage,s_introImage,e_introImage from product where disabled='0' order by sort";
        $stmtproduct = sqlsrv_query($conn, $sqlproduct, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
        $product_rows = sqlsrv_num_rows($stmtproduct);
        for ($i=0; $i<$product_rows; $i++) {
            $row = sqlsrv_fetch_array($stmtproduct, SQLSRV_FETCH_ASSOC);
            $prodidlist[$i] = $row['prodid'];
            $t_prodNamelist[$i] = $row['t_prodName'];
            $s_prodNamelist[$i] = $row['s_prodName'];
            $e_prodNamelist[$i] = $row['e_prodName'];
            $t_prodIntrolist[$i] = nl2br($row['t_prodIntro']);
            $s_prodIntrolist[$i] = nl2br($row['s_prodIntro']);
            $e_prodIntrolist[$i] = nl2br($row['e_prodIntro']);
            $t_introImagelist[$i] = preg_replace('/\.\.\//', '', $row['t_introImage'])."?".rand(1000,9999);
            $s_introImagelist[$i] = preg_replace('/\.\.\//', '', $row['s_introImage'])."?".rand(1000,9999);
            $e_introImagelist[$i] = preg_replace('/\.\.\//', '', $row['e_introImage'])."?".rand(1000,9999);
        }
        $tpl_index->assign('prodidlist',$prodidlist);
        if ($cklang == "zh-tw") {
            $tpl_index->assign('prodNamelist', $t_prodNamelist);
            $tpl_index->assign('prodIntrolist', $t_prodIntrolist);
            $tpl_index->assign('introImagelist', $t_introImagelist);
        } elseif ($cklang == "zh-cn") {
            $tpl_index->assign('prodNamelist', $s_prodNamelist);
            $tpl_index->assign('prodIntrolist', $s_prodIntrolist);
            $tpl_index->assign('introImagelist',  $s_introImagelist);
        } elseif ($cklang == "en") {
            $tpl_index->assign('prodNamelist', $e_prodNamelist);
            $tpl_index->assign('prodIntrolist', $e_prodIntrolist);
            $tpl_index->assign('introImagelist',  $e_introImagelist);
        }


            //客戶見證
        $sqlcustom = "select bannerid,t_title,s_title,e_title,t_content,s_content,e_content,t_imagepath,s_imagepath,e_imagepath from banner where category='3'";
        
        $stmtcustom = sqlsrv_query($conn, $sqlcustom);
        if (sqlsrv_fetch($stmtcustom)) {
            $customBannerid = sqlsrv_get_field($stmtcustom, 0);
            $customt_title = sqlsrv_get_field($stmtcustom, 1);
            $customs_title = sqlsrv_get_field($stmtcustom, 2);
            $custome_title = sqlsrv_get_field($stmtcustom, 3);
            $customt_content = sqlsrv_get_field($stmtcustom, 4);
            $customs_content = sqlsrv_get_field($stmtcustom, 5);
            $custome_content = sqlsrv_get_field($stmtcustom, 6);
            $customt_imagepath = sqlsrv_get_field($stmtcustom, 7);
            $customs_imagepath = sqlsrv_get_field($stmtcustom, 8);
            $custome_imagepath = sqlsrv_get_field($stmtcustom, 9);
    if ($cklang == "zh-tw") {        
        $tpl_index->assign('customTitle',$customt_title);
        $tpl_index->assign('customContent',nl2br($customt_content));
        $tpl_index->assign('customImagepath',  preg_replace('/\.\.\//', '', $customt_imagepath)."?".rand(1000,9999));
    } elseif ($cklang == "zh-cn") {
        $tpl_index->assign('customTitle',$customs_title);
        $tpl_index->assign('customContent',nl2br($customs_content));
        $tpl_index->assign('customImagepath',  preg_replace('/\.\.\//', '', $customs_imagepath)."?".rand(1000,9999));
    } elseif ($cklang == "en") {
        $tpl_index->assign('customTitle',$custome_title);
        $tpl_index->assign('customContent',nl2br($custome_content));
        $tpl_index->assign('customImagepath',  preg_replace('/\.\.\//', '', $custome_imagepath)."?".rand(1000,9999));
    }

            $tpl_index->assign('customBannerid',$customBannerid);

        }
        $tpl_index->assign('customCate',$cateArray[3]);

        //主要客戶
        $sqluser = "select maincustid,custImage from maincust where disabled = '0' order by sort";
        $stmtuser = sqlsrv_query($conn, $sqluser, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
        $user_rows = sqlsrv_num_rows($stmtuser);
        for ($i=0; $i<$user_rows; $i++) {
            $row = sqlsrv_fetch_array($stmtuser, SQLSRV_FETCH_ASSOC);
            $maincustidlist[$i] = $row['maincustid'];
            $custNamelist[$i] = $row['custName'];
            $custImagelist[$i] = preg_replace('/\.\.\//', '', $row['custImage'])."?".rand(1000,9999);

        }

        $tpl_index->assign('maincustidlist',$maincustidlist);
        $tpl_index->assign('custNamelist',$custNamelist);
        $tpl_index->assign('custImagelist',$custImagelist);
        
        
        //banner
        $sqlbanner = "select bannerid,t_title,s_title,e_title,t_content,s_content,e_content,t_imagepath,s_imagepath,e_imagepath,url from banner where category='1' and (forever='1' and sdate<=getdate() or (forever='0' and sdate<=getdate() and edate>DATEADD(day,1,getdate()))) order by sort";
        $stmtbanner = sqlsrv_query($conn, $sqlbanner, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
        $banner_rows = sqlsrv_num_rows($stmtbanner);
        for ($i=0; $i<$banner_rows; $i++) {
            $row = sqlsrv_fetch_array($stmtbanner, SQLSRV_FETCH_ASSOC);
            $banneridlist[$i] = $row['bannerid'];
            $urllist[$i] = $row['url'];
            if ($cklang == "zh-tw") {
                $titlelist[$i] = $row['t_title'];
                $imagepathlist[$i] = preg_replace('/\.\.\//', '', $row['t_imagepath'])."?".rand(1000,9999);
            } elseif ($cklang == "zh-cn") {
                $titlelist[$i] = $row['s_title'];
                $imagepathlist[$i] = preg_replace('/\.\.\//', '', $row['s_imagepath'])."?".rand(1000,9999);
            } elseif ($cklang == "en") {
                $titlelist[$i] = $row['e_title'];
                $imagepathlist[$i] = preg_replace('/\.\.\//', '', $row['e_imagepath'])."?".rand(1000,9999);
            }

        }

        $tpl_index->assign('banneridlist',$banneridlist);
        $tpl_index->assign('urllist',$urllist);
        $tpl_index->assign('titlelist',$titlelist);
        $tpl_index->assign('imagepathlist',$imagepathlist);

        $tpl_index->assign('index',1);
        $tpl_index->display('main.html');