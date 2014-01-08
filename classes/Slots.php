<?php

/**
 * Colors:
 *  X = GOLD    1/8
 *  Y = YELLOW  
 *  R = RED
 *  B = BLUE
 *  G = GREEN
 *  P = PURPLE
 *  O = ORANGE
 *  Z = PINK
 */

class Slots {
    const LINES = 15;
    const ROWS = 3;
    const COLS = 5;
    private $tokens, $spins, $bet, $colors;
    
    private $possible = [
        'XXXXX' => 1000, 'XXXX' => 300, 'XXX' => 100,
        'Y[YZ]{4}' => 300, 'Y[YZ]{3}' => 150, 'Y[YZ]{2}' => 50,
        'R[RZ]{4}' => 150, 'R[RZ]{3}' => 75, 'R[RZ]{2}' => 25,
        'B[BZ]{4}' => 75, 'B[BZ]{3}' => 50, 'B[BZ]{2}' => 20,
        'G[GZ]{4}' => 50, 'G[GZ]{3}' => 15, 'G[GZ]{2}' => 5,
        'P[PZ]{4}' => 40, 'P[PZ]{3}' => 13, 'P[PZ]{2}' => 5,
        'O[OZ]{4}' => 35, 'O[OZ]{3}' => 10, 'O[OZ]{2}' => 3
    ];
    
    public function __construct($bet = 20){
        $this->tokens = 10000;
        $this->spins = 0;
        $this->bet = $bet;
        $this->colors = str_split(
            str_repeat('X', 50) . str_repeat('Y', 100) . str_repeat('R', 200) .
            str_repeat('B', 200) . str_repeat('G', 200) . str_repeat('P', 300) .
            str_repeat('O', 300) . str_repeat('Z', 150)
        );
    }
    
    public function __get($var){
        return isset($this->$var) ? $this->$var : 0;
    }
    
    public function error($msg){
        return [
            'error' => true,
            'message' => '<h1 class="error">' . $msg . '</h1>'
        ];
    }
    
    public function canSpin(){
        return $this->tokens >= ($this->bet * self::LINES);
    }
    
    public function payout($spin){
        list($row1, $row2, $row3) = $spin;
        
        $lines = [
            implode($row2),
            implode($row1),
            implode($row3),
            $row1[0] . $row1[1] . $row2[2] . $row3[3] . $row3[4],
            $row3[0] . $row3[1] . $row2[2] . $row1[3] . $row1[4],
            $row1[0] . $row2[1] . $row3[2] . $row2[3] . $row1[4],
            $row3[0] . $row2[1] . $row1[2] . $row2[3] . $row3[4],
            $row2[0] . $row1[1] . $row1[2] . $row1[3] . $row2[4],
            $row2[0] . $row3[1] . $row3[2] . $row3[3] . $row2[4],
            $row1[0] . $row2[1] . $row2[2] . $row2[3] . $row1[4],
            $row3[0] . $row2[1] . $row2[2] . $row2[3] . $row3[4],
            $row1[0] . $row2[1] . $row1[2] . $row2[3] . $row1[4],
            $row3[0] . $row2[1] . $row3[2] . $row2[3] . $row3[4],
            $row2[0] . $row1[1] . $row2[2] . $row1[3] . $row2[4],
            $row2[0] . $row3[1] . $row2[2] . $row3[3] . $row2[4]
        ];
        
        $count = count($this->possible) / 3;
        $p = array_keys($this->possible);
        $payout = 0;
        
        $lineNum = 1;
        $lineWon = [];
        foreach($lines AS $line){
            for($i = 0; $i < $count; $i++){
                $test = [$p[$i * 3], $p[$i * 3 + 1], $p[$i * 3 + 2]];
                foreach($test AS $regex){
                    $regexTest = '/^' . $regex . '/i';
                    if(preg_match($regexTest, $line)){
                        $payout += $this->possible[$regex];
                        $lineWon[] = $lineNum;
                        break;
                    }
                }
            }
            $lineNum++;
        }
        
        $this->tokens -= $this->bet * self::LINES;
        
        return ['payout' => $payout * $this->bet, 'lines' => json_encode($lineWon)];
    }
    
    public function addTokens($tokens){
        $this->tokens += $tokens;
    }
    
    public function spin(){
        $bet = $this->bet * self::LINES;
        
        if($bet > $this->tokens){
            return $this->error('Not enough tokens to spin! <a href="slots.php?restart">Restart?</a>');
        }else {
            $output = [];
            
            for($i = 0; $i < self::ROWS; $i++){
                for($j = 0; $j < self::COLS; $j++){
                    $output[$i][$j] = $this->colors[array_rand($this->colors)];
                }
            }
            $this->spins++;
            
            return $output;
        }
    }
    
    public function build($spin){
        $output = '<div class="spinHolder">' . "\n";
        $output .= "\t\t" . '<div class="spinRow">' . "\n";
        
        $rowNum = 0;
        
        foreach($spin AS $row => $col){
            foreach($col AS $color){
                $output .= "\t\t\t" . '<div class="color color-'.strtolower($color).'"></div><!-- color -->' . "\n";
            }
            $output .= "\t\t" . '</div><!-- spinRow -->' . "\n\t\t";
            $rowNum++;
            if($rowNum < self::ROWS){
                $output .= '<div class="spinRow">' . "\n";
            }
        }
        
        $output .= '</div><!-- spinHolder -->';
        return $output;
    }
}