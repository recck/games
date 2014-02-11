var numberInt;

$(document).ready(function(){
    numberInt = setInterval(getNextNumber, 5000);
    
    $('.number').on('click', function(){
        var $_ = $(this);
        
        if($_.hasClass('daubed')){
            return;
        }else {
            var num = $_.text();
            $.ajax({
                url: 'bingo.php',
                type: 'POST',
                data: {
                    action: 'daub',
                    daub: num
                },
                dataType: 'json',
                complete: function(e){
                    e = e.responseJSON;
                    console.log(e);
                    if(e.err == true){
                        alert(e.msg);
                    }else {
                        $_.addClass('daubed');
                    }
                }
            });
            
            
        }
    });
    
    $('body').on('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', '.number', function(){
        if($(this).hasClass('rollOut')){
            $('.called > .number').animate({
                left: '+60px'
            }, 500, 'swing', function(e){
                if($('.called > .number:animated').length === 0 && $('.called > .number').length > 5){
                    $('.called > .number').last().remove();
                    $('.called > .number').css('left', 0);
                }
            });
        }
    });
    
    $('button#bingo').on('click', function(){
        $.ajax({
            url: 'bingo.php',
            type: 'POST',
            data: {
                action: 'bingo'
            },
            dataType: 'json',
            complete: function(e){
                e = e.responseJSON;
                var h = parseInt(e.msg.h);
                var v = parseInt(e.msg.v);
                var d = parseInt(e.msg.d);
                var c = parseInt(e.msg.c);
                var num = parseInt(e.msg.num);
                var total = parseInt(e.msg.total);
                
                if(total > 0){
                    alert('You won! You got ' + total + ' bingos in ' + num + ' numbers called!');
                    alert('Horizontals: ' + h + ', Verticals: ' + v + ', Diagonals: ' + d + ', Corners: ' + c);
                    if(confirm('Do you wish to start a new game?')){
                        $.ajax({
                            url: 'bingo.php',
                            type: 'POST',
                            data: {
                                restart: true
                            },
                            dataType: 'json',
                            complete: function(e){
                                window.location.reload();
                            }
                        });
                    }
                    clearInterval(numberInt);
                }else {
                    alert('You did not get a bingo...');
                }
            }
        })
    });
    
    if(init != null){
        for(var i = init.length - 1; i >= 0; i--){
            addNewCalled(init[i]);
        }
    }
});

function addNewCalled(number){
    var letter;
    
    if(number < 1)
        number = 1;
        
    if(number > 75)
        number = 75;
    
    if(number < 16){
        letter = 'b';
    }else
    if(number < 31){
        letter = 'i';
    }else
    if(number < 46){
        letter = 'n';
    }else
    if(number < 61){
        letter = 'g';
    }else {
        letter = 'o';
    }
    
    if($('.called > .number').length >= 5){
        $('.called > .number').last().addClass('rollOut');
    }
    
    $('.called').prepend(
        $('<div></div>').addClass('number animated rollIn ' + letter).text(number)
    );
}

function getNextNumber(){
    $.ajax({
        url: 'bingo.php',
        type: 'POST',
        data: {
            action: 'call'
        },
        dataType: 'json',
        complete: function(e){
            var e = e.responseJSON;
            
            if(e.err){
                alert(e.msg);
                clearInterval(numberInt);
            }else {
                var num = parseInt(e.msg);
                addNewCalled(num);
            }
        }
    });
}