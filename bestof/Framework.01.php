<?php
Class FrameWork
{
    
    public $TableView = "";                 // view of the data
    public $Source_Tables = array("");      // Source of the data
    public $Me = "";                        // The page we are in
    public $Edit_Page = "";                 // Page used to edit the data
    
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

		
	/// Acceps the query and passes back the Multi Input Select options.
    /// $query :: query ex: "select seq as value ,name as title from helpdesk.appequip where type = 'App'"
    /// $val :: value to check for selected
    /// $sql :: datalayer
    /// Example:
    /// $fw = new FrameWork();                      
    /// echo $fw->InputSelect("select seq as value ,name as title from helpdesk.appequip where type = 'App' order by name",$row['app'],$dataset); 
    public function MultiInputSelect($query,$val,$sql,$id) 
    {
      
        $rtn = "";
        $sql->Query($query);
		$cnt = 0;
        while ($app = $sql->NextRecord())
        {
            $rtn .= '<option id = "'.($id.$cnt).'"  value="'.urlencode(trim($app['value'])).'" '.( in_array($app['value'],$val) ? 'selected' : '' ).' >'.trim($app['title']).'</option>';

			$cnt;

        }        
        return $rtn;                                                      
        
    }
	

    
    /// Converts the Date to MySQL Date
    /// $date :: date to convert
    /// Returns :: MySQL Date yyyy/mm/dd
    public function DateToMySQL($date)
    {
        $rtn = '0000-00-00';
        if ($date != '' and trim($date) != '')
        {
            $d =  date_create_from_format('m/d/Y',$date);
            return date_format($d,'Y-m-d');
        }
        return $rtn;
    }
    
    /// Converts a mysql date to a date
    /// $date :: date to convert
    /// Returns :: Date formated mm/dd/yyyy
    public function MySQLToDate($date)
    {
        $rtn = '';        
        if ($date != '0000-00-00' and trim($date) != '')
        {	
			date_default_timezone_set ( 'America/New_York' );
            $d =  date_create_from_format('Y-m-d',$date);
            $rtn = date_format($d,'m/d/Y');
        }
        return $rtn;
        
    }
	
	
}
 
?> 