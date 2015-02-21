<?php
/*	Class to save, insert, delete and update records from a form
*/
Class Form
{
	private $sql;						// sql object
	public $Error = array("HasError" => false, "Message" => '');
	
	/// Constructor
	/// $sql : the sql object to use
	public function Form($sql)
	{
		$this->sql = $sql;												// sql object to be used by the class
	}
	
	public function Save($insert)
	{
		$this->ResetError();
		$lstid = 0;
		if ($this->sql->Open())											// open sql
		{
			$lstid = $this->sql->NonQuery($insert);						// insert or update ; get the last insert id
	
			if ($this->sql->HasErr)										// oops : got an error
			{
				die("error");
				$this->SetError("Unable to query table (Save) : ".$insert." : ".$this->sql->ErrMsg);	// show the error
			}
			$this->sql->Close();										// be good and close your connections
		}
		else
		{
			die("error");
			$this->SetError("Unable to open sql (Save) : ".$this->sql->ErrMsg);		// oops : got an error
		}
		return $lstid;
	}
	
	public function Delete($delete)
	{
		$this->ResetError();
		if ($this->sql->Open())											// open sql
		{

			$lstid = $this->sql->NonQuery($delete);						// insert or update ; get the last insert id
			if ($this->sql->HasErr)										// oops : got an error
			{
				$this->SetError("Unable to query table (Delete) : ".$delete." : ".$this->sql->ErrMsg);	// show the error
			}
			$this->sql->Close();										// be good and close your connections
		}
		else
		{
			$this->SetError("Unable to open sql (Delete) : ".$this->sql->ErrMsg);		// oops : got an error
		}
	}
	
	public function Insert($insert)
	{
		
	}
	
	public function Update($query)
	{
	}
	
	/// Gather the recods and return an array of the line to be edited on the form
	/// $query : the query to execute
	/// returns : array
	public function Edit($query)
	{
		
		$this->ResetError();
		$page_values="";
		if ($this->sql->Open())													// open sql
		{
			$this->sql->Query($query);											// query
			if (!$this->sql->HasErr)											// no errors
			{
				$page_values = $this->sql->NextRecord();						// get the record	// get the only record to edit
				$this->sql->Close();											// be good and close the connections
			}
			else
			{
				$this->SetError("Unable to query table (Edit) : ".$query." : ".$this->sql->ErrMsg);	// oops : has error
			}
		}
		else
		{
			$this->SetError("Unable to open sql (Edit) : ".$this->sql->ErrMsg);	// oops : has error
		}
		return $page_values;
	}
	
	
	/// Sets any erros
	/// $msg : error message
	private function SetError($msg)
	{
		$this->Error['HasError'] = true;
		$this->Error['Message'] = $msg;
	}
	
	/// Sets error values to default
	private function ResetError()
	{
		$this->Error['HasError'] = false;
		$this->Error['Message'] = '';
	}
	
	/// Get's the record set in HTML table form
	/// $query :: query to get records
	/// $edit_field :: the unqique record's field
	/// $edit_url :: the url to call
	/// $delete :: True/False ; creates a delete cell using the edit_field value and the edit_url
	public function GetTableData($query,$edit_field,$edit_url,$delete)
	{
		$this->ResetError();
		$rtn = "";
		
		if ($this->sql->Open())
		{
						
			$this->sql->Query($query);
			if (!$this->sql->HasErr)
			{
				while ($row = $this->sql->NextRecord())
				{
					$rtn .= "<tr>";
					foreach($row as $key=>$val)
					{	
						if ($key == $edit_field)
						{
							$rtn .= '<td><a href="'.$edit_url.'?edit='.$val.'">Edit</a></td>';		
						}
						else
						{
							$rtn .= "<td>".$val."</td>";		
						}
					}
					if ($delete)
					{
						$rtn .= '<td><a href="'.$edit_url.'?delete='.$row[$edit_field].'">Delete</a></td>';		
					}

					$rtn .= "</tr>";
				}
			}
			else
			{
				$this->SetError("Unable to query table (GetTableData) : ".$query." : ".$this->sql->ErrMsg);	// oops : has error
			}
		}
		else
		{
			$this->SetError("Unable to open sql (Edit) : ".$this->sql->ErrMsg);	// oops : has error
		}
		return $rtn;
	}
	
	
	/// Get's the record set in checkbox form
	/// $query :: query to get records
	public function GetCheckBoxGroup($query,$grp_name,$linebreak)
	{
		$this->ResetError();
		$rtn = "";
		
		if ($this->sql->Open())
		{
						
			$this->sql->Query($query);
			if (!$this->sql->HasErr)
			{
				while ($row = $this->sql->NextRecord())
				{
					
					$rtn .= '<input type="checkbox" name="'.$grp_name.'" id="'.$grp_name.'" value="'.$row['value'].'" />';
					$rtn .= '<label for="'.$grp_name.'">'.$row['label'].'</label>';
					$rtn .= $linebreak ? "<br>" : "";
				}
			}
			else
			{
				$this->SetError("Unable to query table (GetCheckBoxGroup) : ".$query." : ".$this->sql->ErrMsg);	// oops : has error
			}
		}
		else
		{
			$this->SetError("Unable to open sql (GetCheckBoxGroup) : ".$this->sql->ErrMsg);	// oops : has error
		}
		return $rtn;
	}
        
    /// Acceps the query and passes back the Input Select options.
    /// $query :: query ex: "select seq as value ,name as title from helpdesk.appequip where type = 'App'"
    /// $val :: value to check for selected
    /// $sql :: datalayer
    /// Example:
    /// $fw = new FrameWork();                      
    /// echo $fw->InputSelect("select seq as value ,name as title from helpdesk.appequip where type = 'App' order by name",$row['app'],$dataset); 
    public function InputSelect($query,$val,$sql) 
    {
      
        $rtn = "";
        
        $sql->Query($query);
        while ($app = $sql->NextRecord())
        {
            $rtn .= '<option value="'.$app['value'].'" '.( $app['value'] == $val ? 'selected' : '' ).' >'.$app['title'].'</option>';

        }        
        return $rtn;                                                      
        
    }
	
	
}

?>