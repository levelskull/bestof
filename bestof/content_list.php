<?php
include_once("security.php");       // check to see if the user is logged in
include_once('cx.php');         // connection string
include_once('MySQL.01.php');   // datalayer
include_once('Form.01.php');    // form
include_once('CreateInsertMySQL.01.php');   // create the insert statement
include_once("Framework.01.php");   // generic functions

$sql = new MySQL($_cx['host'],$_cx['user'],$_cx['password'],$_cx['db']);    // setup datalayer

$form = new Form($sql);                                             // form class
$fw = new FrameWork();

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
        <a href="content.php">Add</a><br>
        
        <div id="data">
            <table>
                <tr>
                    <th>Edit</th>
                    
                    <th>Year</th>
                    <th>Show</th>
                    <th>Post Type</th>
                    <th>Title</th>
                </tr>
                <?php
                    $query = "select 
                                    content.seq, 
                                    post_on.showdte, 
                                    post_on.yr, 
                                    post_type.name as posttype, 
                                    title 
                                from 
                                    content 
                                    left join 
                                    post_type on post_type.seq = content.post_type 
                                    left join post_on on post_on.content_seq = content.seq 
                               order by content.seq desc ";
                    $sql->Query($query);
                    if($sql->HasErr)
                        die("Unable to query [01] :: ".$sql->ErrMsg);
                    
                    while ($row = $sql->NextRecord())
                    {
                        echo "<tr>";
                        echo '<td><a href="content.php?edit='.$row['seq'].'">Edit</a></td>';
                        echo "<td>".$row['yr']."</td>";
                        echo "<td>".$row['showdte']."</td>";
                        echo "<td>".$row['posttype']."</td>";
                        echo "<td>".$row['title']."</td>";
                        echo "</tr>";
                        
                    }
                ?>
            </table>
        </div>
    </body>
</html>
