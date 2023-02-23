<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');
$dbconn = paliolite();

$f_pin = $_POST['fpin'];
if ($f_pin == 'null') {
  $f_pin = '';
}
$method = $_POST['method'];
$status = $_POST['status'];
$price = $_POST['price'];
$reg_type = $_POST['reg_type'];
$date = $_POST['date'];

$transaction_id = md5($f_pin . $date);

session_start();

// FOR KTA AND KIS BATCH

if(isset($_POST['no_batch'])){

  $ref_id = '0'.$_POST['no_batch'];

}else{

  // FOR NOT BATCH

  // ONLY FOR TKT NO (UID) NOT WITH 0, OTHER TKT WITH 0 (LIKE KTA AND KIS)

  if ($reg_type == 6){

    // $ref_id = $_SESSION['ref_id'];
    $ref_id = null;

  }else{

    $ref_id = '0'.$_SESSION['ref_id'];

  }

}

$sql = "
INSERT INTO `REGISTRATION_PAYMENT` (
    PAYMENT_ID, F_PIN, REG_TYPE, PRICE, 
    METHOD, STATUS, DATE, REF_ID
  ) 
  VALUES 
    (
      '$transaction_id', '$f_pin', $reg_type, 
      $price, '$method', $status, $date, '$ref_id'
    )
";

echo $sql;
$query = $dbconn->prepare($sql);
$query->execute();
$query->close();

echo 'Success';


