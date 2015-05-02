<?php
include_once('cx.php');         // connection string
include_once('MySQL.01.php');   // datalayer
include_once('Form.01.php');    // form
include_once('CreateInsertMySQL.01.php');   // create the insert statement
include_once("Framework.01.php");   // generic functions

$today = date('md');

$sql = new MySQL($_cx['host'],$_cx['user'],$_cx['password'],$_cx['db']);    // setup datalayer
$sql->Open();
if ($sql->HasErr)
   die("Unable to open sql :: ".$sql->ErrMsg);

$query = "SELECT seq FROM post_type where noshow != 'Y' order by ord; ";
$sql->Query($query);
if ($sql->HasErr)
   die("Unable to query sql :: ".$sql->ErrMsg);

$content_query = '';
for ($y = $__begyear; $y<=$__endyear; $y++)
{
    while($row = $sql->NextRecord())
    {
        $content_query .= ($content_query != '' ? " union all " : '')."select post_name, prodlink, title, author, release_date, wk_spent, content, showdte,yr from (select * from besttoday where post_type = ".$row['seq']." and showdte <= '".$today."' and yr = ".$y." order by showdte desc limit 1) as a".$y.$row['seq'];    
    }
    $sql->GotoRec(0);
    $content_query .= " union all select 'Event' as post_name, post_on_link as prodlink, '' as title, author, release_date, 0 as wk_spent, post_note as content, showdte,yr from (select * from besttoday where post_name = 'Feature' and showdte = '".$today."' and yr = ".$y." order by showdte ) as a".$y.'Events'; 
}

//die($content_query);

$sql->Query($content_query);
if ($sql->HasErr)
   die("Unable to query sql :: ".$sql->ErrMsg);

?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <link href='http://fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'>
            <link href='main.css' rel='stylesheet' type='text/css'>
        
    </head>
    <body>
        <?php include_once("header.php") ?>
        
        <!--<section>
            
            <article>
                <img id="featurePic" src="http://lorempixel.com/200/200/">
                Lorem ipsum dolor sit amet, summo graeci ad nam, et atqui falli mel. Mel at tritani consetetur, id sed ipsum scribentur. Usu ullamcorper contentiones no, per dicam expetendis liberavisse ea, sea stet tantas ad. Cum ex dicunt inermis, consul perpetua vim no.

Tincidunt mnesarchum cum ad, qui no senserit forensibus. Ferri feugait principes in usu. Mei eu munere perpetua consequat. Ius cu malis utinam timeam, diam scribentur in has, mea in tation dictas.

Eum reque convenire at. Ne graece praesent neglegentur duo, ex error omittam nam. Ad graecis fastidii tacimates eam, at vis perpetua scriptorem. Ne odio mundi salutandi quo, vel in nulla graece contentiones. Dictas inciderint vel no, sit labitur saperet accumsan ea, legimus delectus at sit.
            </article>
        </section>-->
        <!--<nav>Toys Games Movies TV Music</nav>-->
        <section>
            
<?php 
$yr = '';
$post_name = '';
while ($row = $sql->NextRecord()) 
{ ?>
            <?php if ($yr != $row['yr']) {  ?>
            <header><?php echo $row['yr']; ?></header>
            <?php } ?>
            <article>
                <?php if ($yr != $row['yr'] or $post_name != $row['post_name']) { ?>
                <header><?php echo $row['post_name'] != '' ? $row['post_name'] : ''; ?> </header>
                <?php } ?>
                <div class='prodBox'>
                    <?php echo $row['prodlink'] != '' ? stripslashes($row['prodlink']) : ''; ?>
                </div>
                <div class='infoBox'>
                    <?php echo $row['title'] != '' ? stripslashes($row['title']).'<br>' : ''; ?>
                    <?php echo $row['author'] != '' ? stripslashes($row['author']).'<br>' : ''; ?><br>
                    <?php echo $row['release_date'] != '0000-00-00' ? '<label>Release Date:</label>'.$row['release_date'].'<br>' : ''; ?>
                    <?php echo $row['wk_spent'] != '0' ? '<label>Weeks Spent at #1:</label>'.$row['wk_spent'].'<br>' : ''; ?>
                    <?php echo $row['content'] != '' ? $row['content'].'<br>' : ''; ?>
                    <br>
                    <!--Get it here :
                    <ul>
                        <li>Amazon</li>
                        <li>iTunes</li>
                        <li>Google Play</li>
                    </ul>       
                    <br>-->
                </div>
                
                
            </article>
<?php 
$yr = $row['yr'];
$post_name = $row['post_name'];

                } ?>
        </section>
        <?php include_once("footer.php"); ?>
    </body>
</html>
