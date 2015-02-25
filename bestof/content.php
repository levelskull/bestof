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

if (isset($_POST['Submit']))
{
    $save = new InsertStatementMYSQL($fw);     // new insert on duplicate update statement
    $save->Ignore(array('Submit'));      // values to ignore
    //$save->Date(array('release_date')); // dates
    $save->Table($_table);                     // table name -> top
    $save->Key($_key);                         // primmary key -> top
    $insert = $save->CreateInsert($_POST);               // get the insert statement
    $nbr = $form->Save($insert);
    if ($form->Error['HasError'])
        die($form->Error['Message']);
    header("location:content_list.php");
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
        <a href="content_list.php">List</a><br>
        <div id="content">
            <form name="login" action="" method="post">
                <input type="hidden" name="seq" id="seq" value="<?php echo isset($page_values['seq']) ? $page_values['seq'] : '' ; ?>" /><br>
                <label>Post Type</label> <select name="post_type" id="post_type">
                  <?php echo $fw->InputSelect("SELECT name as title, seq as value  FROM post_type;",isset($page_values['post_type']) ? $page_values['post_type'] : '',$sql); ?>
            </select><br>
                <label>Nav Tag</label><select name="nav_tag" id="nav_tag">
                  <?php echo $fw->InputSelect("SELECT name as title, seq as value  FROM nav_tag;",isset($page_values['nav_tag']) ? $page_values['nav_tag'] : '',$sql); ?>
            </select><br>
            
            <label>Year (YYYY)</label><input type="text" name="yr" id="yr" value="<?php echo isset($page_values['yr']) ? $page_values['yr'] : '' ; ?>" /><br>
            <label>Post Date (MMDD)</label><input type="text" name="showdte" id="showdte" value="<?php echo isset($page_values['showdte']) ? $page_values['showdte'] : '' ; ?>" /><br>
            
                <label>Product Link</label><input type="text" name="prodlink" id="prodlink" size='85'  value="<?php echo isset($page_values['prodlink']) ? stripslashes(htmlentities($page_values['prodlink'])) : '' ; ?>" /><br>
                <label>Title</label><input type="text" name="title" id="title" size='85'  value="<?php echo isset($page_values['title']) ? $page_values['title'] : '' ; ?>" /><br>
                <label>Author</label><input type="text" name="author" id="author" size='85'  value="<?php echo isset($page_values['author']) ? $page_values['author'] : '' ; ?>" /><br>
                <label>Release Date</label><input type="text" name="release_date" id="release_date" value="<?php echo isset($page_values['release_date']) ? ($page_values['release_date']) : '' ; ?>" /><br>
                <label>Week Spent #1</label><input type="text" name="wk_spent" id="wk_spent" value="<?php echo isset($page_values['wk_spent']) ? $page_values['wk_spent'] : '' ; ?>" /><br>
                <label>Content</label><br>
                <textarea id="content" name="content" rows="50"><?php echo isset($page_values['nav_tag']) ? $page_values['nav_tag'] : '' ; ?></textarea>
                    
                <br>
                <label>&nbsp;</label><input type="submit" name="Submit" id="Submit" />
            </form>

            
        </div>
<?php if (isset($page_values['seq'])) { ?>            
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
