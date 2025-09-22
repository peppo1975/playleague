
<script>



    $(document).ready(function () {

        
        var indirizzo = window.location.pathname;
        
        var ind = indirizzo.toString();
  
        var ok = "ok";
       
        var out = "out";
        
        if (ind.indexOf(out) >= 0)
        {
            $("#read_php").hide();
            $("#read_jquery").append('<div class="alert alert-danger">Errore, impossibile confermare la sua registrazione.</div>');
            
        }

        if (ind.indexOf(ok) >= 0)
        {
            $("#read_php").hide();
            $("#read_jquery").append('<div class="alert alert-success">La sua registrazione è stata confermata. Può procedere con il login.</p></div>');
        }
    });

</script>

<div role="main" class="main">

    <div style="background: #f5f5f5; margin-bottom: 20px">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumb" style="margin-bottom: 0">
                        <li><a href="/">Home</a></li>
                        <li class="active">Registrazione atleti</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        <div class="row">
            <div class="col-md-9">

                <h2>Modulo di registrazione atleti</h2>
                <div id="read_php">
                    <? if ($ok): ?>

                        <div class="alert alert-success">
                            La sua registrazione è stata confermata. Può procedere con il login.
                            </p>
                        </div>

                    <? else: ?>

                        <div class="alert alert-danger">

                            Errore, impossibile confermare la sua registrazione.

                        </div>

                    <? endif; ?>
                </div>
                <div id="read_jquery"></div>

                <!-- close contents-box -->

                <!--			<div class="col-md-3">
                                                <aside class="sidebar">
                                                        <h4 class="heading-primary">Crea nuovo account</h4>
                                                                <ul class="nav nav-list narrow">
                                                                                <li  class="active" >
                                                                                        <a href="/registrazione" title="">
                                                                                                Registrazione utenti
                                                                                        </a>
                                                                                </li>
                                                                                <li>
                                                                                        <a href="/registrazione/atleti"  class="" title="">
                                                                                                Registrazione atleti
                                                                                        </a>
                                                                                </li>
                
                                                                </ul>
                                                </aside>
                                        </div>-->

            </div><!-- close wrapper-box-contents -->

        </div><!-- close wrapper-box -->
    </div>
</div>