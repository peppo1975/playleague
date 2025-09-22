<?= $this->Form->create('AnniSportivi', array('action' => 'add', 'prefix' => 'admin', 'class' => 'formAdd', 'type' => 'file')); ?>

<div class="form_header">

    <h2>Aggiungi nuovo anno sportivo</h2>
    <ul>

        <li><?= $this->Form->submit('reset campi', array('type' => 'reset', 'div' => false)); ?></li>
        <li><?= $this->Form->submit('annulla', array('type' => 'button', 'div' => false, 'id' => 'formReset')); ?></li>
        <li><?= $this->Form->submit('crea', array('type' => 'submit', 'div' => false, 'id' => 'crea')); ?></li>
    </ul>
    <div class="clear"></div>

</div><!-- close form_header -->

<div class="clear"></div>	

<?= $this->Form->input('AnnoSportivo', array('label' => 'Anno Sportivo', 'type' => 'text')); ?>


<div class="clear"></div>
<br>
<!-- //GIUSEPPE 2018-08-29 -->
<b>
    <? //= $this->Form->input('Inizio', array('label' => 'Data Inizio* (gg-mm-aaaa)', 'type' => 'text', 'maxlength' => '10', 'autocomplete' => 'off')); ?>
    <label for="AnniSportiviDataInizio">Data Inizio* (gg-mm-aaaa)</label>
    <input name="data[AnniSportivi][DataInizio]" type="text" maxlength="10" autocomplete="off" id="AnniSportiviDataInizio">
    <div class="error-message" id="resp_data"></div>
</b>
<!-- --------------------- -->
<?= $this->Form->end(); ?>


<!-- //GIUSEPPE 2018-08-29 -->

<script>
    $(document).ready(function () {

        var date;

        $("#crea").attr("disabled", true);

        $("#AnniSportiviDataInizio").keyup(function () {

            date = $("#AnniSportiviDataInizio").val();

            control_date(date);

        })
    });


    function control_date(date)
    {

        $("#crea").attr("disabled", true);

        var resp_data = '';

        if (date.length == 10)
        {

            if (date[2] == '-' && date[5] == '-')
            {
                var res = date.split("-");

                if (res.length == 3)
                {
                    console.log(is_int(res[0]));

                    if (!is_int(res[0]))
                    {
                        resp_data = "il GIORNO deve essere un numero";
                    }
                    else if (!is_int(res[1]))
                    {
                        resp_data = "il MESE deve essere un numero";
                    }
                    else if (!is_int(res[2]))
                    {
                        resp_data = "l' ANNO deve essere un numero";
                    }
                }
                else
                {
                    resp_data = 'La data non coincide con il formato "gg-mm-aaaa"';
                }


            }
            else
            {
                resp_data = 'La data non coincide con il formato "gg-mm-aaaa"';
            }
        }

        $("#resp_data").html(resp_data);

        if ($("#resp_data").html().length == 0 && date.length == 10)
        {
            $("#crea").attr("disabled", false);
        }
    }

    function is_int(n)
    {

        if (!is_numeric(n))
        {
            return false
        }
        else
        {
            return (n % 1 == 0);
        }
    }


    function is_numeric(n)
    {

        return !isNaN(parseFloat(n)) && isFinite(n);

    }

</script>

