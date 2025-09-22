<script type="text/javascript">

	$(function() {
	
		$("li a.export").click(function(e) {
			e.stopPropagation();
			e.preventDefault();
			
			if ($(".index-select-checkbox:checked").length > 0)
			$.fancybox.open({ 'href': '/admin/newsletter_users/export2', 'type': 'ajax' });
			else alert('Selezionare almeno un gruppo da esportare');
			
			return false;
		});
	
	});

</script>
<?

	print $backend->formIndex('NewsletterGroup',
	
			
				array( 
                                        /* //GIUSEPPE 2020-09-01*/
					'ID' => 	 
							array(
							
								'field' => 'NewsletterGroup.id',
								'order' => true,

							),
                                        /* ********************* */
					'Nome gruppo' => 	 
							array(
							
								'field' => 'NewsletterGroup.title',
								'order' => true,

							),
					'Riassunto' => 	 
							array(
							
								'field' => 'NewsletterGroup.summary',
								'order' => true,

							),
					'Totale utenti' => 	array(
							
								'field' => 'NewsletterGroup.CountUser',
								'order' => true,

							),
						
				)
	
	,array(

		'defaultOrder' => 'NewsletterGroup.title',
		'defaultDir'   => 'ASC',
		'pageTitle' =>	'Gruppi newsletter',
		'quickSearch' => array('NewsletterGroup.title','NewsletterGroup.summary')

	));


?>
<!--/* //GIUSEPPE 2020-09-01 */-->
<style>
 .td_id{text-align:right;width: 50px}
 .th_id{text-align:right;width: 50px}
</style>
<!--/* ********************* */-->
