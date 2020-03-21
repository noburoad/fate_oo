
<?php  
    $titleFlg = $_POST['title'];
?>

<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <title>Fate/ObjectOriented</title>
    <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="title_img"><img src="img/fate-result.jpg">
        <div class="result-window">
            <?php if($titleFlg){ ?>
                <?php $_POST = array(); ?>
                <?php $_SESSION = array(); ?>
                <?php  header("Location:index.php"); ?>
            <?php }else{ ?>
                
            <?php } ?>
        </div>
    </div>
        
        
    </body>
</html>
