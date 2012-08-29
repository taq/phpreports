<?php
	class PHPReportsDBI {
		function db_connect($oArray) {
			if(!is_null($oArray[3]))
				$oCon = PHPReportsDBI::db_select_db($oArray[3]);
			return $oCon;
		}

		function db_select_db($sDatabase) {
			$connect = new SQLite3($sDatabase);
			return $connect;
		}

		function db_query($oCon,$sSQL) {
			$oStmt = $oCon->query($sSQL);
			return $oStmt;
		}

		function db_colnum($oStmt) {
			return $oStmt->numColumns();
		}

		function db_columnName($oStmt,$iPos) {
			return $oStmt->columnName($iPos-1);
		}
		
		function db_columnType($oStmt,$iPos) {
         // return $oStmt->columnType($iPos-1);
         return "NUMERIC";
		}

		function db_fetch($oStmt) {
			return $oStmt->fetchArray();
		}

		function db_free(&$oStmt) {
			$oStmt = NULL;
			return true;
		}

		function db_disconnect($oCon) {
			return $oCon->close();
		}
	}	
?>

