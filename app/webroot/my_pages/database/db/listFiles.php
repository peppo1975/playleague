<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">


        <script>
            $('document').ready(function () {
                var totale = $('#fileTable tbody tr').length;
                $('#totale').text(totale);
                $('#fileTable th:not(".no-order")').click(function () {
                    var tableHeaderIndex = $(this).index();
                    var order = $(this).attr('data-order') === 'asc' ? 'desc' : 'asc';
                    $(this).attr('data-order', order);
                    $(this).siblings().attr('data-order', '');
                    tinysort(
                            '#fileTable tbody tr', {
                                selector: 'td:nth-child(' + (tableHeaderIndex + 1) + ')',
                                data: 'ordina',
                                order: order
                            }
                    );
                });
            });
        </script> 

    </head>
    <body>
        <table id="fileTable">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Nome</th>
                    <th>Dim.</th>
                    <th class="no-order">&darr;</th>
                </tr>
            </thead>
            <tbody>
                <?php
                /* ===============================================================
                  Author's custom code: http://quellidelcucuzzolo.blogspot.it
                  Please do not remove credit
                  ============================================================== */
                foreach (glob("../db/*.gz", GLOB_BRACE) as $filename)
                {
                    $nomefile = pathinfo($filename); //array contenente nome, estensione e percorso del file
                    $timefile = filemtime($filename); //data in timestamp
                    $modifica = date("d/m/Y", $timefile); //data in formato dd/mm/yyyy
                    $peso = round(filesize($filename) / 1024); //dimensioni del file arrotondate ai KB
                    echo "<tr>
           <td data-ordina='$timefile'>$modifica</td>
           <td class='nomefile' data-ordina='$nomefile[basename]'>$nomefile[basename]</td>
           <td class='size' data-ordina='$peso'>$peso KB</td>
           <td>
             <a href='$filename' title='Scarica il file' download><img src='img/d-icon.png' alt='icona download' /></a>
           </td>
         </tr>
         ";
                }
                ?>
            </tbody>
        </table>
    </body>
</html>


<!-- https://quellidelcucuzzolo.blogspot.com/2016/05/php-elenco-file-dinamico-ordinabile.html?m=1 -->