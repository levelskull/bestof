<?php
session_start();        // session is needed for the $_SESSION var
// check for the incoming var
if (isset($_POST['user']) and $_POST['user'] != '' and isset($_POST['pass']) and $_POST['pass'] != '')
{
    include_once('cx.php');         // connection string
    include_once('MySQL.01.php');   // datalayer
    $sql = new MySQL($_cx['host'],$_cx['user'],$_cx['password'],$_cx['db']);    // setup datalayer
    $sql->Open();       // connect
    if($sql->HasErr)
        die("Unable to open SQL [00] :: ".$sql->ErrMsg);
    
    $query = "select count(1) as cnt from user where user = '".$_POST['user']."' and pass='".md5($_POST['pass'])."' limit 1";
    $sql->Query($query);    // query
    if ($sql->HasErr) die("Error in SQL : ".$query." :: <br>".$sql->ErrMsg);
    $row = $sql->NextRecord();  // get the record set
    
    if ($row['cnt'] == 1)   // if count is one ; we are good
    {
        $_SESSION['user'] = $_POST['user']; // set the session security var
        header("location:main.php");        // send to the main screen
    }
}
?>
<html>
    <head>
        <title>Best Of Login</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style> 
        #content
        {
            width: 550px;
            margin-left: auto;
            margin-right: auto;
            
        }
        label
            {
                width:125px;
                display:block;
                text-align: right;
                float: left;
                padding-right: 10px;
            }
        </style>
    </head>
    <body>
        <div id="content">
            <form name="login" action="" method="post">
                <label>User:</label><input type="text" name="user" id="user" value="<?php echo isset($_POST['user']) ? $_POST['user'] : '' ; ?>" /><br>
                <label>Password:</label><input type="password" name="pass" id="pass" /><br>
                <label>&nbsp;</label><input type="submit" name="Submit" id="Submit" />
            </form>
        </div>
    </body>
</html>
