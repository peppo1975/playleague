
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

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

    <h1>Gestione calendari - Load calendario</h1>

    <hr>

    <div class="capis-table-filter">
        <h2>Load file excel</h2>

        <!--check-->
        <div class="row" >
            <div class="w3-col s4 sport-selection" style="display: flex;">



                <!--<form action="/file-upload" class="dropzone">-->
                <form action="/matches/loadExcelCalendar" class="dropzone" id="load-xlsx-file">
                    <div class="fallback">
                        <input name="file" type="file"/>
                    </div>
                </form>

            </div>




        </div>


        <div>
            <div class="tab-page tab-selected" data-index="2">
                <br>
                <div class="booked">

                </div>

                <div class="not-exist response" style="display: none">
                    <p>Controlla i valori evidenziati nella tabella</p>
                </div>

                <div class="to-send" style="display: none">
                    <button id="send-to-save">INSERISCI</button>
                </div>

                <div class="new_hour">

                </div>



            </div> 
        </div>

        <div class="clear"></div>

        <div class="row">

            <div class="col-lg">

            </div>

        </div>
        <div class="row" style="margin: 10px 0px 0px 0px" hidden="">
            <div class="col-lg " id="response" style="width: 100%; float:left">
                <img src="https://media2.giphy.com/media/3oEjI6SIIHBdRxXI40/200.gif" alt="alt"/>
            </div>
        </div>

    </div>
</div>



<script>


    Dropzone.options.loadXlsxFile = {// camelized version of the `id`
        // maxFilesize: 2, // MB
        paramName: "file", // The name that will be used to transfer the file
        acceptedFiles: '.xls,.xlsx',
        accept: function (file, done)
        {

            var myDropzone = this;

            console.log(file.name);
            console.log(file.status);
            done();
        },
        complete: function (file, response)
        {

            var res = JSON.parse(file.xhr.responseText);
            var to_send_button = true;

            $(".response").hide();
            $(".booked").html("");
            $(".to-send").hide();

            console.log(res);

            $(".new_hour").html(res['table']);

            if (!res['response']['exist'])
            {
                to_send_button = false;

                $(".not-exist").show();
            }

            if (!res['response']['insert']['inserimento'])
            {
                to_send_button = false;

                for (i in res['response']['insert']['analizza'])
                {
                    if (parseInt(res['response']['insert']['analizza'][i]['prenotazioni']) > 0)
                    {
                        var toAppend = `<p>${res['response']['insert']['analizza'][i]['data_ora_campo']}</p>`;
                        $(".booked").append(toAppend);
                    }
                }
            }

//            to_send_button = true; //fake

            if (to_send_button)
            {
                $(".to-send").show();
            }

        },
    };

    $(() =>
    {
        $("#send-to-save").click(function ()
        {
            $.post("/matches/saveValuesExcel", function (data)
            {
                console.log(data);
                $.get("/apis/forCronCalendario/?api_key=b621-386594c0895e", function ()
                {
                    alert("INSERIMENTO OK");
                    location.reload();
                });
            });
        });

    });
</script>







