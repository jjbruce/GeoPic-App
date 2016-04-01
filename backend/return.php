<?php

	require_once('common.php');
	$conn->select_db("geopic");
	//This program will return a json or jsonp string string 
	if(isset($_GET['geolocation'])) {

		$idQry = sprintf("SELECT categories_FK, geopic_FK from junc_categories_geopic");
		$idResult = $conn->query($idQry);

		$idData = array();


		while($row = $idResult->fetch_array()) {
			if(isset($idData[$row['geopic_FK']])){
				$idData[$row['geopic_FK']] .= ",";
				if($row['categories_FK'] == 1) {
					//$idData[$row['geopic_FK']] .= "<a href=\"#\" onclick=\"window.location.href='#results;getResults(1);\">Food</a>";
					$idData[$row['geopic_FK']] .= "Food";
				} else if($row['categories_FK'] == 2) {
					$idData[$row['geopic_FK']] .= "Sports";
				} else if($row['categories_FK'] == 3) {
					$idData[$row['geopic_FK']] .= "Nature";
				} else if($row['categories_FK'] == 4) {
					$idData[$row['geopic_FK']] .= "Travel";
				} else if($row['categories_FK'] == 5) {
					$idData[$row['geopic_FK']] .= "Humour";
				} else if($row['categories_FK'] == 6) {
					$idData[$row['geopic_FK']] .= "Pets";
				}
			} else {
				$idData[$row['geopic_FK']] = "";
				if($row['categories_FK'] == 1) {
					$idData[$row['geopic_FK']] .= "Food";
				} else if($row['categories_FK'] == 2) {
					$idData[$row['geopic_FK']] .= "Sports";
				} else if($row['categories_FK'] == 3) {
					$idData[$row['geopic_FK']] .= "Nature";
				} else if($row['categories_FK'] == 4) {
					$idData[$row['geopic_FK']] .= "Travel";
				} else if($row['categories_FK'] == 5) {
					$idData[$row['geopic_FK']] .= "Humour";
				} else if($row['categories_FK'] == 6) {
					$idData[$row['geopic_FK']] .= "Pets";
				}
			
			}
		}

		$geoQry = sprintf("SELECT * FROM geopic");
		$geoResult = $conn->query($geoQry);

		$geoData = array();
		$inc = 0;

		while($row = $geoResult->fetch_array()) {
			if(isset($idData[$row['geopic_ID']])) {
				$geoData[$inc]['categories'] = $idData[$row['geopic_ID']];
			}
			$geoData[$inc]['locality'] = $row['locality'];
			$geoData[$inc]['filename'] = $row['filename'];
			$geoData[$inc]['thumbname'] = $row['thumbname'];
			$inc++;
		}

		$jsonData = json_encode($geoData);


		$geoResult->close();

		if(isset($_GET['callback'])) {
			    header('Content-Type: text/javascript; charset=utf8');
			    header('Access-Control-Allow-Origin: *');
			    header('Access-Control-Max-Age: 3628800');
			    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

			    echo $_GET['callback'] . '('. $jsonData . ');';


		} else {
			header('Content-Type: application/json; charset=utf8');

			echo $jsonData;
		}
	}

	if(isset($_GET['category'])) {

		$idQry = sprintf("SELECT categories_FK, geopic_FK from junc_categories_geopic");
		$idResult = $conn->query($idQry);

		$idData = array();


		while($row = $idResult->fetch_array()) {
			if(isset($idData[$row['geopic_FK']])){
				$idData[$row['geopic_FK']] .= ",";
				if($row['categories_FK'] == 1) {
					//$idData[$row['geopic_FK']] .= "<a href=\"#\" onclick=\"window.location.href='#results;getResults(1);\">Food</a>";
					$idData[$row['geopic_FK']] .= "Food";
				} else if($row['categories_FK'] == 2) {
					$idData[$row['geopic_FK']] .= "Sports";
				} else if($row['categories_FK'] == 3) {
					$idData[$row['geopic_FK']] .= "Nature";
				} else if($row['categories_FK'] == 4) {
					$idData[$row['geopic_FK']] .= "Travel";
				} else if($row['categories_FK'] == 5) {
					$idData[$row['geopic_FK']] .= "Humour";
				} else if($row['categories_FK'] == 6) {
					$idData[$row['geopic_FK']] .= "Pets";
				}
			} else {
				$idData[$row['geopic_FK']] = "";
				if($row['categories_FK'] == 1) {
					$idData[$row['geopic_FK']] .= "Food";
				} else if($row['categories_FK'] == 2) {
					$idData[$row['geopic_FK']] .= "Sports";
				} else if($row['categories_FK'] == 3) {
					$idData[$row['geopic_FK']] .= "Nature";
				} else if($row['categories_FK'] == 4) {
					$idData[$row['geopic_FK']] .= "Travel";
				} else if($row['categories_FK'] == 5) {
					$idData[$row['geopic_FK']] .= "Humour";
				} else if($row['categories_FK'] == 6) {
					$idData[$row['geopic_FK']] .= "Pets";
				}
			}
			

		}

		$newQry = sprintf("	SELECT geopic.filename, geopic.locality, geopic.thumbname, geopic.geopic_ID
							FROM geopic 
							INNER JOIN junc_categories_geopic 
								ON geopic.geopic_ID = junc_categories_geopic.geopic_FK 
							WHERE junc_categories_geopic.categories_FK = '" . $_GET['category'] . "'");
		$result = $conn->query($newQry);
		$data = array();
		$dataInc = 0;
		while($row = $result->fetch_array()) {
			if(isset($idData[$row['geopic_ID']])) {
				$data[$dataInc]['categories'] = $idData[$row['geopic_ID']];
			}
			$data[$dataInc]['filename'] = $row['filename'];
			$data[$dataInc]['locality'] = $row['locality'];
			$data[$dataInc]['thumbname'] = $row['thumbname'];
			$dataInc++;
		}

		$jsonData = json_encode($data);


		$result->close();

		if(isset($_GET['callback'])) {
			    header('Content-Type: text/javascript; charset=utf8');
			    header('Access-Control-Allow-Origin: *');
			    header('Access-Control-Max-Age: 3628800');
			    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

			    echo $_GET['callback'] . '('. $jsonData . ');';


		} else {
			header('Content-Type: application/json; charset=utf8');

			echo $jsonData;
		}
	} else {

	}


?>