<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET ID SHOP

session_start();
$f_pin = $_POST['f_pin'];
$l_pin = $_POST['shop_id'];

// DELETE BLOCK

$query = "DELETE FROM BLOCK_USER WHERE F_PIN = '".$f_pin."' AND L_PIN = '".$l_pin."'";

if (mysqli_query($dbconn, $query)){
    ?>

    <script type="text/javascript">
        
        var l_pin = '<?= $l_pin ?>';
        var f_pin = '<?= $f_pin ?>';

        if (window.Android){

            window.Android.blockUser(l_pin, false);

        }else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.blockUser) {

            window.webkit.messageHandlers.blockUser.postMessage({
                param1: l_pin,
                param2: false
            });

        }

        window.location.href = "http://108.136.138.242/gaspol_web/pages/blocked_list?f_pin="+f_pin;

    </script>

    <?php 
    // header("Location: ../pages/blocked_list?f_pin=".$f_pin."");
}else{
    echo("ERROR: Data gagal diubah. $sql. " . mysqli_error($dbconn));
}

?>