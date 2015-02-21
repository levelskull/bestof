<?php
Class InsertStatementMySQL
{
	private $_dates = array(''); 	// dates to convert
	private $_nums = array(''); 	// numbers to convert
	private $_check = array(''); 	// check boxes to convert
	private $_ignore = array(''); 	// ignore values
	private $_table;		// table name
	public $ErrMsg = "";            // error message
	public $HasErr = false;         // has errors
	private $_fw;			// framework to use for formating dates and numbers
	
	public function InsertStatementMySQL($framework)
	{
		$this->_fw = $framework;	// set the framework
	}

	/// Sets the dates to be formated correctly
	/// $arrDates :: array of dates
	public function Date($arrDates)
	{
		$this->_dates = $arrDates;			// dates to be conveted
	}
	
	/// Sets the numbers to be formated correctly
	/// $arrNums :: array of number fields	
	public function Numeric($arrNums)
	{
		$this->_nums = $arrNums;			// numbers to be converted
	}
	
	/// Sets the Checkboxes to be false if not present
	/// $arrCheck :: array of checkboxes
	public function CheckBoxes($arrCheck)
	{
		$this->_check = $arrCheck;			// check boxes to be applied
	}

	/// Sets the fields to be ignored
	/// $arrDates :: array of fields to be ignored
	public function Ignore($arrIgnore)
	{
		$this->_ignore = $arrIgnore;		// fields to be ignored
	}

	/// Sets the table to save too
	/// $strTable :: table name
	public function Table($strTable)
	{
		$this->_table = $strTable;			// set the table to save
	}
	
	/// Sets the unique key of the table
	/// $strKey :: key name
	public function Key($strKey)
	{
		$this->_key = $strKey;			// set the unique id of the table
	}
	
	/// Creates the insert statement
	/// $data :: the data in an array ($_REQUEST, $_POST, $_GET)
	public function CreateInsert($data)
	{
		$fld = ""; 							// field list
		$fld_val = "";						// values
		$ondup = "";						// on dup values
		while ($val = each($data))
		{
			// skip the ignored values
			if (in_array($val[0],$this->_ignore))
			{
				continue;
			}
			
			// add commas
			$fld .= $fld == "" ? "" : ",";
			$fld_val .= $fld_val == "" ? "" : ",";
			$ondup .= $ondup == "" ? "" : ",";
			
			$fld .= $val[0];				// add the field to the list
			
			if (in_array($val[0],$this->_dates))	// convert the dates
			{
				$fld_val .= "'".$this->_fw->DateToMySQL($data[$val[0]])."'";				// insert
				$ondup .= $val[0]." = '".$this->_fw->DateToMySQL($data[$val[0]])."'";	// ond uplicate
			}			
			elseif (in_array($val[0],$this->_nums))
			{
				$fld_val .= "'".tonum($data[$val[0]])."'";				// insert
				$ondup .= $val[0]." = '".tonum($data[$val[0]])."'";		// ond uplicate
			}
			else
			{
				$fld_val .= "'".(addslashes($data[$val[0]]))."'";			// insert
				$ondup .= $val[0]." = '".(addslashes($data[$val[0]]))."'";		// ond uplicate
			}
			
			
		}
		
		while ($val = each($this->_check))						// check boxes
		{			
			if (!isset($data[$val[0]]) and ($val[0] != "" or $val[0] != 0))							// check box was not passed to save
			{
				// add commas
				$fld .= $fld == "" ? "" : ",";
				$fld_val .= $fld_val == "" ? "" : ",";
				$ondup .= $ondup == "" ? "" : ",";
				
				$fld .= $val[0];								// add the field to the list
				$fld_val .= "'N'";								// insert
				$ondup .= $val[0]." = '".($data[$val[0]])."'";		// ond uplicate				
			}
		}
		
		$insert = "insert into ".$this->_table." (".$fld.") values (".$fld_val.") on duplicate key update ".$ondup;		
		return $insert;

	}
	
	/// Error message routine
	/// Sets the public HasErr and ErrMsg.
	    /// $msg :: Error message
	private function Error($msg)
	{
	    $this->HasErr = true;
	    $this->ErrMsg = $msg;
	    return false;	
	}
		
}
?>























