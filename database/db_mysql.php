<?php
	class PHPReportsDBI {
		function db_connect($oArray) {
			$oCon = mysqli_connect($oArray[2], $oArray[0], $oArray[1]);
			if(!$oCon)
				die("could not connect");
			if(!is_null($oArray[3]))
				PHPReportsDBI::db_select_db($oArray[3]);
			return $oCon;
		}

		function db_select_db($sDatabase) {
			mysqli_select_db($sDatabase);
		}

		function db_query($oCon,$sSQL) {
			$oStmt = mysqli_query($oCon,$sSQL);
			return $oStmt;
		}

		function db_colnum($oStmt) {
			return mysqli_num_fields($oStmt);
		}

		function db_columnName($oStmt,$iPos) {
			require_once('MySQLi-Function/mysqli_field_name.php');
			return mysqli_field_name($oStmt,$iPos-1);
		}
		
		function db_columnType($oStmt,$iPos) {
			require_once('MySQLi-Function/mysqli_field_type.php');
			return mysqli_field_type($oStmt,$iPos-1);
		}

		function db_fetch($oStmt) {
			return mysqli_fetch_array($oStmt);
		}

		function db_free($oStmt) {
			return mysqli_free_result($oStmt);
		}

		function db_disconnect($oCon) {
			return mysqli_close($oCon);
		}
	}	
?>
