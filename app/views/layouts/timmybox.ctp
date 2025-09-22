<? if($layout == "tablet"): ?>

<?=$content_for_layout;?>

<? else: ?>

<div id="timmybox_container">

<div style="display: block;" id="timmy_close" class="button_up"><img src="/img/timmybox/close.png"></div>

<?=$content_for_layout;?>

</div>

<? endif; ?>