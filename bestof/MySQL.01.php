<?php
// Datalayer for php
Class MySQL
{
    
    private $_cx = "";                  // connection string
    private $_host = "";                // host
    private $_user = "";                // user
    private $_pass = "";                // password
    public $ErrMsg = "";                // error message
    public $HasErr = false;             // has errors
    private $_rs = "";                  // record set
	public $_defaultdb = "";			// default database
	public $debug = false;				// debug mode ; tracks the query statements and time
	

    /// Pass the host, user and password to the routine.
    /// $host :: MySQL host or IP address
    /// $user :: User
    /// $pass :: Password    
	/// $defaultdb :: Default database
    public function MySQL($host,$user,$pass,$defaultdb)
    {        
	
		$this->_host = $host;           // set the host
		$this->_user = $user;           // set the user
		$this->_pass = $pass;           // set the password
		$this->_defaultdb = $defaultdb;	// default database
    }
    
    /// Open the MySQL Connection
    /// Returns :: True/False
    public function Open()
    {
		$this->ResetError();
		$old_err_rpt = error_reporting();	// get the orginal error reporting number
		error_reporting(0);			// suppress the error; let the class handle it
        $rtn = true;
       	$this->_cx = mysql_connect($this->_host,$this->_user,$this->_pass) ;    // open 
        if (!$this->_cx)           // if can't open return false
        {
	    //die("DataLayer:: (Open) Could not connect : ".mysql_error());
            $rtn = $this->Error("DataLayer:: (Open) Could not connect : ".mysql_error());			
        } 
    	else
    	{
    		mysql_select_db($this->_defaultdb);			// select the database
    	}
		error_reporting($old_err_rpt);	// set back to orginal reporting	
        return $rtn;
    }
    
    /// Closes the connecton
    public function Close()
    {        
		$this->ResetError();
	    mysql_close($this->_cx);        
    }
    
    /// Query the table
    /// $query :: the query statement to execute
    public function Query($query)
    {
		$this->ResetError();
		$old_err_rpt = error_reporting();	// get the orginal error reporting number
		error_reporting(0);			// suppress the error; let the class handle it
	
		$time_start = microtime(true);						// track thd start time
		$rtn = 0;
	
			$this->_rs = mysql_query($query,$this->_cx);
			
			if (!$this->_rs)
			{
			//	die("DataLayer:: (Query) ".mysql_error()." : ".$query);
				$this->Error("DataLayer:: (Query) ".mysql_error()." : ".$query);   
			} 
			$time_end = microtime(true);						// track the end time
		if ($this->debug)
		{
			$this->Log($query,$time_start,$time_end);
		}
		error_reporting($old_err_rpt);	// set back to orginal reporting
		return $rtn;
	
    }
    
    /// Gets the next record in the record set
    /// Return :: array
    public function NextRecord()
    {
        $row = mysql_fetch_array($this->_rs, MYSQLI_ASSOC);	
        return $row;
    }
    
	/// Moves the array pointer to a specified record
	/// $rec : int of the record to move the poiter too
	public function GotoRec($rec)
	{
		mysql_data_seek($this->_rs,$rec);
	}
	
    /// Executes a query statement that does not return a record set
    /// $query :: query statement
    /// Returns :: Last id (auto increment)
    public function NonQuery($query)
    {
		$this->ResetError();
		$old_err_rpt = error_reporting();	// get the orginal error reporting number
		error_reporting(0);					// suppress the error; let the class handle it
		$rtn['id'] = '';							// retun last id
        $rs = mysql_query($query);
        if (!$rs)
        {     
            $this->Error("DataLayer:: (NonQuery) ".mysql_error());   
			//echo "DataLayer:: (NonQuery) ".mysql_error();
        }
        else
        {
            //$rtn = mysql_affected_rows();    
			$query = "select last_insert_id() as id";
			$this->Query($query);
			$rtn = $this->NextRecord();						
        }
		error_reporting($old_err_rpt);	// set back to orginal reporting
        return $rtn['id'];				// retun the last id

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
	
	/// Reset Error message routine
    /// Sets the default public HasErr and ErrMsg.
    private function ResetError()
    {        
        $this->HasErr = false;
        $this->ErrMsg = '';
    }
	
	// Log the query and time took to a a file in the scrtips dir
	// $query :: query to track
	// $time_start :: start time in microseconds
	// $time_end :: end time in microseconds
	private function Log($query,$time_start,$time_end)
	{
		$filename = "mysql.log";
		$f = fopen($filename,'a');
		fwrite($f,($time_end - $time_start)." : ".$query."\n\r");
		fclose($f);
	}
}

?>