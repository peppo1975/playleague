
<!--<style>
    .loader {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 2s linear infinite; /* Safari */
        animation: spin 2s linear infinite;
    }

    /* Safari */
    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>-->

<section class="filter-<?= @$class; ?> section section-with-divider calendari parallax section section-text-light section-parallax section-center" >

    <div class="container">

        <div class="row">

            <div class="col-lg-12"> 
                <h2 style="font-weight: bold;">
                    Calendari e classifiche
                    <span>Tutti i calendari di gioco, risultati, classifiche e disciplinari</span>
                </h2>

                <div class="row">

                    <div class="col-lg" id="calendario_partite">
                        <!--<div class="loader">-->
                            
                       <div><?=$this->requestAction('apis/calendariClassifichePageOther');?> </div>
                    </div>

                </div>

                <div class="row">

                    <?= $this->element('site/filter_table') ?>

                </div>
            </div>


        </div>
    </div>



</section>


<script>


//    calendario();
//
//
//    async function calendario()
//    {
//        console.log('qui');
//        var link = "/apis/calendariClassifichePageOther";
//        const res = await httpPost(link, {});
//        document.getElementById('calendario_partite').innerHTML = res;
//    }
//
//
//    function httpPost(link, to_send)
//    {
//
//        return new Promise((resolve, reject) => {
//
//            const xhr = new XMLHttpRequest();
//
//            xhr.open("POST", link);
//
//            xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
//
//            const body = JSON.stringify(to_send);
//
//            xhr.send(body);
//
//            xhr.onload = () => {
//
//                if (xhr.readyState == 4 && xhr.status == 200)
//                {
////                    var arr = JSON.parse(xhr.response);
////                    resolve(arr);
//
//                    resolve(xhr.response);
//                }
//                else
//                {
//                    reject(new Error(xhr.statusText));
//                }
//            };
//        });
//    }

</script>