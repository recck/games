<!doctype>
<html>
<head>
    <title>Freakzee :: for PHPFreaks by Marcus Recck</title>
    <link rel="stylesheet" type="text/css" href="css/freakzee.css" />
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    
    <script>
    $(document).ready(function(){
        $('.die').on('click', function(){
            var className = $(this).parent().attr('class');
            
            $('img', $(this)).toggleClass('bordered');
            
            var element = $(this).detach();
            if(className == 'dice'){
                $('.hold').append(element);
            }else {
                $('.dice').append(element);
            }
        });
        
        $('#holdAndRoll').on('click', function(){
            var holding = [];
            $('.bordered').each(function(){
                holding.push($(this).data('die'));
            });
            $('#hold').val(holding.join(','));
            $('form.newGame').submit();
        });
        
        $('#nextTurn').on('click', function(){
            return confirm('Did you mark off your score first?');
        });
    });
    </script>
</head>
<body>

<div id="game">
    <h1 class="heading">Freakzee</h1>