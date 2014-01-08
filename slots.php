<?php
session_start();

require_once 'classes/Slots.php';

if(isset($_GET['restart'])){
    unset($_SESSION['slots']);
    header('Location: slots.php');
}

if(!isset($_SESSION['slots'])){
    $_SESSION['slots'] = serialize(new Slots());
}

$slots = unserialize($_SESSION['slots']);
$spin = $slots->spin();

if($slots->canSpin()){
    $board = $slots->build($spin);
    $payout = $slots->payout($spin);
    $slots->addTokens($payout['payout']);
}else {
    $board = $spin['message'];
}

$bet = $slots->bet;
$lines = $slots::LINES;
$tokens = $slots->tokens;
$_SESSION['slots'] = serialize($slots);
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="Marcus Recck" />
    
    <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" type="text/css" href="css/slots.css" />
    
    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="js/slots.js"></script>
    
    <script>
    $(document).ready(function(){
        var lines = <?php echo $payout['lines']; ?>;
        addLines(lines);
        
        $('.lineHolder').eq(0).show();
        
        $('.chooseLine').on('change', function(){
            var val = $(this).val();
            $('.lineHolder').hide();
            $('.lineHolder').eq(val - 1).show();
        });
    });
    </script>

	<title>Slots!!</title>
</head>

<body>

    <div id="holder">
        <h1>Slots by marcus.</h1>
        
        <?php echo $board; ?>
        
        <div class="info">
            Bet Per Line: <?php echo number_format($bet); ?>
            | Your Bet: <?php echo number_format($bet * $lines); ?>
            | Tokens: <?php echo number_format($tokens); ?>
            | Spin Payout: <?php echo number_format($payout['payout']); ?>
            <?php if($payout['payout'] > 0): ?>
            | Lines Hit: <?php echo implode(', ', json_decode($payout['lines'], 1)); ?>
            <?php endif; ?>
            <br />
            <a href="slots.php" class="spinAgain">Spin Again!</a>
        </div>
    </div>
    <div id="lines">
        <h3>Pay Lines</h3>
        <select class="chooseLine">
            <?php for($i = 1; $i <= 15; $i++): ?>
            <option value="<?php echo $i; ?>">Line <?php echo $i; ?></option>
            <?php endfor; ?>
        </select>
        <div class="lineHolder">
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
            
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
        </div>
        
        <div class="lineHolder">
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
        
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
        </div>
        
        <div class="lineHolder">
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
        </div>
        
        <div class="lineHolder">
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            
            <div class="box"></div>
            <div class="box"></div>
            <div class="box line diag-left color-y"></div>
            <div class="box"></div>
            <div class="box"></div>
            
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
        </div>
        
        <div class="lineHolder">
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
            
            <div class="box"></div>
            <div class="box"></div>
            <div class="box line diag-right color-y"></div>
            <div class="box"></div>
            <div class="box"></div>
            
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
        </div>
        
        <div class="lineHolder">
            <div class="box line diag-left color-y"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box line diag-right color-y"></div>
            
            <div class="box"></div>
            <div class="box line diag-left color-y"></div>
            <div class="box"></div>
            <div class="box line diag-right color-y"></div>
            <div class="box"></div>
            
            <div class="box"></div>
            <div class="box"></div>
            <div class="box line v-up color-y"></div>
            <div class="box"></div>
            <div class="box"></div>
        </div>
        
        <div class="lineHolder">
            <div class="box"></div>
            <div class="box"></div>
            <div class="box line v-down color-y"></div>
            <div class="box"></div>
            <div class="box"></div>
            
            <div class="box"></div>
            <div class="box line diag-right color-y"></div>
            <div class="box"></div>
            <div class="box line diag-left color-y"></div>
            <div class="box"></div>
            
            <div class="box line diag-right color-y"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box line diag-left color-y"></div>
        </div>
        
        <div class="lineHolder">
            <div class="box"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box"></div>
            
            <div class="box line diag-right color-y"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box line diag-left color-y"></div>
            
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
        </div>
        
        <div class="lineHolder">
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            
            <div class="box line diag-left color-y"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box line diag-right color-y"></div>
            
            <div class="box"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box"></div>
        </div>
        
        <div class="lineHolder">
            <div class="box line diag-left color-y"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box line diag-right color-y"></div>
            
            <div class="box"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box"></div>
            
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
        </div>
        
        <div class="lineHolder">
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            
            <div class="box"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box line horizontal color-y"></div>
            <div class="box"></div>
            
            <div class="box line diag-right color-y"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box line diag-left color-y"></div>
        </div>
        
        <div class="lineHolder">
            <div class="box line diag-left color-y"></div>
            <div class="box"></div>
            <div class="box line v-down color-y"></div>
            <div class="box"></div>
            <div class="box line diag-right color-y"></div>
            
            <div class="box"></div>
            <div class="box line v-up color-y"></div>
            <div class="box"></div>
            <div class="box line v-up color-y"></div>
            <div class="box"></div>
            
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
        </div>
        
        <div class="lineHolder">
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            
            <div class="box"></div>
            <div class="box line v-down color-y"></div>
            <div class="box"></div>
            <div class="box line v-down color-y"></div>
            <div class="box"></div>
            
            <div class="box line diag-right color-y"></div>
            <div class="box"></div>
            <div class="box line v-up color-y"></div>
            <div class="box"></div>
            <div class="box line diag-left color-y"></div>
        </div>
        
        <div class="lineHolder">
            <div class="box"></div>
            <div class="box line v-down color-y"></div>
            <div class="box"></div>
            <div class="box line v-down color-y"></div>
            <div class="box"></div>
            
            <div class="box line diag-right color-y"></div>
            <div class="box"></div>
            <div class="box line v-up color-y"></div>
            <div class="box"></div>
            <div class="box line diag-left color-y"></div>
            
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
        </div>
        
        <div class="lineHolder">
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            
            <div class="box line diag-left color-y"></div>
            <div class="box"></div>
            <div class="box line v-down color-y"></div>
            <div class="box"></div>
            <div class="box line diag-right color-y"></div>
            
            <div class="box"></div>
            <div class="box line v-up color-y"></div>
            <div class="box"></div>
            <div class="box line v-up color-y"></div>
            <div class="box"></div>
        </div>
    </div>
    <div id="payout">
        <h3>Payout Table</h3>
        
        <div class="color color-x"></div>
        <div class="color color-x"></div>
        <div class="color color-x"></div>
        <div class="color color-x"></div>
        <div class="color color-x"></div>
        <div class="payout">1000x</div>
        
        <div class="color color-x"></div>
        <div class="color color-x"></div>
        <div class="color color-x"></div>
        <div class="color color-x"></div>
        <div class="payout">300x</div>
        
        <div class="color color-x"></div>
        <div class="color color-x"></div>
        <div class="color color-x"></div>
        <div class="payout">100x</div>
        
        <div class="color color-y"></div>
        <div class="color color-y"></div>
        <div class="color color-y"></div>
        <div class="color color-y"></div>
        <div class="color color-y"></div>
        <div class="payout">300x</div>
        
        <div class="color color-y"></div>
        <div class="color color-y"></div>
        <div class="color color-y"></div>
        <div class="color color-y"></div>
        <div class="payout">150x</div>
        
        <div class="color color-y"></div>
        <div class="color color-y"></div>
        <div class="color color-y"></div>
        <div class="payout">50x</div>
        
        <div class="color color-r"></div>
        <div class="color color-r"></div>
        <div class="color color-r"></div>
        <div class="color color-r"></div>
        <div class="color color-r"></div>
        <div class="payout">150x</div>
        
        <div class="color color-r"></div>
        <div class="color color-r"></div>
        <div class="color color-r"></div>
        <div class="color color-r"></div>
        <div class="payout">75x</div>
        
        <div class="color color-r"></div>
        <div class="color color-r"></div>
        <div class="color color-r"></div>
        <div class="payout">25x</div>
        
        <div class="color color-b"></div>
        <div class="color color-b"></div>
        <div class="color color-b"></div>
        <div class="color color-b"></div>
        <div class="color color-b"></div>
        <div class="payout">75x</div>
        
        <div class="color color-b"></div>
        <div class="color color-b"></div>
        <div class="color color-b"></div>
        <div class="color color-b"></div>
        <div class="payout">50x</div>
        
        <div class="color color-b"></div>
        <div class="color color-b"></div>
        <div class="color color-b"></div>
        <div class="payout">20x</div>
        
        <div class="color color-g"></div>
        <div class="color color-g"></div>
        <div class="color color-g"></div>
        <div class="color color-g"></div>
        <div class="color color-g"></div>
        <div class="payout">50x</div>
        
        <div class="color color-g"></div>
        <div class="color color-g"></div>
        <div class="color color-g"></div>
        <div class="color color-g"></div>
        <div class="payout">15x</div>
        
        <div class="color color-g"></div>
        <div class="color color-g"></div>
        <div class="color color-g"></div>
        <div class="payout">5x</div>
        
        <div class="color color-p"></div>
        <div class="color color-p"></div>
        <div class="color color-p"></div>
        <div class="color color-p"></div>
        <div class="color color-p"></div>
        <div class="payout">40x</div>
        
        <div class="color color-p"></div>
        <div class="color color-p"></div>
        <div class="color color-p"></div>
        <div class="color color-p"></div>
        <div class="payout">13x</div>
        
        <div class="color color-p"></div>
        <div class="color color-p"></div>
        <div class="color color-p"></div>
        <div class="payout">5x</div>
        
        <div class="color color-o"></div>
        <div class="color color-o"></div>
        <div class="color color-o"></div>
        <div class="color color-o"></div>
        <div class="color color-o"></div>
        <div class="payout">35x</div>
        
        <div class="color color-o"></div>
        <div class="color color-o"></div>
        <div class="color color-o"></div>
        <div class="color color-o"></div>
        <div class="payout">10x</div>
        
        <div class="color color-o"></div>
        <div class="color color-o"></div>
        <div class="color color-o"></div>
        <div class="payout">3x</div>
        
        <div class="color color-z"></div>
        <div class="payout">Wild, replaces any but white</div>
    </div>

</body>
</html>