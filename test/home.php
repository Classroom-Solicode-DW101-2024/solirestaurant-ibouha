<?php 
require "config.php";
if(isset($_SESSION["client"])){

    echo "le nom de client ".$_SESSION["client"]["nomCl"];

    $typeCuisine = getKitchenType();
    print_r($typeCuisine);



}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="content">
        <?php foreach($typeCuisine as $type):?>
            <div class="typeCuisineSection">
            <h2><?= $type['TypeCuisine']?></h2>
                <div class="Cards">
                <?php 
                $Dishes=getdishesByType($type['TypeCuisine']);
                foreach($Dishes as $dish):?>
                <div class='card'>
                    <img src="<?= $dish['image']?>">
                    <h3><?= $dish['nomPlat']?></h3>
                    <h4><?= $dish['categoriePlat']?></h4>
                    <span><?= $dish['prix']?></span>
                    <a href=""><button>Add to cart</button></a>
                  </div>
                  <?php endforeach;?>
                </div>
                
            

            </div>
        <?php endforeach;?>
    </div>
</body>
</html>