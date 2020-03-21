<?php
    require('game.php');
?>

<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <title>Fate/ObjectOriented</title>
    <link rel="stylesheet" href="style.css">
    </head>
    <body>
            <?php if(empty($_SESSION)){ ?>
                <div class="title_img"><img src="img/fate_title.png">
                    <div class="game_window">
                        <form method="post">
                            <input type="submit" class="start-btn" name="start" value="▶︎ 聖杯戦争の開始">
                        </form>
                    </div>
                </div>
            <?php }elseif($resultFlg || $_SESSION['human']->getHp() <= 0){ ?>
                <div class="title_img"><img src="img/fate-result.jpg">
                    <div class="game_window">
                        <div class="result-window">
                            <div class="result">
                                <h2>取得した令呪</h2>
                                <p><?php echo $_SESSION['knockDownCount'] ;?>個</p>
                            </div>
                            <form action="" method="POST">
                                <input type="submit" name="end" value="タイトルへ">
                            </form>
                        </div>
                    </div>
                </div>
            <?php }elseif($startFlg = true && $_SESSION['human']->getHp() >= 0){?>
                <div class="title_img"><img src="img/fate-back.jpg">
                    <div class="game_window">
                        <h2><?php echo $_SESSION['monster']->getName().'が現れた！！'; ?></h2>
                        <p class="enemy-hp">HP：<?php echo $_SESSION['monster']->getHp(); ?></p>
                        <div class="monster-img">
                            <img src="<?php echo $_SESSION['monster']->getImg(); ?>">
                        </div>
                        <div class="battle-window">
                            <div class="message-area" id="scrollTarget">
                                
                                <p><?php echo (!empty($_SESSION['history'])) ? $_SESSION['history'] : ''; ?></p>
                                
                            </div>
                            <form class="command-area" method="post">
                                    <p>HP：<span><?php echo $_SESSION['human']->getHp(); ?></span></p>
                                    <input class="command-area-btn" type="submit" name="attack" value="▶︎攻撃する">
                                    <input class="command-area-btn" type="submit" name="escape" value="▶︎逃げる">
                                    <input class="command-area-btn" type="submit" name="end" value="▶︎ゲーム終了">
                            </form>
                        </div>
                        <div class="count-area">
                            <p>獲得した令呪：<?php echo $_SESSION['knockDownCount']; ?></p>
                        </div>
                    </div>
                </div>
           <?php }?>
           
        </div>
    </div>
        <h1 style="text-align:center; color:#333;">「Fate/Object Oriented」</h1>
        
        <script>
            var Element = document.getElementById('scrollTarget');
            var scrollHeight = Element.scrollHeight;
            var bottom = Element.scrollHeight - Element.clientHeight;
            document.addEventListener('DOMContentLoaded',function(){
                Element.scroll(0, bottom);
            });
            
        </script>
    </body>
</html>
