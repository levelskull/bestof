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
        
    // remove special chars from user - this is a sql injection fix
    $query = "select count(1) as cnt from user where user = '".preg_replace('/[^A-Za-z0-9\-]/', '', $_POST['user'])."' and pass='".md5($_POST['pass'])."' limit 1";
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
            /* See : http://www.smashingmagazine.com/2013/08/09/absolute-horizontal-vertical-centering-css/ */
            width: 350px;
            
            border:solid thin #000;
            padding: 20px;
            margin: auto;
            position: absolute;
            top: 0; left: 0; bottom: 0; right: 0;
            display: table; /* FireFox/IE8/Chrome */
            height: auto; /* IE9/IE10 */
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
            Best Of
            <form name="login" action="" method="post">
                <label>User:</label><input type="text" name="user" id="user" value="<?php echo isset($_POST['user']) ? $_POST['user'] : '' ; ?>" /><br>
                <label>Password:</label><input type="password" name="pass" id="pass" />
                <?php echo isset($_POST['user']) ? '<br><br><label>&nbsp;</label>Incorrect user or password.' : ''; ?>
                <br><br>
                <label>&nbsp;</label><input type="submit" name="Submit" id="Submit" />
            </form>
        </div>
    </body>
</html>
