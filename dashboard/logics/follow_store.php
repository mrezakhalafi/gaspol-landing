<?php 

    include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

    $dbconn = paliolite();

    $store_code = $_POST['store_code'];
    $flag_follow = $_POST['flag_follow'];
    $last_update = $_POST['last_update'];
    $f_pin = $_POST['f_pin'];

    try {
        if($flag_follow == '1'){
            // $query = $dbconn->prepare("INSERT INTO SHOP_FOLLOW (F_PIN, STORE_CODE, FOLLOW_DATE) VALUES (?,?,?) ON DUPLICATE KEY UPDATE FOLLOW_DATE = ?");
            // $query->bind_param("ssss", $f_pin, $store_code, $last_update, $last_update);
            // $query->execute();
            // $query->close();

            // $query = $dbconn->prepare("UPDATE SHOP SET TOTAL_FOLLOWER=TOTAL_FOLLOWER+1 WHERE CODE = ?");
            // $query->bind_param("s", $store_code);
            // $query->execute();
            // $query->close();

            $sql = "INSERT INTO FOLLOW_LIST (F_PIN, L_PIN, CREATED_DATE) VALUES ('".$f_pin."','".$store_code."','".date('Y-m-d h:i:s')."')";
            $query = $dbconn->prepare($sql);
            $query->execute();
            $query->close();

            echo "follow ";
        } else {
            $query = $dbconn->prepare("DELETE FROM FOLLOW_LIST WHERE F_PIN = ? AND L_PIN = ?");
            $query->bind_param("ss", $f_pin, $store_code);
            $query->execute();
            $query->close();

            // $query = $dbconn->prepare("UPDATE SHOP SET TOTAL_FOLLOWER=IF(TOTAL_FOLLOWER<=0,0,TOTAL_FOLLOWER-1) WHERE CODE = ?");
            // $query->bind_param("s", $store_code);
            // $query->execute();
            // $query->close();

            echo "unfollow ";
        }

        // echo ' Success ';

    } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
    }
?>