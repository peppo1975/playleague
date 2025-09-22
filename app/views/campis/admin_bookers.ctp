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
        max-width: 360px;
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

    #blackListCheck{
        margin-left: 20px;
    }

    label{
        font-weight: bold;
        padding-top: 3px;
    }


    /* // GIUSEPPE ---------------------------  */

    .black-list-row{
        color: white; 
        background-color: red;
    }

    .edited-row{
        /*color: white;*/ 
        background-color: greenyellow;
    }

    .blacklisted-row{
        /*color: white;*/ 
        background-color: orange;
    }

</style>

<!--date("W", mktime(0, 0, 0, 9, 5, 2020));-->

<!--
mktime(
    int $hour,
    ?int $minute = null,
    ?int $second = null,
    ?int $month = null,
    ?int $day = null,
    ?int $year = null
): int|false
-->


<div style="padding: 30px;">

    <h1>Tabella noleggiatori</h1>

    <hr>

    <div class="capis-table-filter">
        <!--check-->
        <div class="row" >
            <div class="w4-col s4 sport-selection" style="display: flex;">
                <input id="searchBooker" placeholder="Cerca noleggiatore">

                <input type="checkbox" name="bl" id="blackListCheck" value="1"> 
                <label for="vehicle1">Solo noleggiatori in blackList</label>
            </div>

        </div>

        <div class="clear"></div>

        <div class="row">

            <div class="col-lg">

            </div>

        </div>
        <div class="row" style="margin: 10px 0px 0px 0px">
            <div class="col-lg " id="response" style="width: 100%; float:left">
                <img src="https://media2.giphy.com/media/3oEjI6SIIHBdRxXI40/200.gif" alt="alt"/>
            </div>
        </div>

    </div>
</div>

<? // require 'admin_bookers_form_insert.ctp'; ?>

<script>

    $(function ()
    {
        var link = "/admin/Campis/tableBookers";
        searchBookers("");
        $("#searchBooker").keyup(function ()
        {
            var filter = $(this).val();
            console.log($("#blackListCheck").is(':checked'));
            if ($("#blackListCheck").is(':checked'))
            {
                searchBookers(filter, 1);
            }
            else
            {
                searchBookers(filter);
            }
        });
        $("#blackListCheck").change(function ()
        {
            var filter = $("#searchBooker").val();
            if ($(this).is(':checked'))
            {
                searchBookers(filter, 1);
            }
            else
            {
                searchBookers(filter);
            }
        });
        $(".confirmEdit").click(function ()
        {
            alert("TEST");
        })

        /* ------------------------------------------------- */

        function searchBookers(filter, blackList = 0)
        {
            to_send = {};
            to_send['filter'] = filter;
            to_send['blackList'] = blackList;
            $.post(link, to_send, function (data)
            {
                console.log(data);
                $("#response").html(data);
                // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

                bookersTable = document.getElementsByClassName("bookersTable");
                for (var i = 0; i < bookersTable.length; i++)
                {
                    bookersTable[i].addEventListener('click', Myf, false);
                }

                function Myf()
                {
                    var booker_id = this.getAttribute('booker_id');
                    $(".bookerEdit_" + booker_id).toggle('fast');
                }


                // confirm
                var confirmEdit = document.getElementsByClassName("confirmEdit");
                for (var i = 0; i < confirmEdit.length; i++)
                {
                    confirmEdit[i].addEventListener('click', MyConfirmEdit, false);
                }


                // delete
                var confirmDelete = document.getElementsByClassName("confirmDelete");
                for (var i = 0; i < confirmDelete.length; i++)
                {
                    confirmDelete[i].addEventListener('click', MyConfirmDelete, false);
                }

                function MyConfirmEdit()
                {
                    var id_edit = this.getAttribute('id_edit');
                    // alert(id_edit);

                    var cognomeEdit = document.getElementsByClassName("Cognome_" + id_edit);
                    var nomeEdit = document.getElementsByClassName("Nome_" + id_edit);
                    var emailEdit = document.getElementsByClassName("Email_" + id_edit);
                    var telefonoEdit = document.getElementsByClassName("Telefono_" + id_edit);
                    var blacklistEdit = document.getElementsByClassName("Blacklist_" + id_edit);
                    //alert(cognomeEdit[0].value + " " + nomeEdit[0].value);

                    cognomeEdit = cognomeEdit[0].value;
                    nomeEdit = nomeEdit[0].value;
                    emailEdit = emailEdit[0].value;
                    telefonoEdit = telefonoEdit[0].value;
                    blacklistEdit = blacklistEdit[0].checked;
                    var to_send = {};
                    to_send['editBooker'] = {};
                    to_send['editBooker']['Booker'] = id_edit;
                    to_send['editBooker']['Cognome'] = cognomeEdit;
                    to_send['editBooker']['Nome'] = nomeEdit;
                    to_send['editBooker']['Email'] = emailEdit;
                    to_send['editBooker']['Telefono'] = telefonoEdit;
                    to_send['editBooker']['Blacklist'] = blacklistEdit ? '1' : '0';
                    var link = "/admin/Campis/editBooker";

                    $.post(link, to_send, function (data)
                    {
                        console.log(data);
//                        document.getElementsByClassName("Cognome_" + id_edit)[0].value = "Test";

                        if (data['response'] == true)
                        {
                            var Booker = data['data']['Booker'];
                            delete data['data']['Booker'];

                            var Blacklist = data['data']['Blacklist'];
                            delete data['data']['Blacklist'];

                            for (key in data['data'])
                            {
                                var value = data['data'][key];
                                var label = `label${key}_${Booker}`;
                                document.getElementsByClassName(label)[0].innerHTML = value;
                            }




                            var elementRow = document.getElementById(`bookersTableRow_${Booker}`);
                            document.getElementsByClassName(`labelBlacklist_${Booker}`)[0].innerHTML = "";

                            elementRow.classList.remove("black-list-row");
                            elementRow.classList.remove("blacklisted-row");
                            elementRow.classList.remove("edited-row");

                            if (parseInt(Blacklist) == 0)
                            {
                                //elementRow.classList.remove("black-list-row");
                                elementRow.classList.add("edited-row");
                            }
                            else if (parseInt(Blacklist) == 1)
                            {
                                elementRow.classList.add("blacklisted-row");
                                document.getElementsByClassName(`labelBlacklist_${Booker}`)[0].innerHTML = "Blacklist";
                            }


                            $.post("/apis/aggiornaBookersNewsLetters", function (data)
                            {
                                console.log(data);
                            });
                        }
                        else
                        {
                            alert("Problemi nel salvataggio \nProbabilmente la mail è assegnata ad un altro utente");
                        }

                        $(".bookerEdit_" + Booker).toggle('fast');

                    }, 'json');
                }


                function MyConfirmDelete()
                {
                    var id_delete = this.getAttribute('id_delete');
                    var cognome = this.getAttribute('cognome');
                    var nome = this.getAttribute('nome');
                    var email = this.getAttribute('email');

                    if (confirm("Sei sicuro di voler eliminare il noleggiatore:\n" + cognome + " " + nome + "\n" + email + "?"))
                    {
                        var link = "/admin/Campis/deleteBooker";

                        to_send = {};
                        to_send['deleteBooker'] = id_delete;

                        $.post(link, to_send, function (data)
                        {
                            console.log(data);

                            $("#searchBooker").trigger('keyup');

                            $.post("/apis/aggiornaBookersNewsLetters", function (data)
                            {
                                console.log(data);
                            });

                        }, 'json');
                    }
                    else
                    {

                    }
                    ;
                }

            });
        }

    });
</script>










