<style>
    .index_table{
        margin-bottom: 20px;
    }

    h3.result-value{
        margin-bottom: 10px;
        border-bottom: 0;
    }

    table.index_table tr td{
        padding: 8px 5px;
        font-size: 13px;
    }

    table.index_table tr td.bookers-surname,
    table.index_table tr td.bookers-name{
        text-transform: capitalize;
    }


    /* table.index_table tr:nth-child(even)  {
        background-color: #fff !important;
    }

    table.index_table tr:nth-child(odd) {
        background-color: #eee !important;
    } */

     table.index_table tr td.bookers-email{
        text-transform: lowercase;
    }

    .bookers-data{
        background-color: #fafafa;
        padding: 30px;
    }

    .bookers-data label{
        font-size: 13px;
        padding-bottom: 5px;
    }

    .bookers-data .confirmEdit{
        padding: 5px 10px;
        font-size: 12px;
        color: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        background: #6fb406;
        cursor: pointer;
        text-transform: uppercase;
        margin: 0 20px 0 0;
    }

    .bookers-data .confirmEdit:hover{
        background: #548b00;
    }   

    .bookers-data .confirmDelete{
        padding: 5px 10px;
        font-size: 12px;
        color: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        background: #D2312D;
        cursor: pointer;
        text-transform: uppercase;
    }

    .bookers-data .confirmDelete:hover{
        background: #ff3e39;
    }

    input.in_cognomeBooker,
    input.in_nomeBooker {
        text-transform: capitalize;
    }

    input.in_emailBooker{
        text-transform: lowercase;
    }


</style>

<? ob_start() ?>


<h3 class="result-value">
    <?= count($listBookers) ?> noleggiatori trovati
</h3>

<? // print_r($listBookers) ?>
<table class="campis-table index_table">
    <tbody>
        <tr class="bookers-theader">
            <th>Cognome</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Telefono</th>
            <th>Blacklist</th>
        </tr>
        <? foreach ($listBookers as $champ => $value): ?>

            <tr class="table-header bookersTable <?= $value['Blacklist'] == 0 ? "" : "black-list-row" ?>" 
                booker_id="<?= $value['Booker'] ?>" 
                id="bookersTableRow_<?= $value['Booker'] ?>"  >

                <td class="cella bookers-surname">
                    <span class="book-day labelCognome_<?= $value['Booker'] ?>"><?= $value['Cognome'] ?></span>
                </td>
                <td class="cella bookers-name">
                    <span class="book-day labelNome_<?= $value['Booker'] ?>"><?= $value['Nome'] ?></span>
                </td>
                <td class="cella bookers-email">
                    <span class="book-day labelEmail_<?= $value['Booker'] ?>"><?= $value['Email'] ?></span>
                </td>
                <td class="cella bookers-tel">
                    <span class="book-day labelTelefono_<?= $value['Booker'] ?>"><?= $value['Telefono'] ?></span>
                </td>
                <td class="cella bookers-blacklist">
                    <span class="book-day labelBlacklist_<?= $value['Booker'] ?>"><?= $value['Blacklist'] == 0 ? "" : "BlackList" ?></span>
                </td>
            </tr>

            <tr class="bookerEdit_<?= $value['Booker'] ?>" style="display: none" >
                <td colspan="5">

                            <div class="bookers-data">
                                <label>Cognome</label><input class="in_cognomeBooker Cognome_<?= $value['Booker'] ?>" size="50" value="<?= $value['Cognome'] ?>"><br><br>
                                <label>Nome</label><input class="in_nomeBooker Nome_<?= $value['Booker'] ?>" size="50" value="<?= $value['Nome'] ?>"><br><br>
                                <label>Email</label><input class="in_emailBooker Email_<?= $value['Booker'] ?>" size="50" value="<?= $value['Email'] ?>"><br><br>
                                <label>Telefono</label><input class="in_telBooker Telefono_<?= $value['Booker'] ?>" size="50" value="<?= $value['Telefono'] ?>"><br><br>
                                <input type="checkbox" class="Blacklist_<?= $value['Booker'] ?>"  <?= $value['Blacklist'] == 0 ? "" : "checked" ?>  name="bl" id="blackListCheck" value="1"> <label for="vehicle1"  style="  float: left;">BlackList</label><br>
                                <hr>
                                <button  id_edit ="<?= $value['Booker'] ?>" class="confirmEdit">
                                    Salva e chiudi
                                </button>
                                <button  id_delete ="<?= $value['Booker'] ?>" 
                                         cognome="<?= $value['Cognome'] ?>" 
                                         nome="<?= $value['Nome'] ?>" 
                                         email="<?= $value['Email'] ?>"
                                         class="confirmDelete">
                                    Cancella noleggiatore
                                </button>
                            </div>

                </td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>


<? $html = ob_get_clean() ?>










