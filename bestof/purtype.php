<?php
include_once("security.php");       // check to see if the user is logged in
include_once('cx.php');         // connection string
include_once('MySQL.01.php');   // datalayer
include_once('Form.01.php');    // form
include_once('CreateInsertMySQL.01.php');   // create the insert statement
include_once("Framework.01.php");   // generic functions

$sql = new MySQL($_cx['host'],$_cx['user'],$_cx['password'],$_cx['db']);    // setup datalayer
$_key = "seq";
$_table = "pur_type";


$form = new Form($sql);                                             // form class
$fw = new FrameWork();

if (isset($_POST['Submit']))
{
    $save = new InsertStatementMYSQL($fw);     // new insert on duplicate update statement
    $save->Ignore(array('Submit'));      // values to ignore
    $save->Table($_table);                     // table name -> top
    $save->Key($_key);                         // primmary key -> top
    $insert = $save->CreateInsert($_POST);               // get the insert statement
    $nbr = $form->Save($insert);
    if ($form->Error['HasError'])
        die($form->Error['Message']);
    header("location:purtype.php?edit=".$nbr);
}

if (isset($_REQUEST['edit']))
{
    $page_values = $form->Edit("select * from ".$_table." where seq = ".$_REQUEST['edit']." limit 1;");
     
     
    if ($form->Error['HasError'])
    {
        die($form->Error['Message']);
    }    
}


$sql->Open();       // connect
if($sql->HasErr)
    die("Unable to open SQL [00] :: ".$sql->ErrMsg);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
         <style> 
        #content, #data
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
        <title></title>
    </head>
    <body>
        <?php include_once("main.php"); ?>
        <a href="purtype.php">Add</a><br>
        <div id="content">
            <form name="login" action="" method="post">
                <input type="hidden" name="seq" id="seq" value="<?php echo isset($page_values['seq']) ? $page_values['seq'] : '' ; ?>" /><br>
                
                <label>Name</label><input type="text" name="name" id="name" value="<?php echo isset($page_values['name']) ? $page_values['name'] : '' ; ?>" /><br>
                <label>Icon</label><input type="text" name="icon" id="icon" value="<?php echo isset($page_values['icon']) ? $page_values['icon'] : '' ; ?>" /><br>
                <label>Cert</label><input type="text" name="cert" id="cert" value="<?php echo isset($page_values['cert']) ? $page_values['cert'] : '' ; ?>" /><br>
                <label>&nbsp;</label><input type="submit" name="Submit" id="Submit" />
            </form>
        </div>
        <div id="data">
            <table>
                <tr>
                    <th>Edit</th>
                    <th>Order</th>
                    <th>Name</th>
                </tr>
                <?php
                    $query = "select * from pur_type order by name";
                    $sql->Query($query);
                    if($sql->HasErr)
                        die("Unable to query [01] :: ".$sql->ErrMsg);
                    
                    while ($row = $sql->NextRecord())
                    {
                        echo "<tr>";
                        echo '<td><a href="?edit='.$row['seq'].'">Edit</a></td>';
                        echo "<td>".$row['name']."</td>";
                    
                        echo "</tr>";
                        
                    }
                ?>
            </table>
        </div>
    </body>
</html>
