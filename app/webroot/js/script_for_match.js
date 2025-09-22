$(function () {

    $('.th_data_finale').hide();
    $('.th_arbitro').hide();
    $('.td_arbitro').hide();
    $('.th_arbitro2').hide();
    $('.td_arbitro2').hide();
    $('.td_data_finale').hide();
    $('.td_campo').css('min-width', '150px');
    $('.td_gg').css('min-width', '55px');
    $('.td_h').css('min-width', '50px');
    $('.td_goal_c').css('min-width', '60px');
    $('.td_goal_t').css('min-width', '60px');
    $('.td_n_gara').css('min-width', '80px');
    $('.td_g_ta').css('text-align', 'center');
    $('.td_girone').css('text-align', 'center');
    $('.td_nome_g').css('min-width', '87px');


    //$('.td_campo').css('font-size','8px');

    $('td.tools').css('min-width', 70);

    $('.td_manifestazione').css('overflow', 'hidden')
            .css('white-space', 'nowrap')
            .css('text-overflow', 'ellipsis');

    var loc_hash = location.hash;

    if (loc_hash == '#addMode') {

        $('.add').trigger('click');
        location.hash = '';

    }

    $("#print_notes").live('click', function () {

        var l = $(".index-select-checkbox:checked");


        if (l == 0) {

            alert('Selezionare almeno una gara');
            return false;
        }

        var matches = new Array;

        l.each(function () {

            matches.push($(this).val());

        });

        matches = matches.join();

//      console.log(matches);

        $(".page_title").text('Stampa note gara in corso... attendere');
        $.post('/admin/prints/notesnew', {'ids': matches}, function (ret) {



//            location.href = ret; //GIUSEPPE 2024-09-01 apriva due stampe
            
            window.open(ret, '_blank'); //GIUSEPPE 2023-07-25
            
        

//GIUSEPPE 2023-09-18 temporaneo --------------------------------
//            var loc = JSON.parse(ret);
//
//            Object.keys(loc).forEach((i)=>{
//                 window.open(loc[i], '_blank'); 
//            });
//---------------------------------------------------------------
        }, 'html');

    });

    $('.add').click(function () {

        if ($('.view_mode').is(':hidden') == false) {

            location.hash = '#addMode';
            location.reload();

        }

    });

});