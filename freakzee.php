<?php
session_start();

require_once 'classes/Freakzee.php';
require_once 'includes/Freakzee/header.php';

//Freakzee::killGame();

if(isset($_SESSION['game'])){
    $game = unserialize($_SESSION['game']);
    $showDice = true;
    $showButton = true;
    
    if(isset($_POST['submit']) && !empty($_POST['action'])){
        switch($_POST['action']){
            case 'start':
                if(!$game->gameStarted){
                    $game->gameStarted = true;
                    $game->rollDice();
                    $showDice = true;
                    Freakzee::saveGame($game);
                }
                break;
            case 'holdRoll':
                if($game->gameStarted){
                    $showDice = true;
                    $hold = [];
                    
                    if($game->getRollsLeft() > 0){
                        if(!empty($_POST['hold'])){
                            $holding = explode(',',$_POST['hold']);
                            if(count($holding) > 4){
                                echo Freakzee::error('You can only hold four dice!');
                            }else {
                                $currentHold = $game->getHoldAndRold();
                                sort($holding);
                                sort($currentHold);
                                
                                if(array_intersect($holding, $currentHold) == $holding){
                                    $game->updateHolding($holding);
                                    $game->rollDice();
                                    Freakzee::saveGame($game);
                                }else {
                                    echo Freakzee::error('You do not hold all the dice you decided to hold...');
                                }
                            }
                        }else {
                            $game->rollDice();
                            Freakzee::saveGame($game);
                        }
                    }else {
                        echo Freakzee::error('You are out of rolls for this turn, accept fate and continue.');
                    }
                }else {
                    echo Freakzee::error('You have not started the game yet!');
                }
                break;
            case 'newRoll':
                if($game->gameStarted){
                    $game->resetRolls();
                    $game->rollDice();
                    Freakzee::saveGame($game);
                }else {
                    echo Freakzee::error('You have not started the game yet!');
                }
                break;
            case 'pickScore':
                if($game->gameStarted){
                    if(!empty($_POST['score'])){
                        $score = $_POST['score'];
                        if(array_key_exists($score, $game->getScoreTable())){
                            $score = $game->determinePossibleScore($score, true);
                            $game->resetRolls();
                            $game->rollDice();
                            Freakzee::saveGame($game);
                        }else {
                            echo Freakzee::error('Invalid column to choose from!');
                        }
                    }
                }else {
                    echo Freakzee::error('You have not started the game yet!');
                }
                break;
            case 'restartGame':
                if($game->gameStarted){
                    $game->resetGame();
                    $game->rollDice();
                    Freakzee::saveGame($game);
                }
                break;
        }
    }
    
    include 'includes/Freakzee/gameScreen.php';
    
}else {
    if(isset($_POST['submit']) && !empty($_POST['name'])){
        $newGame = new Freakzee($_POST['name']);
        $_SESSION['game'] = serialize($newGame);
        header('Location: freakzee.php');
    }
    
    include 'includes/Freakzee/newGame.php';
}

require_once 'includes/Freakzee/footer.php';
