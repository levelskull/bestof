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

$query = "SELECT seq FROM bestof.post_type where name != 'Feature' order by ord; ";
$sql->Query($query);
if ($sql->HasErr)
   die("Unable to query sql :: ".$sql->ErrMsg);

$content_query = '';
while($row = $sql->NextRecord())
{
    $content_query .= ($content_query != '' ? " union all " : '')."select * from (select * from besttoday where post_type = ".$row['seq']." and showdte <= '".$today."' and yr between ".$__begyear." and ".$__endyear." order by showdte desc limit 1) as a".$row['seq'];    
}

$sql->Query($content_query);
if ($sql->HasErr)
   die("Unable to query sql :: ".$sql->ErrMsg);

?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
            <link href='../bestof/main.css' rel='stylesheet' type='text/css'>
        
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
            <header>On this Day : <?php echo date("F j, Y"); ?></header>
<?php while ($row = $sql->NextRecord()) { ?>
            <article>
                
                <header>#1 Single</header>
                <div class='prodBox'>
                    <?php echo $row['prodlink']; ?>
                </div>
                <div class='infoBox'>
                    <?php echo $row['title'] != '' ? $row['title'].'<br>' : ''; ?>
                    <?php echo $row['author'] != '' ? $row['author'].'<br>' : ''; ?><br>
                    <?php echo $row['release_date'] != '' ? '<label>Release Date:</label>'.$row['release_date'].'<br>' : ''; ?>
                    <?php echo $row['wk_spent'] != '' ? '<label>Weeks Spent at #1:</label>'.$row['wk_spent'].'<br>' : ''; ?>
                    <br>
                    Get it here :
                    <ul>
                        <li>Amazon</li>
                        <li>iTunes</li>
                        <li>Google Play</li>
                    </ul>       
                    <br>
                </div>
                
                
            </article>
<?php } ?>
        </section>
        <?php include_once("footer.php"); ?>
    </body>
</html>
