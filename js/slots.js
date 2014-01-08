function addLines(lines){
    var count = lines.length;
    
    var rows = $('.spinRow');
    var row1 = rows.eq(0);
    var row2 = rows.eq(1);
    var row3 = rows.eq(2);
    var r1c = row1.find('.color');
    var r2c = row2.find('.color');
    var r3c = row3.find('.color');
    
    if(count > 0){
        for(var i = 0; i < count; i++){
            switch(lines[i]){
                case 1:
                    r2c.each(function(){
                        addBackground($(this), 'horizontal');
                    });
                    break;
                case 2:
                    r1c.each(function(){
                        addBackground($(this), 'horizontal');
                    });
                    break;
                case 3:
                    r3c.each(function(){
                        addBackground($(this), 'horizontal');
                    });
                    break;
                case 4:
                    addBackground(r1c.eq(0), 'horizontal');
                    addBackground(r1c.eq(1), 'horizontal');
                    addBackground(r2c.eq(2), 'diag-left');
                    addBackground(r3c.eq(3), 'horizontal');
                    addBackground(r3c.eq(4), 'horizontal');
                    break;
                case 5:
                    addBackground(r3c.eq(0), 'horizontal');
                    addBackground(r3c.eq(1), 'horizontal');
                    addBackground(r2c.eq(2), 'diag-right');
                    addBackground(r1c.eq(3), 'horizontal');
                    addBackground(r1c.eq(4), 'horizontal');
                    break;
                case 6:
                    addBackground(r1c.eq(0), 'diag-left');
                    addBackground(r2c.eq(1), 'diag-left');
                    addBackground(r3c.eq(2), 'v-up');
                    addBackground(r2c.eq(3), 'diag-right');
                    addBackground(r1c.eq(4), 'diag-right');
                    break;
                case 7:
                    addBackground(r3c.eq(0), 'diag-right');
                    addBackground(r2c.eq(1), 'diag-right');
                    addBackground(r1c.eq(2), 'v-down');
                    addBackground(r2c.eq(3), 'diag-left');
                    addBackground(r3c.eq(4), 'diag-left');
                    break;
                case 8:
                    addBackground(r2c.eq(0), 'diag-right');
                    addBackground(r1c.eq(1), 'horizontal');
                    addBackground(r1c.eq(2), 'horizontal');
                    addBackground(r1c.eq(3), 'horizontal');
                    addBackground(r2c.eq(4), 'diag-left');
                    break;
                case 9:
                    addBackground(r2c.eq(0), 'diag-left');
                    addBackground(r3c.eq(1), 'horizontal');
                    addBackground(r3c.eq(2), 'horizontal');
                    addBackground(r3c.eq(3), 'horizontal');
                    addBackground(r2c.eq(4), 'diag-right');
                    break;
                case 10:
                    addBackground(r1c.eq(0), 'diag-left');
                    addBackground(r2c.eq(1), 'horizontal');
                    addBackground(r2c.eq(2), 'horizontal');
                    addBackground(r2c.eq(3), 'horizontal');
                    addBackground(r1c.eq(4), 'diag-right');
                    break;
                case 11:
                    addBackground(r3c.eq(0), 'diag-right');
                    addBackground(r2c.eq(1), 'horizontal');
                    addBackground(r2c.eq(2), 'horizontal');
                    addBackground(r2c.eq(3), 'horizontal');
                    addBackground(r3c.eq(4), 'diag-left');
                    break;
                case 12:
                    addBackground(r1c.eq(0), 'diag-left');
                    addBackground(r2c.eq(1), 'v-up');
                    addBackground(r1c.eq(2), 'v-down');
                    addBackground(r2c.eq(3), 'v-up');
                    addBackground(r1c.eq(4), 'diag-right');
                    break;
                case 13:
                    addBackground(r3c.eq(0), 'diag-right');
                    addBackground(r2c.eq(1), 'v-down');
                    addBackground(r3c.eq(2), 'v-up');
                    addBackground(r2c.eq(3), 'v-down');
                    addBackground(r3c.eq(4), 'diag-left');
                    break;
                case 14:
                    addBackground(r2c.eq(0), 'diag-right');
                    addBackground(r1c.eq(1), 'v-down');
                    addBackground(r2c.eq(2), 'v-up');
                    addBackground(r1c.eq(3), 'v-down');
                    addBackground(r2c.eq(4), 'diag-left');
                    break;
                case 15:
                    addBackground(r2c.eq(0), 'diag-left');
                    addBackground(r3c.eq(1), 'v-up');
                    addBackground(r2c.eq(2), 'v-down');
                    addBackground(r3c.eq(3), 'v-up');
                    addBackground(r2c.eq(4), 'diag-right');
                    break;
            }
        }
    }
}

function addBackground(box, line){
    if(box.hasClass('all')){
        return;
    }
    
    switch(line){
        case 'v-up':
            if(box.hasClass('horizontal')){
                box.removeClass('horizontal').addClass('horizontal-v-up');
            }else
            if(box.hasClass('v-down')){
                box.removeClass('v-down').addClass('diag-both');
            }
            else {
                box.addClass('v-up');
            }
            break;
        case 'v-down':
            if(box.hasClass('horizontal')){
                box.removeClass('horizontal').addClass('horizontal-v-down');
            }else 
            if(box.hasClass('v-up')){
                box.removeClass('v-up').addClass('diag-both');
            }
            else{
                box.addClass('v-down');
            }
            break;
        case 'horizontal':
            if(box.hasClass('v-up')){
                box.removeClass('v-up').addClass('horizontal-v-up');
            }else
            if(box.hasClass('v-down')){
                box.removeClass('v-down').addClass('horizontal-v-down');
            }else
            if(box.hasClass('diag-left') && box.hasClass('diag-right')){
                box.removeClass('diag-left').removeClass('diag-right').addClass('all');
            }else
            if(box.hasClass('diag-left')){
                box.removeClass('diag-left').addClass('horizontal-diag-left');
            }else
            if(box.hasClass('diag-right')){
                box.removeClass('diag-right').addClass('horizontal-diag-right');
            }else {
                box.addClass('horizontal');
            }
            break;
        case 'diag-left':
            if(box.hasClass('diag-right') && box.hasClass('horizontal')){
                box.removeClass('diag-right').removeClass('horizontal').addClass('all');
            }else
            if(box.hasClass('diag-right')){
                box.removeClass('diag-right').addClass('diag-both');
            }else
            if(box.hasClass('horizontal')){
                box.removeClass('horizontal').addClass('horizontal-diag-left');
            }else {
                box.addClass('diag-left');
            }
            break;
        case 'diag-right':
            if(box.hasClass('diag-left') && box.hasClass('horizontal')){
                box.removeClass('diag-left').removeClass('horizontal').addClass('all');
            }else
            if(box.hasClass('diag-left')){
                box.removeClass('diag-left').addClass('diag-both');
            }else
            if(box.hasClass('horizontal')){
                box.removeClass('horizontal').addClass('horizontal-diag-right');
            }else {
                box.addClass('diag-right');
            }
            break;
    }
    
    if(!box.hasClass('line')){
        box.addClass('line');
    }
}