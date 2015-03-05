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
        
        <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="http://cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>
        <link href='http://cdn.datatables.net/1.10.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css' />
        <script>
            $(document).ready(function(){
        $('#myTable').DataTable();
        });
        </script>
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
        <a href="content.php">Add</a><br>
        
        <div id="data">
            <table id="myTable"class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Edit</th>
                    
                    <th>Year</th>
                    <th>Show</th>
                    <th>Post Type</th>
                    <th>Title</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    $query = "select 
                                    content.seq, 
                                    group_concat(distinct post_on.showdte order by post_on.showdte desc) as showdte, 
                                    post_on.yr, 
                                    post_type.name as posttype, 
                                    title 
                                from 
                                    content 
                                    left join 
                                    post_type on post_type.seq = content.post_type 
                                    left join post_on on post_on.content_seq = content.seq 
                               group by content.seq
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
                        echo "<td>".stripslashes($row['title'])."</td>";
                        echo "</tr>";
                        
                    }
                ?></tbody>
            </table>
        </div>
    </body>
</html>
