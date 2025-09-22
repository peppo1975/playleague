
<script src="http://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<link rel="stylesheet" href="http://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

<style>
    .intro {
        /*background-color: greenyellow;*/
    }
    .pointer {
        cursor: pointer
    }

    .sport-selection,
    .new_hour{
        display: flex;
        padding: 10px 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        margin-top: 10px;
        background: aliceblue;
    }

    .sport-selection{
        max-width: 300px;
    }

    .new_hour{
        max-width: 600px;
    }

    .capis-table-filter h2{
        padding-top: 10px;
        padding-left: 20px;
    }

    #from-to{
        padding: 5px 0;
        font-size: 14px;
    }

    .button-row{
        margin: 15px 0 25px;
        padding-left: 20px;
    }

    .error-input{
        border: 2px solid red;
    }



    .not-exist{
        color: red;
        font-weight: bold;
        background-color: yellow;
    }
    .booked{
        color: red;
        font-weight: bold;
        background-color: yellow;
    }


</style>


<div style="margin: 10px 10px 10px 10px">

    <h1><?= $NomeCampionato ?></h1>

    <hr>

    <div class="capis-table-filter">
        <h2>Seleziona le squadre</h2>

        <!--check-->
        <div class="row" >






        </div>


        <div>
            <div class="tab-page tab-selected" data-index="2">
                <br>
                <h4><input type="checkbox" id="check-squadre" name="" value=""> Seleziona tutte</h4>

                <form action="/admin/campionatis/pdfLiberatoria/<?= $campionato ?>" target="_blank" method="POST">
                    <? foreach ($view as $key => $squadra): ?>
                        <div class="new_hour">
                            <h4><input type="checkbox" class="check-squadre" name="<?= $squadra['SquadraCampionato'] ?>" value="<?= $squadra['SquadraCampionato'] ?>"> <?= $squadra['NomeSquadra'] ?></h4>
                        </div>
                    <? endforeach; ?>
                    <hr>
                    <input type="submit" value="CREA PDF">
                </form>

            </div> 
        </div>

        <div class="clear"></div>

        <div class="row">

            <div class="col-lg">

            </div>

        </div>

    </div>
</div>



<script>

    $(() =>
    {
        $("#check-squadre").click(function ()
        {
            //alert("test");
            is_checked = $(this)['context']['checked'];

            $(".check-squadre").attr("checked", is_checked);
//            console.log(is_checked);
        });

    });
</script>







