<?= $this->element('/backend/tab_scripts'); ?>

<?= $this->Form->create('NewsletterUser', array('action' => 'add', 'prefix' => 'admin', 'class' => 'formAdd', 'type' => 'file')); ?>

<div class="form_header">

    <h2>Aggiungi nuovo utente newsletter</h2>
    <ul>

        <li><?= $this->Form->submit('reset campi', array('type' => 'reset', 'div' => false)); ?></li>
        <li><?= $this->Form->submit('annulla', array('type' => 'button', 'div' => false, 'id' => 'formReset')); ?></li>
        <li><?= $this->Form->submit('crea', array('type' => 'submit', 'div' => false)); ?></li>
    </ul>
    <div class="clear"></div>

</div><!-- close form_header -->

<div class="clear"></div>

<div class="tab-container">

    <ul class="tab-selector">
        <li data-index="1" class="selected"><a href="javascript:;">Utente</a></li>
        <li data-index="2"><a href="javascript:;">Gruppi</a></li>

        <!--//GIUSEPPE 2020-09-01-->
        <li data-index="3"><a href="javascript:;">Inserisci da Excel</a></li> 
        <!--*********************-->

    </ul>

    <div class="tab-page tab-selected" data-index="1">		

        <?= $this->Form->input('email', array('label' => 'Email', 'type' => 'text')); ?>

        <div class="clear"></div>	

        <?= $this->Form->input('name', array('label' => 'Nome', 'type' => 'text')); ?>

        <?= $this->Form->input('surname', array('label' => 'Cognome', 'type' => 'text')); ?>

        <div class="clear"></div>	

        <?= $this->Form->input('company', array('label' => 'Compagnia', 'type' => 'text')); ?>

        <?= $this->Form->input('piva', array('label' => 'P.IVA', 'type' => 'text')); ?>

        <div class="clear"></div>	

        <?= $this->Form->input('city', array('label' => 'Citt&agrave', 'type' => 'text')); ?>

        <?= $this->Form->input('address', array('label' => 'Indirizzo', 'type' => 'text')); ?>

        <div class="clear"></div>	

        <?= $this->Form->input('tel', array('label' => 'Telefono (casa)', 'type' => 'text')); ?>

        <?= $this->Form->input('cel', array('label' => 'Cellulare', 'type' => 'text')); ?>

        <?= $this->Form->input('fax', array('label' => 'Fax', 'type' => 'text')); ?>

        <div class="clear"></div>	

    </div>

    <div class="tab-page" data-index="2">

        <h3>Inserisci da Excel</h3>

        <ul class="tag_list">

            <? foreach ($groups as $group): ?>

                <li>
                    <?= $this->Form->checkbox('group_' . $group['NewsletterGroup']['id'], array('value' => $group['NewsletterGroup']['id'], 'hiddenField' => false)); ?>
                    <?= $group['NewsletterGroup']['title']; ?>
                </li>

            <? endforeach; ?>

        </ul>

    </div>



    <?= $this->Form->end(); ?>  





    <!-- //GIUSEPPE 2020-09-01*********************************************************-->
    <div class="tab-page" data-index="3">

        <h3>Lista gruppi</h3>

        <ul class="tag_list">
            <table>
                <thead>
                    <tr>
                        <th >ID</th>
                        <th >Nome gruppo</th>

                    </tr>
                </thead>
                <tbody>
                    <? foreach ($groups as $group): ?>
                        <tr>
                            <td style="text-align: right">
                                <strong><?= $group['NewsletterGroup']['id'] ?></strong>
                            </td>
                            <td>
                                <?= $group['NewsletterGroup']['title']; ?>
                            </td>
                        </tr>

                    <? endforeach; ?>
                </tbody>
            </table>


        </ul>

        <div class="clear"></div>

        <form method="post" action="" enctype="multipart/form-data"id="myform"> 
            <? $message_link = " scarica modello excel " ?>
            <hr>

            <h2>Inserici dati da excel</h2> 

            <div class="clear"></div>

            <p>Clicca su <b><?= $message_link ?></b> e compila i campi. Terminata la compilazione esegui l'upload del file</p>

            <div class="clear"></div>
            <br>
            <div> 
                <a href="/download/newsletter_users.xlsx" target="_blank"><?= $message_link ?></a>&emsp;
                <input type="file" id="file" name="file" /> 
                <input type="button" class="button" value="Upload" id="but_upload"> 

            </div> 



        </form> 
        <div class="clear"></div>

        <input type="button" class="button" value="SALVA" id="but_save" style="display: none"> 

        <div class="clear"></div>

        <div id="response">

        </div>

        <script type="text/javascript">
            $(document).ready(function ()
            {
                $("#but_upload").click(function ()
                {


                    var fd = new FormData();
                    var files = $('#file')[0].files[0];
                    fd.append('file', files);

                    $.ajax({
                        url: 'read_newsletter_users_xlsx',
                        type: 'post',
                        data: fd,
                        contentType: false,
                        processData: false,
                        success: function (response)
                        {
                            //console.log(response);
                            res = JSON.parse(response);
                            $("#but_save").show('fast');
                            $("#response").html(res['table']);

                            return;


                        },
                    });
                });
            });


            $("#but_save").click(function ()
            {
                $.post("/admin/newsletter_users/save_users_groups", function (data)
                {
//                    console.log(data);
                    alert('Operazione andata a buon fine');
                    $("#but_save").hide('fast');
                    $("#response").html('');

                    return;
                });
            });
        </script> 

    </div>
    <!--***************************************************-->