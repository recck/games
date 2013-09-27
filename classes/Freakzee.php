<?php

class Freakzee {
    private $table = [
        'Ones' => -1,
        'Twos' => -1,
        'Threes' => -1,
        'Fours' => -1,
        'Fives' => -1,
        'Sixes' => -1,
        '3 of a Kind' => -1,
        '4 of a Kind' => -1,
        'Full House' => -1,
        'Small Straight' => -1,
        'Large Straight' => -1,
        'Freakzee' => -1,
        'Chance' => -1
    ];
    
    public $name;
    private $holding = [0, 0, 0, 0, 0];
    private $roll = [];
    private $rollsLeft = 3;
    public $gameStarted = false;
    
    public function __construct($name){
        $this->name = htmlentities($name);
    }
    
    public function resetGame(){
        $this->holding = [0, 0, 0, 0, 0];
        $this->roll = [];
        $this->rollsLeft = 3;
        
        foreach($this->table AS $col => &$score){
            $score = -1;
        }
    }
    
    static function killGame(){
        session_destroy();
    }
    
    static function saveGame($game){
        $_SESSION['game'] = serialize($game);
    }
    
    static function error($text){
        return '<div class="error">' . $text . '</div>';
    }
    
    public function determinePossibleScore($score, $update = false){
        $digits = [
            'Ones' => 1,
            'Twos' => 2,
            'Threes' => 3,
            'Fours' => 4,
            'Fives' => 5,
            'Sixes' => 6
        ];
        
        $value = 0;
        $curRoll = $this->getHoldAndRold();
        sort($curRoll);
        
        switch($score){
            case 'Ones':
            case 'Twos':
            case 'Threes':
            case 'Fours':
            case 'Fives':
            case 'Sixes':
                $key = $digits[$score];
                $value = $key * count(array_filter($curRoll, function($die) use($key){
                    return $die == $key;
                }));
                break;
            case '3 of a Kind':
            case '4 of a Kind':
                $minRepeat = (int)$score[0];
                $values = array_count_values($curRoll);
                foreach($values AS $key => $val){
                    if($val >= $minRepeat){
                        $value = array_sum($curRoll);
                        break;
                    }
                }
                break;
            case 'Full House':
                $values = array_count_values($curRoll);
                if(count($values) == 2){
                    $aVals = array_values($values);
                    if( ($aVals[0] == 3 && $aVals[1] == 2) || ($aVals[0] == 2 && $aVals[1] == 3) ){
                        $value = 25;
                    }
                }
                break;
            case 'Small Straight':
                $possible = [ '1234', '2345', '3456' ];
                $curRollText = implode(array_unique($curRoll));
                foreach($possible AS $combo){
                    if(stristr($curRollText, $combo)){
                        $value = 30;
                        break;
                    }
                }
                break;
            case 'Large Straight':
                $curRollText = implode($curRoll);
                if($curRollText == '12345' || $curRollText == '23456'){
                    $value = 40;
                }
                break;
            case 'Freakzee';
                $values = array_count_values($curRoll);
                if(count($values) == 1){
                    $value = 50;
                }
                break;
            case 'Chance':
                $value = array_sum($curRoll);
                break;
        }
        
        if($update && $this->table[$score] == -1){
            $this->table[$score] = $value;
        }
        
        return $value;
    }
    
    public function showDice(){
        $output = '';
        
        foreach($this->roll AS $die){
            $output .= '
        <div class="die">
            <img data-die="'.$die.'" src="images/freakzee/'.$die.'.png" />
        </div>
    ';
        }
        
        return $output;
    }
    
    public function showHold(){
        $output = '';
        
        foreach($this->holding AS $hold){
            if($hold != 0){
                $output .= '
        <div class="die">
            <img class="bordered" data-die="'.$hold.'" src="images/freakzee/'.$hold.'.png" />
        </div>';
            }
        }
        
        return $output;
    }
    
    public function showTable(){
        $output = '<table class="scoreTable">';
        $output .= '<thead><tr><th>Column</th><th>Score</th></tr></thead>';
        $output .= '<tbody>';
        
        $calcTopHalf = $this->calcTopHalf();
        $bonus = $calcTopHalf >= 63 ? 35 : 0;
        
        foreach($this->table AS $col => $score){
            $possible = $score != -1 ? $score : $this->determinePossibleScore($col);
            $text = $score == 0 ? '--' : $possible;
            
            if($score == -1 && $col != 'Bonus'){
                $view = '<form method="post" action="freakzee.php">';
                $view .= '<input type="hidden" name="action" value="pickScore" />';
                $view .= '<input type="hidden" name="score" value="'.$col.'" />';
                $view .= '<button type="submit" name="submit">' . $text . '</button>';
                $view .= '</form>';
            }else {
                $view = $score;
            }
            
            $output .= '<tr><td>'.$col.'</td><td>'.$view.'</td></tr>';
            
            if($col == 'Sixes'){
                $output .= '<tr class="total"><td><strong>Bonus</strong></td><td>'.$bonus.'</td></tr>';
                $output .= '<tr class="total"><td><strong>Total</strong></td><td>'.$calcTopHalf.'</td></tr>';
                $output .= '<tr class="total"><td><strong>Top Total</strong></td><td>'.($calcTopHalf + $bonus).'</td></tr>';
            }
        }
        
        $calcBotHalf = $this->calcBotHalf();
        $gameTotal = $calcTopHalf + $calcBotHalf + $bonus;
        $output .= '<tr class="total"><td><strong>Bottom Total</strong></td><td>'.$calcBotHalf.'</td></tr>';
        $output .= '<tr class="total"><td><strong>Game Total</strong></td><td>'.$gameTotal.'</td></tr>';
        
        $output .= '</tbody></table>';
        
        return $output;
    }
    
    public function updateHolding($dice){
        $this->holding = array_fill(0, 5, 0);
        $num = count($dice);
        
        for($i = 0; $i < $num; $i++){
            $this->holding[$i] = $dice[$i];
        }
    }
    
    public function resetRolls(){
        $this->rollsLeft = 3;
        $this->roll = [];
        $this->holding = [0, 0, 0, 0, 0];
    }
    
    public function rollDice(){
        $this->roll = [];
        $left = $this->__diceToRoll();
        
        for($i = 0; $i < $left; $i++){
            $this->roll[] = rand(1,6);
        }
        
        $this->rollsLeft--;
        
        return $this->roll;
    }
    
    public function getHoldAndRold(){
        return array_merge($this->roll, array_filter($this->holding, function($die){
            return $die > 0;
        }));
    }
    
    public function getRollsLeft(){
        return $this->rollsLeft;
    }
    
    public function getScoreTable(){
        return $this->table;
    }
    
    public function calcTopHalf(){
        $scores = array_slice($this->table, 0, 6);
        $scores = array_sum(array_filter($scores, function($score){
            return $score != -1;
        }));
        
        return $scores;
    }
    
    public function calcBotHalf(){
        $scores = array_slice($this->table, 6);
        $scores = array_sum(array_filter($scores, function($score){
            return $score != -1;
        }));
        
        
        return $scores;
    }
    
    public function getTotalScore(){
        $top = $this->calcTopHalf();
        $bot = $this->calcBotHalf();
        $bon = $top >= 63 ? 35 : 0;
        return $top + $bot + $bon;
    }
    
    public function fullBoard(){
        return count($this->table) == count(array_filter($this->table, function($score){
            return $score != -1;
        }));
    }
    
    protected function __diceToRoll(){
        return 5 - count(array_filter($this->holding, function($die){
            return $die > 0;
        }));
    }
}