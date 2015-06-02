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

if (isset($_POST['Submit']) or isset($_POST['quickadd']))
{
   
    switch ($_POST['quickadd'])
    {
        case "movie":
            $insert = "INSERT INTO `content`
                        (
                        `post_type`,
                        `nav_tag`,
                        `title`,                        
                        `wk_spent`)
                        VALUES
                        (
                        1,
                        7,
                        '".$_POST['title']."',                           
                        ".$_POST['wk_spent']."
                        );";
            $nbr = $form->Save($insert);
            if ($form->Error['HasError'])
                die($form->Error['Message']);
            
            $insert = "INSERT INTO `post_on`
                        (
                        `content_seq`,
                        `yr`,
                        `showdte`
                        )
                        VALUES
                        (
                        ".$nbr.",
                        ".$_POST['yr'].",
                        '".(str_pad($_POST['month'],2,'0',STR_PAD_LEFT).str_pad($_POST['day'],2,'0',STR_PAD_LEFT))."'
                        );";
            $nbr = $form->Save($insert);
            if ($form->Error['HasError'])
                die($form->Error['Message']);
            
            break;
        case "book":
      
            $insert = "INSERT INTO `content`
                        (
                        `post_type`,
                        `nav_tag`,
                        `title`,
                        author,
                        `wk_spent`)
                        VALUES
                        (
                        4,
                        8,
                        '".$_POST['title']."',
                            '".$_POST['author']."',
                        ".$_POST['wk_spent']."
                        );";
            $nbr = $form->Save($insert);
            if ($form->Error['HasError'])
                die($form->Error['Message']);
            
            $insert = "INSERT INTO `post_on`
                        (
                        `content_seq`,
                        `yr`,
                        `showdte`
                        )
                        VALUES
                        (
                        ".$nbr.",
                        ".$_POST['yr'].",
                        '".(str_pad($_POST['month'],2,'0',STR_PAD_LEFT).str_pad($_POST['day'],2,'0',STR_PAD_LEFT))."'
                        );";
            $nbr = $form->Save($insert);
            if ($form->Error['HasError'])
                die($form->Error['Message']);
            
            break;
            
        default:
            $insert = "";
    }
    
    
    header("location:main.php");
}

if (isset($_POST['Missing']))
{
    $query = "update content set prodlink = '".$_POST['prodlink']."' where seq = ".$_POST['seq'];
    $form->Save($query);
    if ($form->Error['HasError'])
        die($form->Error['Message']);
}




$sql->Open();       // connect
if($sql->HasErr)
    die("Unable to open SQL [00] :: ".$sql->ErrMsg);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Main</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                width:100px;
                display:block;
                text-align: right;
                float: left;
                padding-right: 10px;
            }
            .quickaddBlock
            {
                width:300px;
                margin:auto;
                padding:5px;
                border:solid thin #000;
                margin-bottom:10px;
                
            }
        #missing
        {
            width:900px;
            margin:auto;
            padding:5px;
            
        }
        </style>
        
    </head>
    <body>
        <?php include_once("nav_bar.php"); ?>
     
        <div id="movie" class="quickaddBlock">
            <form name="movie" action="" method="post">
                
                Quick Add Movie<br>
                <input type="hidden" name="quickadd" id="quickadd" value="movie"/>
                <label>Title</label> <input type="text" name="title" id="title" size="25" value="<?php echo isset($page_values['title']) ? $page_values['title'] : '' ; ?>" />
                <br><label>Week Spent #1</label> <input type="text" name="wk_spent" id="wk_spent" size="5" value="<?php echo isset($page_values['wk_spent']) ? $page_values['wk_spent'] : '' ; ?>" />
                
            <br>
                <label>&nbsp;</label>
            <select id="month" name="month">
               
                <?php
                    for($i=1;$i<=12;$i++)
                    {
                        echo '<option value="'.str_pad($i,2,'0',STR_PAD_LEFT).'">'. date('F',strtotime('2015-'.str_pad($i,2,'0',STR_PAD_LEFT).'-01')).' ('.str_pad($i,2,'0',STR_PAD_LEFT).')</option>';
                    }
                ?>
                
                
            </select>
            
            <br><label> &nbsp;</label>
            <select id="day" name="day">
               
                <?php
                    for($i=1;$i<=31;$i++)
                    {
                        echo '<option value="'.str_pad($i,2,'0',STR_PAD_LEFT).'">'.str_pad($i,2,'0',STR_PAD_LEFT).'</option>';
                    }
                ?>
                
                
            </select>
                <br><label> &nbsp;</label>
                 <select id="yr" name="yr">
               
                <?php
                    for($i=1980;$i<=1989;$i++)
                    {
                        echo '<option value="'.str_pad($i,2,'0',STR_PAD_LEFT).'">'.str_pad($i,2,'0',STR_PAD_LEFT).'</option>';
                    }
                ?>
                
                
            </select>
                <br>
                <label> &nbsp;</label>
                <input type="submit" name="Submit" id="Submit" />
            </form>

            
        </div>
        
        <div id="book" class="quickaddBlock">
            <form name="book" action="" method="post">
                
                Quick Add Book<br>
                <input type="hidden" name="quickadd" id="quickadd" value="book"/>
                <br><label>Title</label><input type="text" name="title" id="title" size="25" value="<?php echo isset($page_values['title']) ? $page_values['title'] : '' ; ?>" />
                <br><label>Author</label><input type="text" name="author" id="author" size="25" value="<?php echo isset($page_values['author']) ? $page_values['author'] : '' ; ?>" />
                <br><label>Week Spent #1</label><input type="text" name="wk_spent" id="wk_spent" size="5" value="<?php echo isset($page_values['wk_spent']) ? $page_values['wk_spent'] : '' ; ?>" />
                
            <br><label>&nbsp;</label>
            <select id="month" name="month">
               
                <?php
                    for($i=1;$i<=12;$i++)
                    {
                        echo '<option value="'.str_pad($i,2,'0',STR_PAD_LEFT).'">'. date('F',strtotime('2015-'.str_pad($i,2,'0',STR_PAD_LEFT).'-01')).' ('.str_pad($i,2,'0',STR_PAD_LEFT).')</option>';
                    }
                ?>
                
                
            </select>
            
            <br><label>&nbsp;</label>
            <select id="day" name="day">
               
                <?php
                    for($i=1;$i<=31;$i++)
                    {
                        echo '<option value="'.str_pad($i,2,'0',STR_PAD_LEFT).'">'.str_pad($i,2,'0',STR_PAD_LEFT).'</option>';
                    }
                ?>
                
                
            </select><br><label>&nbsp;</label>
                 <select id="yr" name="yr">
               
                <?php
                    for($i=1980;$i<=1989;$i++)
                    {
                        echo '<option value="'.str_pad($i,2,'0',STR_PAD_LEFT).'">'.str_pad($i,2,'0',STR_PAD_LEFT).'</option>';
                    }
                ?>
                
                
            </select>
                
                <br><label>&nbsp;</label>
                <input type="submit" name="Submit" id="Submit" />
            </form>

            
        </div>
        
        <div id="missing">
            <table>
            <?php
            
                $query = "SELECT content.seq, author as 'Author', title as 'Title', min(post_on.showdte) as 'Show Date' FROM content left join post_on on post_on.content_seq = content.seq where prodlink is null or prodlink = '' group by content.seq order by min(post_on.showdte);";
                //die($query);
                $sql->Query($query);
                $header = true;
                
                $cnt = 0;
                while($row = $sql->NextRecord())
                {
                    if ($header)
                    {
                        $header = false;
                        echo '<tr>';
                        echo '<th>Edit</th>';
                        foreach($row as $key=>$val)
                        {
                            if ($key == 'seq')
                                continue;
                            echo '<th>'.$key.'</th>';
                        }
                        echo '<th>Product Link</th>';
                        echo '<th>YouTube</th>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td><a href="content.php?edit='.$row['seq'].'">Edit</a></td>';
                    foreach($row as $key=>$val)
                    {
                        if ($key == 'seq')
                                continue;
                        echo '<td>'.$val.'</td>';
                    }
                    echo '<td><form name="missing'.($cnt++).'" action="" method="post"><input type="hidden" id="seq" name = "seq" value="'.$row['seq'].'" /> <textarea id="prodlink" name="prodlink"></textarea><input type="submit" id="Missing" name="Missing" value="Save"/></form></td>';
                    echo '<td><a href="https://www.youtube.com/results?search_query='.($row['Author'].$row['Title']).'" target="_blank">YouTube</a></td>';
                    echo '</tr>';
                }
                
            ?>
            </tabel>
        </div>
      
    </body>
</html>
