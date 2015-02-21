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
	    public function StateInputSelect($query,$val,$sql) 
    {
      
        $rtn = "";
        
        $sql->Query($query);
        while ($app = $sql->NextRecord())
        {
            $rtn .= '<option value="'.$app['value'].'" '.( $app['title'] == $val ? 'selected' : '' ).'>'.$app['title'].'</option>';

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
	
	//Returns a preselected list of options and values from query
	//ex.  query list of dlr numbers and return the value and label for each option  used on dashboard to build multidealer select box
	//$query :: SELECT nbr as value, name as title FROM dealers.dealer_mast where nbr in (".$row['dealer_mast_nbr_multi'].")
	//$sql :: datalayer
	    public function DlrInputSelect($query,$sql) 
    {
      
        $rtn = "";
		$sql->Query($query);
		$cnt = 0;
        while ($app = $sql->NextRecord())
        {
            $rtn .= '<option value="'.urlencode(trim($app['value'])).'" >'.trim($app['title']).'</option>';

			$cnt;

        }        
        return $rtn;                                                      
        
    }

	//Returns Select for Admin page links 
	//ex.  query available list of user pages and allows admin to enter them with proper credentials
	//$query :: select page,name from
	//(SELECT * FROM csi.page_group where page_id in (1,2,8,9,10,11,12,13,212,213,215,216,217,221,222,238,239,240,241)) as user_pages
	//left join
	//(select * from csi.page) as pages on user_pages.page_id=pages.id where page_group_name_id=2 
	//order by page_id asc
	//$sql :: datalayer
	    public function PageSelect($query,$sql) 
    {
      
        $rtn = "";
		$sql->Query($query);
		$cnt = 0;
        while ($app = $sql->NextRecord())
        {
            $rtn .= '<option value="'.$app['page'].'" >'.$app['name'].'</option>';

			$cnt;

        }        
        return $rtn;                                                      
        
    }    
    /// Returns a title for a value
    /// $query :: query to look for value
    /// $sql :: datalayer
    /// Returns title that matches the value
    public function ValToTitle($query,$sql)
    {
        $rtn = "";
        $sql->Query($query);
        $val = $sql->NextRecord();
        return $val[0];
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
	
	/// Get the Segment
	/// $class :: the class to find
	/// $segsql :: instance of the sql class
	/// Returns :: Name of the segment
	public function GetSegment($class,$segsql)
	{
		$segsql->Query("select name from vin.class where class = '$class'");
		$row = $segsql->NextRecord();
		return $row['name'];
		
	}
   	///Takes Numeric Year Month and translates to Spelled out Month Year
	///$date::numeric date ex. 201202
	///$rtn::Date Year ex. February 2012
    public function YearMonToMonYear($date)
    {	$date.='01';
		date_default_timezone_set ( 'America/New_York' );
        $d =  date_create_from_format('Ymd',$date);
		$rtn = date_format($d,'M Y');
		return $rtn;
        
    }
	
	///Takes Numeric Year Month and translates to Spelled out Month Year
	///$date::numeric date ex. 201202
	///$rtn::Date Year ex. February 2012
    public function YearMonToMonYearFull($date)
    {	$date.='01';
		date_default_timezone_set ( 'America/New_York' );
        $d =  date_create_from_format('Ymd',$date);
		$rtn = date_format($d,'F Y');
		return $rtn;
        
    }
	
	///Return the name of the month
	/// $month : month (Jan)
	// $pad (bool) : padding
	/* Example : 
		MonthToNum(1,true) will return 01
		MonthToNum(1,false) will return 1
	*/
	
	function MonthToNum($month,$pad)
	{
		$month = strlen(trim($month)) >= 3 ? substr(trim(strtoupper($month)),0,3) : '';
		$month_names = array("JAN" => 1, 
							"FEB" => 2, 
							"MAR" => 3, 
							"APR" => 4, 
							"MAY" => 5, 
							"JUN" => 6, 
							"JUL" => 7, 
							"AUG" => 8, 
							"SEP" => 9, 
							"OCT" => 10, 
							"NOV" => 11, 
							"DEC" => 12);
		
		return isset($month_names[$month]) ? (($pad and $month_names[$month] < 10 ) ? '0' : '').$month_names[$month] : '';
	}
	
}
 
?>