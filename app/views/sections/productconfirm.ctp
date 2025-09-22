<?
    $fixed = $this->requestAction('fixeds/read_all_fixed');//GIUSEPPE 2018-08-28 --richiama la tabella dei contenuti fissi
?>


<script type="text/javascript" src="/js/layout.js"></script>


<style type="text/css">

.contents-text p, .contents-text h3 { padding-left: 20px; }
.contents-text { padding-top: 20px; }

#progress * {
    box-sizing: border-box;
}

#progress {
    padding: 0;
    list-style-type: none;
    font-family: arial;
    font-size: 12px;
    clear: both;
    line-height: 1em;
    margin: 0 -1px;
    text-align: center;
}

#progress li {
    float: left;
    padding: 10px 30px 10px 40px;
    background: #eeeeee;
    color: #444;
    position: relative;
    border-top: 1px solid #eeeeee;
    border-bottom: 1px solid #eeeeee;
    width: 19%;
    margin: 0 1px;
}

#progress li:first-child:before {
	content: none !important;
}
#progress li:before {
    content: '';
    border-left: 16px solid #fff;
    border-top: 16px solid transparent;
    border-bottom: 16px solid transparent;
    position: absolute;
    top: 0;
    left: 0;
    
}
#progress li:after {
    content: '';
    border-left: 16px solid #eeeeee;
    border-top: 16px solid transparent;
    border-bottom: 16px solid transparent;
    position: absolute;
    top: 0;
    left: 100%;
    z-index: 20;
}

#progress li.active {
    background: #fd8a15;
    color: #fff;
}

#progress li.active:after {
    border-left-color: #fd8a15;
}


</style>

<div role="main" class="main">

<!-- Admin Extension Specific Page Vendor CSS -->

                <link rel="stylesheet" href="/vendor/theme.admin.extension.css">

    <link rel="stylesheet" href="/vendor/theme.extension.css">
  <div class="container" id="main-custom">
    
    <div class="row">
      <div class="col-md-12">


    <div class="post-content">
    <div class="row">
    <div class="col-md-12">

        <? if ($ok == 1): ?>
<div class="alert alert-success text-center">
Transazione eseguita con successo.

</div>
<? endif ?>
        </div>
    </div>


      <?if($ok == 1):?>
    <hr />
    <?endif?>
	


	</div>


								<div class="contents-text">




<div style="padding: 20px;" class="text-center">

<? if ($ok == 1): ?>

<div class="alert alert-success" style="text-align: center;">
        Gentile <?=$product["nome"]?> <?=$product["cognome"]?>,<br>
        ti confermiamo l’acquisto di:<br>
        <b><?=$product["product_name"]?>  &nbsp;€ <?=$product["product_price"]?></b><br>
        <br>
        Riceverai una email di conferma della transazione.<br /><br />
</div>
                            <div class="call-to-action-btn" style="text-align: center; margin-top: 40px;"> 
                                <a class="btn btn-sm btn-primary" href="/"><?= $fixed['message_torna_home'] ?></a>
                            </div>
<? else: ?>
                            <div class="alert alert-danger" style="text-align: center;">
                                <?= $fixed['alert_message_transazione'] ?>
                            </div>
                            <div class="call-to-action-btn" style="text-align: center; margin-top: 40px;"> 
                                <a class="btn btn-sm btn-primary" href="/"><?= $fixed['message_torna_home'] ?></a>
                            </div>
<? endif; ?>
</div>


</div>
</div>
</div>
</div>