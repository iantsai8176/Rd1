<?php
header("content-type: text/html; charset=utf-8");

ignore_user_abort();//關掉瀏覽器，PHP腳本也可以繼續執行.
set_time_limit(0);// 限制腳本執行時間

while(true){
    // 1. 初始設定
    $ch = curl_init();
    $fp = fopen("test.txt", "w+"); // W以寫模式打開文件
    $cookie = dirname(__FILE__)."/".'cookie.txt';
    $url = "http://www.228365365.com/sports.php"; // bet365網址
    //早盤
    $url2 = "http://www.228365365.com/app/member/FT_future/body_var.php?uid=test00&rtype=r&langx=zh-cn&mtype=3&page_no=0&league_id=&hot_game=";
    //今日賽事
    $url3 = "http://www.228365365.com/app/member/FT_browse/body_var.php?uid=test00&rtype=r&langx=zh-cn&mtype=3&page_no=0&league_id=&hot_game=undefined=";
    //抓取cookie紀錄
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_COOKIEJAR,$cookie);//取得並儲存cookie
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //不直接輸出到頁面，以文件格式獲取內容
    curl_setopt($ch, CURLOPT_HEADER,false);//不輸出header資訊
    $pageContent = curl_exec($ch);
    //送出cookie資訊取得資料
    curl_setopt($ch, CURLOPT_URL,$url2);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);//送出cookie
    curl_setopt($ch, CURLOPT_HEADER,false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $pageContent1 = curl_exec($ch);
    //篩選預選取資料
    $contents = substr($pageContent1, 78);
    $db = new PDO("mysql:host=localhost;dbname=Rdone;port=3306", "root", "");
    $db->exec("set names utf8");
    $delete = $db->query("TRUNCATE TABLE raceData"); // 清空資料表

    $arr = explode("parent.GameFT", $contents);

    for ($i=1;$i<=count($arr);$i++) {
        $result = explode(",",$arr[$i]);

        if ($result[7] == "'H'") {
            $allBall = $result[8].$result[9].$result[10];
            $halfBall = $result[25].$result[24].$result[26];
        } else {
            $allBall = $result[9].$result[8].$result[10];
            $halfBall = $result[25].$result[24].$result[26];
        }

        $time = $result[1];
        $raceName = $result[2];
        $raceStatus = $result[5].$result[6];
        $singleWin = $result[15].$result[16].$result[17];
        $allSize = $result[11].$result[14].$result[12].$result[13];
        $singleDouble = $result[18].$result[20].$result[19].$result[21];
        $backSingleWin = $result[31].$result[32].$result[33];
        $halfSize = $result[27].$result[30].$result[28].$result[29];

        $db = new PDO("mysql:host=localhost;dbname=Rdone;port=3306", "root", "");
        $db->exec("set names utf8");
        $insert = $db->query("INSERT INTO `raceData` (`time`,`raceName`, `raceStatus`, `singleWin`, `allBall`,
                                                    `allSize`, `singDouble`, `backSingWin`, `halfBall`, `halfSize`) VALUES
                                                    ($time,$raceName,$raceStatus,$singleWin,$allBall,$allSize,$singleDouble,
                                                    $backSingleWin,$halfBall,$halfSize)");
    }

    sleep(60);
}
