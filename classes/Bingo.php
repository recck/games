<?php

class Bingo {
    private $called;
    private $board;
    private $daubed;
    private $last;
    
    public function __construct(){
        $range = range(1, 75);
        $chunks = array_chunk($range, 15);
        
        $this->board = [];
        
        foreach($chunks AS $chunk){
            shuffle($chunk);
            $this->board = array_merge($this->board, array_slice($chunk, 0, 5));
        }
        
        $this->board[12] = 'D';
        
        $this->last = time();
    }
    
    public function __toString(){
        $output = '';
        $bingo = str_split('BINGO');
        
        $board = $this->getBoard();
        
        $i = 0;
        foreach($board AS $column){
            $output .= '<div class="bingo-column"><div class="number letter">'.$bingo[$i].'</div>';
            foreach($column AS $number){
                $daubed = $number == 'D' ? ' daubed' : '';
                $output .= '<div class="number'.$daubed.'">'.$number.'</div>';
            }
            $output .= '</div>';
            $i++;
        }
        
        return $output . '<div class="clearfix"></div>';
    }
    
    public function getBoard(){
        return array_chunk($this->board, 5);
    }
    
    public function getCalled(){
        return $this->called;
    }
    
    public function getLastFive(){
        $called = array_slice(array_reverse($this->called), 0, 5);
        return json_encode($called);
    }
    
    public static function pre($array){
        echo '<pre>' . print_r($array, true) . '</pre>';
    }
    
    public function daub($number){
        foreach($this->board AS $key => $num){
            if($num == $number){
                $this->board[$key] = 'D';
                return;
            }
        }
    }
    
    public function managePost(){
        $action = $_POST['action'];
        $output = [];
        
        switch($action){
            case 'daub':
                if(empty($_POST['daub'])){
                    $output['err'] = true;
                    $output['msg'] = 'No number sent!';
                }else {
                    $daub = (int) $_POST['daub'];
                    
                    if($daub < 1 || $daub > 75){
                        $output['err'] = true;
                        $output['msg'] = 'Invalid number!';
                    }else {
                        if(!in_array($daub, $this->called)){
                            $output['err'] = true;
                            $output['msg'] = 'This number has not been called!';
                        }else {
                            $output['err'] = false;
                            $this->daub($daub);
                        }
                    }
                }
                break;
            case 'call':
                if(count($this->called) == 75){
                    $output['err'] = true;
                    $output['msg'] = 'All the numbers have been called!';
                }else {
                    $i = rand(1, 75);
                    while(in_array($i, $this->called)){
                        $i = rand(1, 75);
                    }
                    
                    $this->called[] = $i;
                    
                    $output['err'] = false;
                    $output['msg'] = $i;
                }
                break;
            case 'bingo':
                $output['err'] = false;
                $output['msg'] = $this->hasBingo();
                break;
        }
        
        return json_encode($output);
    }
    
    public function hasBingo(){
        $board = $this->getBoard();
        
        $bingos = [
            'h' => 0,
            'v' => 0,
            'd' => 0,
            'c' => 0
        ];
        
        // check verticals
        foreach($board AS $column){
            if(implode($column) === 'DDDDD'){
                $bingos['v']++;
            }
        }
        
        // check horizontals
        for($i = 0; $i < 5; $i++){
            $out = '';
            for($j = 0; $j < 5; $j++){
                $out .= $board[$j][$i];
            }
            
            if($out === 'DDDDD'){
                $bingos['h']++;
            }
        }
        
        $diags = [
            $board[0][0] . $board[1][1] . $board[2][2] . $board[3][3] . $board[4][4],
            $board[0][4] . $board[1][3] . $board[2][2] . $board[3][1] . $board[4][0]
        ];
        
        foreach($diags AS $diag){
            if($diag === 'DDDDD'){
                $bingos['d']++;
            }
        }
        
        $corners = $board[0][0] . $board[4][0] . $board[0][4] . $board[4][4];

        if($corners === 'DDDD'){
            $bingos['c'] = 1;
        }
        
        $bingos['total'] = array_sum($bingos);
        $bingos['num'] = count($this->called);
        
        return $bingos;
    }
}