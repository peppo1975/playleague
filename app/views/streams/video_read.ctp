<?//pr($posts);?>

<center>
<?if($posts == array()) echo "Nessun articolo trovato.";?>
<?php foreach($posts as $post): ?>
<p><b>Titolo:</b> <?=$this->Html->link($post['Post']['title'], '/video/posts/read/' . $post['Post']['id']); ?></p>
<p><b>Sottotitolo:</b> <?=$post['Post']['subtitle']; ?></p>
<p><b>Riassunto:</b> <?=$post['Post']['summary']; ?></p>
<hr>
<?php endforeach; ?>
<? $paginator->options(array('url'=>array_merge(array('video' => true), $this->passedArgs))); ?>
<?php

echo $html->div(
  null,
  $paginator->prev(
    'Nuovi post',
    array(
      'class' => 'PrevPg'
    ),
    null,
    array(
      'class' => 'PrevPg DisabledPgLk'
    )
  ).
  $paginator->next(
    'Vecchi post',
    array(
      'class' => 'NextPg'
    ),
    null,
    array(
      'class' => 'NextPg DisabledPgLk'
    )
  ),
  array(
    'style' => 'width: 100%;'
  )
);  
?>