<script type="text/javascript" src="/js/layout.js"></script>
<script type="text/javascript">

    $(function () {

        $('.contents-box-right-container').css('position', 'relative');

        $('.tipoSesso').live('click', function () {

            var obj = $(this);

            $('html,body').animate({scrollTop: $('.list-albo-calcio[data-index="' + obj.attr("data-index") + '"]').offset().top}, 1000, function () {

                //$('.contents-box-right-container').css('top', $('.list-albo-calcio[data-index="' + obj.attr("data-index") + '"]').offset().top - 224);

            });

            return false;

        });

        $('.tipoCategoria').live('click', function () {

            var obj = $(this);

            $('html,body').animate({scrollTop: $('.list-albo-campionato[data-index="' + obj.attr("data-index") + '"]').offset().top}, 1000, function () {

                //$('.contents-box-right-container').css('top', $('.list-albo-campionato[data-index="' + obj.attr("data-index") + '"]').offset().top - 224);

            });

            return false;

        });




    });

</script>

<div role="main" class="main">

    <div style="background: #f5f5f5; margin-bottom: 20px">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumb" style="margin-bottom: 0">
                        <li><a href="/">Home</a></li>
                        <li class="">
                            Albo d'oro Basket
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container" id="main-custom">

        <div class="row">
            <div class="col-md-12">


                <div class="post-content">
                    <div class="row">
                        <div class="col-md-12">
                            <h2>
                                Albo d'oro Basket
                            </h2>
                        </div>

                    </div>
                </div>
                <hr />

                <div class="tabs tabs-bottom tabs-center tabs-simple">
                    <ul class="nav nav-tabs">
                        <? $godpig = 0; ?>
                        <? foreach ($albo as $tipo => $categorie): ?>	


                            <li class="<? if ($godpig == 0): ?>active<? $godpig = 1; ?><? endif; ?>">
                                <a href="#<?= str_replace(" ", "_", $tipo); ?>" data-toggle="tab"><?= $tipo; ?></a>
                            </li>
                        <? endforeach; ?>

                    </ul>
                    <div class="tab-content">

                        <? $godpig = 0; ?>


                        <? foreach ($albo as $tipo => $categorie): ?>	

                            <div class="tab-pane <? if ($godpig == 0): ?>active<? $godpig = 1; ?><? endif; ?>" id="<?= str_replace(" ", "_", $tipo); ?>">
                                <div class="panel-group" id="accordion_<?= str_replace(" ", "_", $tipo); ?>">

                                    <? ksort($categorie); ?>


                                    <? foreach ($categorie as $categoria => $anni): ?>

                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_<?= str_replace(" ", "_", $tipo); ?>" href="#<?= Inflector::slug($categoria, "_"); ?>">
                                                        <?= $categoria; ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="<?= Inflector::slug($categoria, "_"); ?>" class="accordion-body collapse">
                                                <div class="panel-body">
                                                    <? $i = 0; ?>

                                                    <table class="table table-bordered table-striped table-condensed table-responsive">
                                                        <thead>
                                                        <th>Stagione</th>
                                                        <th>Vincitore</th>
                                                        </thead>

                                                        <? foreach ($anni as $anno => $vincitore): ?>

                                                            <tr>
                                                                <td class="data"><?= $anno; ?></td>
                                                                <td class="squadra"><?= $vincitore; ?></td>
                                                            </tr>
                                                            <? $i++; ?>

                                                        <? endforeach; ?>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>


                                    <? endforeach; ?>


                                </div>						

                            </div>
                        <? endforeach; ?>
                    </div>
                </div>

                <div class="contents-block-left container-albo-list" style="display: none;">
                    <? foreach ($albo as $tipo => $categorie): ?>	


                        <div class="list-albo-calcio" data-index="<?= $tipo; ?>">
                            <h1><?= $tipo; ?></h1>

                            <? ksort($categorie); ?>

                            <? foreach ($categorie as $categoria => $anni): ?>

                                <div class="list-albo-campionato" data-index="<?= $tipo; ?>-<?= $categoria; ?>">
                                    <h2><?= $categoria; ?></h2>

                                    <? $i = 0; ?>

                                    <? foreach ($anni as $anno => $vincitore): ?>

                                        <div class="list-winner">
                                            <p <? if (($i % 2) == 0): ?>class="alternata"<? endif; ?>>
                                                <span class="data"><?= $anno; ?></span>
                                                <span class="squadra"><?= $vincitore[0]; ?></span>
                                            </p>
                                        </div><!-- close list-winner -->

                                        <? $i++; ?>

                                    <? endforeach; ?>

                                </div><!-- close list-albo-campionato -->

                            <? endforeach; ?>

                        </div><!-- close list-albo-calcio -->

                    <? endforeach; ?>

                </div><!-- close contents-box-left -->

                <div class="clear"></div>
            </div><!-- close contents-box -->
        </div><!-- close wrapper-box-contents -->
        <div class="wrapper-box-bottom"></div>
    </div><!-- close wrapper-box --> 																																			