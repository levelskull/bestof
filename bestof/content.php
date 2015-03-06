<?php
include_once("../bestof/security.php");       // check to see if the user is logged in
include_once('../bestof/cx.php');         // connection string
include_once('../bestof/MySQL.01.php');   // datalayer
include_once('../bestof/Form.01.php');    // form
include_once('../bestof/CreateInsertMySQL.01.php');   // create the insert statement
include_once("../bestof/Framework.01.php");   // generic functions

$sql = new MySQL($_cx['host'],$_cx['user'],$_cx['password'],$_cx['db']);    // setup datalayer
$_key = "seq";
$_table = "content";


$form = new Form($sql);                                             // form class
$fw = new FrameWork();

if (isset($_POST['Submit']) or isset($_POST['AddPost']))
{
    $edit = '';
    if (isset($_POST['AddPost']))
    {
        $_table = "post_on";
        $edit = $_POST["content_seq"];
        $_POST['showdte'] = $_POST['month'].$_POST['day'];
        $_COOKIE['show_year'] = $_POST['yr'];
    }
    
    if (isset($_POST['Submit']))
    {
        $edit = $_POST["seq"] != '' ? $_POST["seq"] : '';
    }
    
    $save = new InsertStatementMYSQL($fw);     // new insert on duplicate update statement
    $save->Ignore(array('Submit','AddPost','month','day'));      // values to ignore
    //$save->Date(array('release_date')); // dates
    $save->Table($_table);                     // table name -> top
    $save->Key($_key);                         // primmary key -> top
    $insert = $save->CreateInsert($_POST);               // get the insert statement
    //die($insert);
    $nbr = $form->Save($insert);
    if ($form->Error['HasError'])
        die($form->Error['Message']);
    
    header("location:content.php?edit=".($edit != '' ? $edit : $nbr));
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
            width: 900px;
            margin-left: auto;
            margin-right: auto;
            
        }
        label
            {
                width:200px;
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
        <a href="content_list.php">List</a><br>
        <div id="content">
            <form name="content" action="" method="post">
                <input type="hidden" name="seq" id="seq" value="<?php echo isset($page_values['seq']) ? $page_values['seq'] : '' ; ?>" /><br>
                <label>Post Type</label> <select name="post_type" id="post_type">
                  <?php echo $fw->InputSelect("SELECT name as title, seq as value  FROM post_type;",isset($page_values['post_type']) ? $page_values['post_type'] : '',$sql); ?>
            </select><br>
                <label>Nav Tag</label><select name="nav_tag" id="nav_tag">
                  <?php echo $fw->InputSelect("SELECT name as title, seq as value  FROM nav_tag;",isset($page_values['nav_tag']) ? $page_values['nav_tag'] : '',$sql); ?>
            </select><br>
            
            <!--<label>Year (YYYY)</label><input type="text" name="yr" id="yr" value="<?php echo isset($page_values['yr']) ? $page_values['yr'] : '' ; ?>" /><br>
            <label>Post Date (MMDD)</label><input type="text" name="showdte" id="showdte" value="<?php echo isset($page_values['showdte']) ? $page_values['showdte'] : '' ; ?>" /><br>
            -->
                <label>Product Link</label><br>
                <label>&nbsp;</label><textarea name="prodlink" id="prodlink" rows="10" cols="50"><?php echo isset($page_values['prodlink']) ? stripslashes(htmlentities($page_values['prodlink'])) : '' ; ?></textarea> <br>
                <label>Title</label><input type="text" name="title" id="title" size='85'  value="<?php echo isset($page_values['title']) ? $page_values['title'] : '' ; ?>" /><br>
                <label>Author</label><input type="text" name="author" id="author" size='85'  value="<?php echo isset($page_values['author']) ? $page_values['author'] : '' ; ?>" /><br>
                <label>Release Date</label><input type="text" name="release_date" id="release_date" value="<?php echo isset($page_values['release_date']) ? ($page_values['release_date']) : '' ; ?>" /><br>
                <label>Week Spent #1</label><input type="text" name="wk_spent" id="wk_spent" value="<?php echo isset($page_values['wk_spent']) ? $page_values['wk_spent'] : '' ; ?>" /><br>
                <label>Content</label><br>
                <label>&nbsp;</label><textarea id="content" name="content" rows="10" cols="50"><?php echo isset($page_values['content']) ? $page_values['content'] : '' ; ?></textarea>
                    
                <br>
                <label>&nbsp;</label><input type="submit" name="Submit" id="Submit" />
            </form>

            
        </div>
<?php if (isset($page_values['seq'])) { ?>            
         
 <br><br>       <div id="data">
            <form name="post_on" action="" method="post">
                <input type="hidden" name="seq" id="seq" value="<?php echo isset($post_on['seq']) ? $post_on['seq'] : '' ; ?>" />
                <input type="hidden" name="content_seq" id="content_seq" value="<?php echo isset($post_on['content_seq']) ? $post_on['content_seq'] : $page_values['seq'] ; ?>" /><br>
            <label>Year (YYYY)</label><input type="text" name="yr" id="yr" value="<?php echo isset($post_on['yr']) ? $post_on['yr'] : isset($_COOKIE['show_year']) ? $_COOKIE['show_year'] : '' ; ?>" /><br>
            <label>Post Date (MMDD)</label>
            
            <select id="month" name="month">
               
                <?php
                    for($i=1;$i<=12;$i++)
                    {
                        echo '<option value="'.str_pad($i,2,'0',STR_PAD_LEFT).'">'. date('F',strtotime('2015-'.str_pad($i,2,'0',STR_PAD_LEFT).'-01')).' ('.str_pad($i,2,'0',STR_PAD_LEFT).')</option>';
                    }
                ?>
                
                
            </select>
            
            
            <select id="day" name="day">
               
                <?php
                    for($i=1;$i<=31;$i++)
                    {
                        echo '<option value="'.str_pad($i,2,'0',STR_PAD_LEFT).'">'.str_pad($i,2,'0',STR_PAD_LEFT).'</option>';
                    }
                ?>
                
                
            </select>
            
            
            <!--<input type="text" name="showdte" id="showdte" value="<?php //echo isset($post_on['showdte']) ? $post_on['showdte'] : '' ; ?>" />-->
            <br>
            <label>Link</label>
            <textarea id="info_link" name="info_link" rows="10" cols="50"><?php echo isset($post_on['info_link']) ? $post_on['info_link'] : '' ; ?></textarea><br>
            <label>Event</label>
            <textarea id="note" name="note" rows="10" cols="50"><?php echo isset($post_on['note']) ? $post_on['note'] : '' ; ?></textarea>
            <br>
            <label>&nbsp;</label><input type="submit" name="AddPost" id="AddPost" />
            </form>

            <table>
                <tr>
                    <th>Edit</th>
                    <th>Year</th>
                    <th>Show Date</th>
                    <th>Event</th>
                </tr>
                <?php
                    $query = "select * from post_on where content_seq = ".$page_values['seq']." order by yr, showdte";
                    $sql->Query($query);
                    if($sql->HasErr)
                        die("Unable to query [01] :: ".$sql->ErrMsg);
                    
                    while ($row = $sql->NextRecord())
                    {
                        echo "<tr>";
                        //echo '<td><a href="content.php?edit='.$row['seq'].'">Edit</a></td>';
                        echo "<td>".$row['yr']."</td>";       
                        echo "<td>".$row['showdte']."</td>";  
                        echo "<td>".$row['note']."</td>"; 
                        echo "</tr>";
                        
                    }
                ?>
            </table>
        </div>
        <br><br>
         <div id="data">
                <a href="add_pur.php?content_seq=<?php echo $page_values['seq'];?>">Add Purchase</a>
            <table>
                <tr>
                    <th>Edit</th>
                    <th>Purchase Type</th>
                    
                </tr>
                <?php
                    $query = "select seq, pur_type from purchase where content_seq = ".$page_values['seq']." order by pur_type";
                    $sql->Query($query);
                    if($sql->HasErr)
                        die("Unable to query [01] :: ".$sql->ErrMsg);
                    
                    while ($row = $sql->NextRecord())
                    {
                        echo "<tr>";
                        echo '<td><a href="content.php?edit='.$row['seq'].'">Edit</a></td>';
                        echo "<td>".$row['pur_type']."</td>";                  
                        echo "</tr>";
                        
                    }
                ?>
            </table>
        </div>
<?php } ?>        
    </body>
</html>
