<? if ($this->params['prefix'] != 'admin'): ?>
<div class="modal fade" id="defaultModal" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
																									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

												<h4 class="modal-title-tmp text-center">&nbsp;</h4>
												</div>
												<div class="modal-body">
<?=$content_for_layout;?>

												</div>
											
											</div>
										</div>
										</div>
<? else: ?>
<div id="timmybox_container">

<div style="display: block;" id="timmy_close" class="button_up"><img src="/img/timmybox/close.png"></div>

<?=$content_for_layout;?>

</div>
<? endif; ?>