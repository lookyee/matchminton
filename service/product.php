<?php
	date_default_timezone_set("Asia/Bangkok");
    header('Content-type: text/html; charset=utf-8');
    require_once 'PDO.php';

    $db_host = 'localhost:8889';
    $db_name = 'matchminton';
    $db_user = 'root';
    $db_pass = '1';

    $conn = new PDO("mysql:host=$db_host; dbname=$db_name", $db_user, $db_pass);
    $conn->exec("SET CHARACTER SET utf8");

    if(isset($_POST['action']) && $_POST['action'] == 'create'){
        $sql = "INSERT INTO `product`(`product_name`, `price`, `description`, `quantity`, `brand_id`, `type`)VALUES ('".$_POST['product_name']."','".$_POST['price']."','".$_POST['description']."','".$_POST['quantity']."','".$_POST['brand_id']."','".$_POST['type']."')";
        $rs = getpdo($conn,$sql);
        if($rs){
        	$lastid = $conn->lastInsertId();
        	
        	if(isset($_FILES['files'])){
        		$total = count($_FILES['files']['name']);

				for( $i=0 ; $i < $total ; $i++ ) {
					$tmpFilePath = $_FILES['files']['tmp_name'][$i];
					if ($tmpFilePath != ""){
					    $newFilePath = "../uploadFiles/" . $_FILES['files']['name'][$i];
					    if(move_uploaded_file($tmpFilePath, $newFilePath)) {
					    	$sql = "INSERT INTO `product_image`(`fk_product_id`, `path`) VALUES ('".$lastid."','".$newFilePath."')";
	        				$rs = getpdo($conn,$sql);
						}
					}
				}
        	}

        	if(isset($_POST['racket']) && $_POST['racket'] == 1){
        		$sql = "INSERT INTO `racket_detail`(`grip_size`, `balance`, `tension`, `weight`, `flex`,`level`,`fk_product_id`) VALUES ('".$_POST['grip_size']."','".$_POST['balance']."','".$_POST['tension']."','".$_POST['weight']."','".$_POST['flex']."','".$_POST['level']."','".$lastid."')";
        		echo $sql;
        		$rs = getpdo($conn,$sql);
        		if($rs){
					$res = array("code" => 200, "result" => $rs);
		        	echo json_encode($res);
		            return ;
				}
        	}
			else{
				$res = array("code" => 200, "result" => $rs);
	        	echo json_encode($res);
	            return ;
			}
        }
    }

    $result = array("message" => "Error someting");
    $res = array("code" => 401, "result" => $result);
    echo json_encode($res);
?>