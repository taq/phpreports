<?php
	// MUST be in the include path
	require_once("PHPReportsUtil.php");
	require_once("PHPReportOutputObject.php");

	/*********************************************************************************
	*																											*
	*	PHPReports TXT plugin - renders the page into a TXT file								*
	*	You need to erase the file if you'll need it no more.									*
	*	If $this->_bJump is true, it will print the report on the screen.					*
	*																											*
	*********************************************************************************/
	class PHPReportOutput extends PHPReportOutputObject {
		function proc_cols($cols){
			$rst = "";
			foreach($cols as $col){
				if(strlen($col)<1)
					continue;
				$col = "<C".$col;

				// valor
				preg_match("/>.*</",$col,$val);
				$val = substr($val[0],1,strlen($val[0])-2);

				// procura tamanho
				$fixed_width = -1;
				preg_match("/FW=\"[0-9]{1,6}\"/",$col,$width);
				if(sizeof($width)>0){
					preg_match("/[0-9]{1,6}/",$width[0],$width);
					$fixed_width = $width[0];
				}

				// procura alinhamento
				$align_side = "";
				preg_match("/AL=\"[A-Z]{1,10}\"/",$col,$align);
				if(sizeof($align)>0){
					preg_match("/\"[A-Z]{1,10}\"/",$align[0],$align);
					$align_side = preg_replace("/(\"|')/","",$align[0]);
				}

				if($fixed_width>0){
					$nval		= str_pad($val,$fixed_width," ",$align_side=="RIGHT"?STR_PAD_LEFT:STR_PAD_RIGHT);
					$col		= str_replace(">$val<",">$nval<",$col);
				}
				$rst .= $col;
			}
			return $rst;
		}

		function proc_rows($filepath){
			$file = fopen($filepath,"r");
			$rst  = "";

			while($line=fgets($file)){
				if(!preg_match("/^<R[ >]/",$line)){
					$rst .= $line;
					continue;
				}

				preg_match_all("/<\/?R[ A-Z0-9=\"]*>/i",$line,$rows);
				foreach($rows as $row){
					foreach($row as $match)
						$line = str_replace($match,"",$line);
				}
				$cols = preg_split("/<C/",$line);
				$cols = $this->proc_cols($cols);
				$line = $rows[0][0].$cols.$rows[0][1]."\n";
				$rst .= $line;
			}
			fclose($file);

			// overwrite the file
			$file = fopen($filepath,"w");
			fwrite($file,$rst);
			fclose($file);
		}

		function run() {
			$sPath  = getPHPReportsFilePath();
			$sTmp	  = getPHPReportsTmpPath();
			$sXSLT  = $sPath."/output/txtfc/txtfc.xsl";
			$sXML	  = $this->getInput();

			$this->proc_rows($sXML);

			// create a new filename if its empty
			if(is_null($this->getOutput())){
				$sOut  = tempnam($sTmp,"txt");
				unlink($sOut);
				$sOut .= ".txt";
			}else
				$sOut  = $this->getOutput();

			// XSLT processor				
			$oProcFactory = new XSLTProcessorFactory();
			$oProc = $oProcFactory->get();
			$oProc->setXML($sXML);
			$oProc->setXSLT($sXSLT);
			$oProc->setOutput($sOut);
			$oProc->setParms(array("body"=>($this->getBody()?"true":"false")));
			$sRst = $oProc->run();
		
			/*	
				Read file to pre-processing, replacing the __formfeed__ indicator for a 
				chr(12), and write it again
			*/	
			$fHand = fopen($sOut,"rb");
			$sText = fread($fHand,filesize($sOut));
			fclose($fHand);
			
			if(strpos($sText,"__formfeed__")){
				$sText = str_replace("__formfeed__",chr(12),$sText);
				$sText = str_replace(chr(160)," ",$sText);
				$fHand = fopen($sOut,"wb");
				fwrite($fHand,$sText);
				fclose($fHand); 
			}	

			// if needs to jump to show the file, show it
			if($this->isJumping())
				print $sText;
				
			// check if needs to clean the XML data file	
			if($this->isCleaning())	
				unlink($sXML);	
		}
	}
?>
