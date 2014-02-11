<?php
session_start();

require_once 'classes/Bingo.php';

if(isset($_POST['restart'])){
    unset($_SESSION['bingo']);
}

if(!isset($_SESSION['bingo'])){
    $_SESSION['bingo'] = serialize(new Bingo());
}

$bingo = unserialize($_SESSION['bingo']);

if(isset($_POST['action'])){
    header('Content-type: application/json');
    echo $bingo->managePost();
    $_SESSION['bingo'] = serialize($bingo);
    exit;
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Bingo!!</title>
    
    <meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="Marcus Recck" />
    
    <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" type="text/css" href="css/bingo.css" />
    <link rel="stylesheet" type="text/css" href="css/animate.min.css" />
    
    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script>
        var init = <?php echo $bingo->getLastFive(); ?>;
    </script>
    <script src="js/bingo.js"></script>
</head>
<body>

    <div class="container">
        <h1>Bingo by Marcus</h1>
        
        <div class="called">
        </div>
        
        <div class="bingo-board">
            <?php echo $bingo; ?>
        </div>
        
        <button type="button" id="bingo">BINGO!!</button>
    </div>

</body>
</html>