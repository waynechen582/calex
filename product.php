<?php
    include ('main.php');
    
    $webpath = "upload";
    $today = date("Y-m-d");
          
    //程式內容
    if (isset($_POST['getitem'])) {
        $priceid = $_POST['priceid'];
        $sqlproduct = "select p.t_prodName,p.s_prodName,p.e_prodName,pr.t_priceName,pr.s_priceName,pr.e_priceName,pr.price from product p, price pr where pr.prodid=p.prodid and pr.priceid=?";
        $params = array($priceid);
        $stmtproduct = sqlsrv_query($conn, $sqlproduct, $params);
        if (sqlsrv_fetch($stmtproduct)) {
            $t_prodName = sqlsrv_get_field($stmtproduct, 0);
            $s_prodName = sqlsrv_get_field($stmtproduct, 1);
            $e_prodName = sqlsrv_get_field($stmtproduct, 2);
            $t_priceName = sqlsrv_get_field($stmtproduct, 3);
            $s_priceName = sqlsrv_get_field($stmtproduct, 4);
            $e_priceName = sqlsrv_get_field($stmtproduct, 5);
            $price = sqlsrv_get_field($stmtproduct, 6);
        }

        $json = array();
        if ($cklang == "zh-tw") {        
            $json["prodName"] = $t_prodName;
            $json["priceName"] = $t_priceName;
        } elseif ($cklang == "zh-cn") {
            $json["prodName"] = $s_prodName;
            $json["priceName"] = $s_priceName;
        } elseif ($cklang == "en") {
            $json["prodName"] = $e_prodName;
            $json["priceName"] = $e_priceName;
        }
        $json["price"] = "NT$" . number_format($price,0,'.',',');
        if ($price == "0") {
            $json['pricing'] = "free";
        } else {
            $json['pricing'] = "price";
        }

        echo json_encode($json, JSON_UNESCAPED_UNICODE);
    } elseif (isset($_POST['freetry'])) {
        $priceid = $_POST['priceid'];
        $sqlprod = "select prodid,term from price where priceid=?";
        $params = array($priceid);
        $stmtprod = sqlsrv_query($conn, $sqlprod, $params);
        if (sqlsrv_fetch($stmtprod)) {
            $prodid = sqlsrv_get_field($stmtprod, 0);
            $term = sqlsrv_get_field($stmtprod, 1);
        }

        $sqlsub = "select subscriptionid from subscription where memberid=? and prodid=?";
        $stmtsub = sqlsrv_query($conn, $sqlsub, array($memberid, $prodid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
        $sub_rows = sqlsrv_num_rows($stmtsub);

        $json = array();
        if ($sub_rows > 0) {
            if ($cklang == "zh-tw") { 
                $json["msg"] = "此產品項目已試用過或在試用中，請前往會員專區查看。";
            } elseif ($cklang == "zh-cn") {
                $json["msg"] = "此产品项目已试用过或在试用中，请前往会员专区查看。";
            } elseif ($cklang == "en") {
                $json["msg"] = "This product item has been tried or is in trial, please go to the member area to check.";
            }
        } else {
            $sdate = date("Y-m-d");
            $edate = date("Y-m-d", mktime(0, 0, 0, date("m")+$term, date("d"), date("Y")));
            $sqlinsub = "insert into subscription (memberid,prodid,priceid,sdate,edate,status,newdate) values (?,?,?,?,?,'f',getdate())";
            $paramsInsert = array($memberid, $prodid, $priceid, $sdate, $edate);
            $stmtinsub = sqlsrv_query($conn, $sqlinsub, $paramsInsert);
            if ($cklang == "zh-tw") { 
                $json["msg"] = "產品項目免費試用已加入會員專區，請前往查看並進入試用。";
            } elseif ($cklang == "zh-cn") {
                $json["msg"] = "产品项目免费试用已加入会员专区，请前往查看并进入试用。";
            } elseif ($cklang == "en") {
                $json["msg"] = "The free trial of product items has been added to the member area, please go to view and enter the trial.";
            }
        }

        echo json_encode($json, JSON_UNESCAPED_UNICODE);
    } elseif (isset($_POST['payment'])) {
        $priceid = $_POST['priceid'];
        $sqlprod = "select prodid,term,price from price where priceid=?";
        $params = array($priceid);
        $stmtprod = sqlsrv_query($conn, $sqlprod, $params);
        if (sqlsrv_fetch($stmtprod)) {
            $prodid = sqlsrv_get_field($stmtprod, 0);
            $term = sqlsrv_get_field($stmtprod, 1);
            $price = sqlsrv_get_field($stmtprod, 2);
        }

        $sqlsub = "select subscriptionid from subscription where memberid=? and prodid=?";
        $stmtsub = sqlsrv_query($conn, $sqlsub, array($memberid, $prodid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
        $sub_rows = sqlsrv_num_rows($stmtsub);

        $json = array();
        if ($sub_rows > 0) {
            if ($cklang == "zh-tw") { 
                $json["msg"] = "此產品項目已在訂購中，請前往會員專區查看。";
            } elseif ($cklang == "zh-cn") {
                $json["msg"] = "此产品项目已在订购中，请前往会员专区查看。";
            } elseif ($cklang == "en") {
                $json["msg"] = "This product item is already in order, please go to the member area to check.";
            }
            $json['goECPay'] = 0;
        } else {
            $sdate = date("Y-m-d");
            $edate = date("Y-m-d", mktime(0, 0, 0, date("m")+$term, date("d"), date("Y")));
            $sqlinsub = "insert into subscription (memberid,prodid,priceid,status,newdate) values (?,?,?,'p',getdate()); SELECT SCOPE_IDENTITY()";
            $paramsInsert = array($memberid, $prodid, $priceid);
            $stmtinsub = sqlsrv_query($conn, $sqlinsub, $paramsInsert);
            sqlsrv_next_result($stmtinsub); 
            sqlsrv_fetch($stmtinsub); 
            $subscriptionid = sqlsrv_get_field($stmtinsub, 0);
            $sqlorderno = "select orderno from payment where newdate >= getdate()";
            $stmtorderno = sqlsrv_query($conn, $sqlorderno);
            if (sqlsrv_fetch($stmtorderno)) {
                $orderno = sqlsrv_get_field($stmtorderno, 0);
                $neworderno = $orderno + 1;
            } else {
                $neworderno = date("Ymd")."01";
            }
            $sqlinpay = "insert into payment (orderno,memberid,subscriptionid,price,paymentStatus,newdate) values (?,?,?,?,'0',getdate())";
            $paramsInsert = array($neworderno, $memberid, $subscriptionid, $price);
            $stmtinpay = sqlsrv_query($conn, $sqlinpay, $paramsInsert);
            $json['goECPay'] = 1;
            $json['orderno'] = $neworderno;
        }

        echo json_encode($json, JSON_UNESCAPED_UNICODE);
    } else {
    if (isset($_GET['prodid'])) {
        $prodid = $_GET['prodid'];
        $sqlproduct = "select t_prodName,s_prodName,e_prodName,t_prodContent,s_prodContent,e_prodContent,t_contentImage,s_contentImage,e_contentImage from product where prodid=?";
        $params = array($prodid);
        $stmtproduct = sqlsrv_query($conn, $sqlproduct, $params);
        if (sqlsrv_fetch($stmtproduct)) {
            $t_prodName = sqlsrv_get_field($stmtproduct, 0);
            $s_prodName = sqlsrv_get_field($stmtproduct, 1);
            $e_prodName = sqlsrv_get_field($stmtproduct, 2);
            $t_prodContent = sqlsrv_get_field($stmtproduct, 3);
            $s_prodContent = sqlsrv_get_field($stmtproduct, 4);
            $e_prodContent = sqlsrv_get_field($stmtproduct, 5);
            $t_contentImage = sqlsrv_get_field($stmtproduct, 6);
            $s_contentImage = sqlsrv_get_field($stmtproduct, 7);
            $e_contentImage = sqlsrv_get_field($stmtproduct, 8);

            if ($cklang == "zh-tw") {        
                $tpl_index->assign('prodName', $t_prodName);
                $tpl_index->assign('prodContent', nl2br($t_prodContent));
                $tpl_index->assign('contentImage', preg_replace('/\.\.\//', '', $t_contentImage)."?".rand(1000,9999));
            } elseif ($cklang == "zh-cn") {
                $tpl_index->assign('prodName', $s_prodName);
                $tpl_index->assign('prodContent', nl2br($s_prodContent));
                $tpl_index->assign('contentImage', preg_replace('/\.\.\//', '', $s_contentImage)."?".rand(1000,9999));
            } elseif ($cklang == "en") {
                $tpl_index->assign('prodName',  $e_prodName);
                $tpl_index->assign('prodContent', nl2br($e_prodContent));
                $tpl_index->assign('contentImage', preg_replace('/\.\.\//', '', $e_contentImage)."?".rand(1000,9999));
            }
        } else {
            header("Location: index.php"); 
        }
        

        $sqlproduct = "select prodid,t_prodName,s_prodName,e_prodName from product where disabled='0' order by sort";
        $stmtproduct = sqlsrv_query($conn, $sqlproduct, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
        $product_rows = sqlsrv_num_rows($stmtproduct);
        for ($i=0; $i<$product_rows; $i++) {
            $row = sqlsrv_fetch_array($stmtproduct, SQLSRV_FETCH_ASSOC);
            $prodidlist[$i] = $row['prodid'];
            $t_prodNamelist[$i] = $row['t_prodName'];
            $s_prodNamelist[$i] = $row['s_prodName'];
            $e_prodNamelist[$i] = $row['e_prodName'];
        }
        $tpl_index->assign('prodidlist',$prodidlist);
        if ($cklang == "zh-tw") {        
            $tpl_index->assign('prodNamelist',$t_prodNamelist);
        } elseif ($cklang == "zh-cn") {
            $tpl_index->assign('prodNamelist',$s_prodNamelist);
        } elseif ($cklang == "en") {
            $tpl_index->assign('prodNamelist',$e_prodNamelist);
        }

        $sqlfeature = "select featureid,t_featureName,s_featureName,e_featureName,t_featureContent,s_featureContent,e_featureContent,featureIcon from feature where disabled='0' and prodid=? order by sort";
        $stmtfeature = sqlsrv_query($conn, $sqlfeature, array($prodid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
        $feature_rows = sqlsrv_num_rows($stmtfeature);
        for ($i=0; $i<$feature_rows; $i++) {
            $row = sqlsrv_fetch_array($stmtfeature, SQLSRV_FETCH_ASSOC);
            $featureidlist[$i] = $row['featureid'];
            $t_featureNamelist[$i] = $row['t_featureName'];
            $s_featureNamelist[$i] = $row['s_featureName'];
            $e_featureNamelist[$i] = $row['e_featureName'];
            $t_featureContentlist[$i] = nl2br($row['t_featureContent']);
            $s_featureContentlist[$i] = nl2br($row['s_featureContent']);
            $e_featureContentlist[$i] = nl2br($row['e_featureContent']);
            $featureIconlist[$i] = $row['featureIcon'];
        }
        $tpl_index->assign('featureidlist', $featureidlist);
        $tpl_index->assign('featureIconlist', $featureIconlist);
        if ($cklang == "zh-tw") {        
            $tpl_index->assign('featureNamelist', $t_featureNamelist);
            $tpl_index->assign('featureContentlist', $t_featureContentlist);
        } elseif ($cklang == "zh-cn") {
            $tpl_index->assign('featureNamelist', $s_featureNamelist);
            $tpl_index->assign('featureContentlist', $s_featureContentlist);
        } elseif ($cklang == "en") {
            $tpl_index->assign('featureNamelist', $e_featureNamelist);
            $tpl_index->assign('featureContentlist', $e_featureContentlist);
        }

        $sqlprice = "select priceid,t_priceName,s_priceName,e_priceName,t_priceContent,s_priceContent,e_priceContent,t_unit,s_unit,e_unit,price from price where prodid=? order by sort";
        $stmtprice = sqlsrv_query($conn, $sqlprice, array($prodid), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
        $price_rows = sqlsrv_num_rows($stmtprice);
        for ($i=0; $i<$price_rows; $i++) {
            $row = sqlsrv_fetch_array($stmtprice, SQLSRV_FETCH_ASSOC);
            $priceidlist[$i] = $row['priceid'];
            $t_priceNamelist[$i] = $row['t_priceName'];
            $s_priceNamelist[$i] = $row['s_priceName'];
            $e_priceNamelist[$i] = $row['e_priceName'];
            $t_priceContentlist[$i] = nl2br($row['t_priceContent']);
            $s_priceContentlist[$i] = nl2br($row['s_priceContent']);
            $e_priceContentlist[$i] = nl2br($row['e_priceContent']);
            $t_unitlist[$i] = $row['t_unit'];
            $s_unitlist[$i] = $row['s_unit'];
            $e_unitlist[$i] = $row['e_unit'];
            $pricelist[$i] = number_format($row['price'],0,'.',',');
        }
        $tpl_index->assign('priceidlist', $priceidlist);
        $tpl_index->assign('pricelist', $pricelist);
        if ($cklang == "zh-tw") {        
            $tpl_index->assign('priceNamelist', $t_priceNamelist);
            $tpl_index->assign('priceContentlist', $t_priceContentlist);
            $tpl_index->assign('unitlist', $t_unitlist);
        } elseif ($cklang == "zh-cn") {
            $tpl_index->assign('priceNamelist', $s_priceNamelist);
            $tpl_index->assign('priceContentlist', $s_priceContentlist);
            $tpl_index->assign('unitlist', $s_unitlist);
        } elseif ($cklang == "en") {
            $tpl_index->assign('priceNamelist', $e_priceNamelist);
            $tpl_index->assign('priceContentlist', $e_priceContentlist);
            $tpl_index->assign('unitlist', $e_unitlist);
        }
    } else {
        $sqlproduct = "select top 1 prodid from product where disabled='0' order by sort";
        $stmtproduct = sqlsrv_query($conn, $sqlproduct);
        if (sqlsrv_fetch($stmtproduct)) {
            $prodid = sqlsrv_get_field($stmtproduct, 0);
            header("Location: product.php?prodid=$prodid");
        } else {
            header("Location: index.php");
        }
            
    }

        $tpl_index->assign('product',1);
        $tpl_index->display('main.html');
    
    }


?>
