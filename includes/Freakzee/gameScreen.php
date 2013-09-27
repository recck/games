<h3>Current playing as: <?php echo $game->name; ?></h3>
<?php
if(!$game->gameStarted){
?>
    <form method="post" action="freakzee.php" class="newGame">
        <input type="hidden" name="action" value="start" />
        <button type="submit" name="submit">
            Roll The Dice!
        </button>
    </form>
<?php
}else if($game->gameStarted && $game->getRollsLeft() > 0 && !$game->fullBoard()){
?>
    <form method="post" action="freakzee.php" class="newGame">
        <input type="hidden" name="action" value="holdRoll" />
        <input type="hidden" name="hold" id="hold" />
        <button type="submit" name="submit" id="holdAndRoll">
            Hold and Roll
        </button>
    </form>
<?php
}else if($game->gameStarted && $game->getRollsLeft() <= 0 && !$game->fullBoard()){
?>
    <form method="post" action="freakzee.php" class="newGame">
        <input type="hidden" name="action" value="newRoll" />
        <button type="submit" name="submit" id="nextTurn">
            Next Turn, Roll!
        </button>
    </form>
<?php
}

if($game->gameStarted){
    echo '
    <div class="table">' . $game->showTable() . '</div>';
}

if($showDice && $game->gameStarted && !$game->fullBoard()){
    echo '
    <div class="dice"><div class="title">Current Roll</div>' . $game->showDice() . '
    </div><div class="clear"></div>';
    echo '
    <div class="hold"><div class="title">Holding</div>' . $game->showHold() . '
    </div>';
}else if($game->fullBoard() && $game->gameStarted){
    echo '
    <div class="restart">
        <div class="title">Game Over</div>
        <div class="finalScore">
            You Scored
            <div class="finalScoreNum">'.$game->getTotalScore().'</div>
        </div>
        <div class="restartForm">
            <form method="post" action="freakzee.php">
                <input type="hidden" name="action" value="restartGame" />
                <button type="submit" name="submit">
                    New Game?
                </button>
            </form>
        </div>
    </div>';
}