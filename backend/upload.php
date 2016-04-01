<?php

	//Load mysql connect for GeoPic database.
	//Reference: $mysqli

	require_once('common.php');
	require_once('class.upload.php');

	$conn->select_db("geopic");

	$uploadDir = 'images/';
	//$fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
	//move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . "/" . time() . "." . $fileExt);

		print_r($_POST);

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['locality']) && isset($_POST['categories'])) {
			if(isset($_FILES['file']) && filesize($_FILES['file']['tmp_name']) > 0){ //file array is set and filesize > 0
				if(getimagesize($_FILES['file']['tmp_name']) != FALSE) { //check if file is an image
					$newQry = sprintf("INSERT INTO geopic (locality) VALUES ('%s')", addslashes($_POST['locality']));
					$conn->query($newQry);
					print_r($conn->error);
					if($conn->affected_rows > 0){
						$lastID = $conn->insert_id; //Get the ID of the last insert


						$fileName = $lastID . "_" . time();
						$fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
						//Generate new filepath for uploaded image.
						//	Format:
						//		"./images/[geopic_ID]_[time()].[extension]"
						//
						//	Example:
						//		"./upload/1_1448383830.jpg"
						$newFilePath = $uploadDir . $fileName . "." . $fileExt;
						$thumbName = $uploadDir . $fileName . "_thumb." . $fileExt;
						$resizedImage = new upload($_FILES['file']);

						if($resizedImage->uploaded) {
							$resizedImage->file_new_name_body = $fileName;
							$resizedImage->process($uploadDir);

							$resizedImage->file_new_name_body = $fileName . "_thumb";
							$resizedImage->image_resize = true;
							$resizedImage->image_ratio_crop = true;
							
							$exif = exif_read_data($_FILES['file']["tmp_name"]);
							if(!empty($exif['Orientation'])) { 
							  switch($exif['Orientation']) {
							    case 8:
							      echo 2;
							      $resizedImage->image_rotate = 270;
							      break;
							    case 3:
							      echo 3;
							      $resizedImage->image_rotate = 180;
							      break;
							    case 6:
							      echo 4;
							      $resizedImage->image_rotate = 90;
							      break;
							  }
							}

							$resizedImage->image_x = 500;
							$resizedImage->image_y = 500;
							//$resizedImage->image_ratio_y = true;
							
							$resizedImage->process($uploadDir);

							if($resizedImage->processed) {
								$resizedImage->clean();
							}
						}

						//move_uploaded_file($_FILES['file']['tmp_name'], $newFilePath);


						//Update appropriate user's photo path
						$updateQry = sprintf("UPDATE geopic SET filename = '%s', thumbname = '%s' WHERE geopic_id ='%d'", addslashes($newFilePath), addslashes($thumbName), $lastID);
						$conn->query($updateQry);

						$categories = explode(",", $_POST['categories']);
						print_r($categories);
						foreach($categories as $value) {
							$newQry = sprintf("INSERT INTO junc_categories_geopic (categories_FK, geopic_FK) VALUES ('%s', '%s')", $value, $lastID);
							$conn->query($newQry);
							echo($conn->error);
						}
					} else { //Insert failed
					}
				} else { //Invalid File Type
				}
			} else { //no photo
			}
		}
	}
	//}


?>