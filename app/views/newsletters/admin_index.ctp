<script>
    //GIUSEPPE 2017-05-26 - - - - - - - - - - - - - 

    setTimeout(test, 500);

    function test()
    {

        window.scrollTo(0, 0);
    }

    //- - - - - - - - - - - - - - - - - - - - - - - 
</script>
<?
print $backend->formIndex('Newsletter', array(
            'Abilita/Disabilita' =>
            array(
                'field' => 'Newsletter.disabled'
            ),
            'Titolo' =>
            array(
                'field' => 'Newsletter.title',
                'order' => true,
            ),
            'Layout' =>
            array(
                'field' => 'Newsletter.layout',
                'order' => true,
            ),
            'Data creazione' =>
            array(
                'field' => 'Newsletter.created',
                'order' => true,
                'afterRender' => 'make_date',
                'afterSearch' => 'invert_date'
            ),
            'Data ultima modifica' =>
            array(
                'field' => 'Newsletter.modified',
                'order' => true,
                'afterRender' => 'make_date',
                'afterSearch' => 'invert_date'
            ),
                )
                , array(
            'defaultOrder' => 'Newsletter.created',
            'defaultDir' => 'DESC',
            'pageTitle' => 'Newsletter',
            'quickSearch' => array('Newsletter.title'),
            'buttons' => array(
                'Anteprima' => array('class' => 'previewNewsletter', 'img' => '/img/timmyshare/icon_preview.png'),
                'Storico' => array('class' => 'storyNewsletter', 'img' => '/img/timmyshare/icon_story.png'),
            ),
            'besideQuickSearch' => '
			<ul>
				<li><a href="javascript:;" title="Invia più newsletter" onclick="javascript:if($(\'.index-select-checkbox:checked\').length > 0) timmy_load(\'/admin/newsletters/send\'); else { alert(\'Selezionare almeno una newsletter.\') }" rel="timmytip" id="newsletterSend"><img src="/img/mainshare/mail-send.png" width="16" height="16" alt="" /></a></li>
			</ul>
		
		',
));
?>