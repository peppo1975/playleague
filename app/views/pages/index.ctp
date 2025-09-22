
<script type="text/javascript">
    $(function ()
    {
        $('.block-attachment li').find('img').css('opacity', 0);
    });
    $(window).load(function ()
    {

        $('.block-attachment').find('li').each(function (index)
        {

            var first = parseFloat($(this).height() / 2);
            var second = parseFloat($(this).find('img').height() / 2);

            var margin_top = first - second;
            //var margin_left = ($(this).width() / 2) - ($(this).children('img').width() / 2);

            $(this).find('img').css('margin-top', margin_top);
            //$(this).children('img').css('margin-left', margin_left);

        });

        $('.block-attachment li').find('img').css('opacity', 1);

    });

</script>
<?
//debug($data);
$isiPad = (bool) strpos($_SERVER['HTTP_USER_AGENT'], 'iPad');
$ext = array('flv', 'mp4');
$strip = strip_tags($data['Page']['content']);
$ext_doc = array('doc', 'xls', 'zip', 'xlsx', 'rar', 'pdf', 'mp3');
$ext_video = array('flv', 'mp4', 'mp3');
$ext_img = array('jpg', 'jpeg', 'png', 'gif');

$mimes = array(
    'doc' => 'icon-doc.png',
    'xls' => 'icon-xls.png',
    'zip' => 'icon-zip.png',
    'xlsx' => 'icon-xls.png',
    'rar' => 'icon-zip.png',
    'pdf' => 'icon-pdf.png',
    'mp3' => 'icon-mp3.png',
);

$countDocuments = 0;
$countMultimedia = 0;
$countVideos = 0;
$countLink = 0;
$countIpad = 0;
if (isset($data['Upload']) && count($data['Upload']))
{
//Check documents and media upload
    foreach ($data['Upload'] as $tmp)
    {
        if (in_array($tmp['ext'], $ext_doc))
            $countDocuments++;
        if (in_array($tmp['ext'], $ext_img) && $tmp['tag'] != 'link')
            $countMultimedia++;
        if (in_array($tmp['ext'], $ext_video))
            $countVideos++;
        if ($tmp['ext'] == 'mp4')
            $countIpad++;
        if ($tmp['tag'] == 'link')
            $countLink++;
    }
}
?>

<?


function fucking16_10izer($array)
{
    $tb = new ThumbnailHelper;

    $width = $array["w"];
    $height = 10 / 16 * $width;
    return $tb->link(array_merge($array, ["w" => $width, "h" => $height, "zc" => 1]));

}
?>
<style>
    .img-thumbnail img{
        width: 100%;
    }
    .img-thumbnail{
        width: 100px;
    }

    .img-thumbnail-spec{
        width: 100%;
    }


</style>
<div role="main" class="main">

    <div style="background: #f5f5f5; margin-bottom: 20px">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumb" style="margin-bottom: 0">
                        <?php if ($data['Page']['Genitore'] == "Scuola calcio a 5"): ?>
                            <li><a href="/#menu=secondary">Home</a></li>
                        <?php else: ?>
                            <li><a href="/">Home</a></li>
                        <?php endif; ?>
                        <li class="active"><?= $data['Page']['title']; ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <div class="container" id="main-custom">

        <? $esclusi = array(128, 129, 130); ?>


        <div class="<? if ($data['Page']['Genitore'] != 'Campionati/Tornei' && !in_array($data['Page']['id'], $esclusi)): ?>col-md-9<? else: ?>col-md-12<? endif; ?>">
            <?php
            //print "<pre>";print_r($data["Upload"]);exit;
            $img_evidenza = null;
            foreach ($data["Upload"] as $upload)
            {
                if ($upload["isEvidenza"])
                    $img_evidenza = $upload;
            }

            if (!$img_evidenza)
            {
                foreach ($data["Upload"] as $upload)
                {
                    if ($upload["type"] == "image/jpeg" && $upload['tag'] != 'link')
                    {
                        $img_evidenza = $upload;
                        break;
                    }
                }
            }

            if ($img_evidenza):
                $src = $thumbnail->link(array('path' => $img_evidenza['path'], 'w' => 794));
                ?>

                <div class="post-image">
                    <div class="owl-carousel owl-theme" data-plugin-options='{"items":1}'>
                        <div style="text-align: center">
                            <div class="img-thumbnail img-thumbnail-spec">
                                <img class="img-responsive" style="width: 100%; margin: 0 auto;" src="<?= $src ?>" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>




            <div>









                <div class="post-content">
                    <h2><?= $data['Page']['alias']; ?></h2>

                    <? if ($data['Page']['subtitle'] != ''): ?>
                        <p class="lead"><?= $data['Page']['subtitle']; ?></p>
                    <? endif; ?>

                    <hr />

                    <? if (!empty($strip)): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <p class="lead">



                                    <?= $data['Page']['content']; ?>
                                </p>
                            </div>
                        </div>
                    <? endif; ?>



                    <!-- INIZIO POSTS --> 
                    <style>
                        .thumb-info .thumb-info-wrapper:after{
                            background: initial !important;
                        }
                    </style>
                    <div class="blog-posts">
                        <!-- Allegati pdf -->
                        <? if ($countDocuments > 0): ?>
                            <div class="post-meta">
                                <? foreach ($data['Upload'] as $attach): ?>
                                    <? if (in_array($attach['ext'], $ext_doc)): ?>
                                        <?
                                        if ($attach['title'] != '')
                                            $title = $attach['title'];
                                        else
                                            $title = $attach['name'];
                                        ?>
                                        <span class="bottom-5">
                                            <a href="/files/uploads/<?= $attach['name']; ?>" title="<?= $title; ?>">
                                                <span class="icon"><img src="/img/website/icon-pdf.png" alt=""></span> <?= $title; ?>
                                            </a>
                                        </span>
                                    <? endif; ?>
                                <? endforeach; ?>
                            </div> <!-- close contentents-files-documents -->
                        <? endif; ?>


                        <? if ($countLink > 0): ?>

                            <div class="row">
                                <?php foreach ($data['Upload'] as $attach): ?>
                                    <?php
                                    if ($attach['tag'] == 'link'):
                                        if ($attach['title'] != '')
                                            $title = $attach['title'];
                                        else
                                            $title = $attach['name'];
                                        $path = fucking16_10izer(array('path' => $attach['path'], 'w' => 212 * 2, 'h' => 80 * 2, 'q' => 100));
                                        ?>
                                        <?php if (substr($attach['description'], 0, 7) == 'http://' && strlen($attach['description']) > 7): ?>
                                            <div class="col-md-3">	
                                                <a href="<?= $attach['description']; ?>" title="<?= $title; ?>">
                                                    <span class="thumb-info"  style="width: 100px; height: 60px; padding: 4px">
                                                        <div style="width: 100%; height: 100%; text-align: center; background-image: url('<?= $path; ?>'); background-repeat: no-repeat; background-position: center center; background-size: contain; ">
                                                            <span class="thumb-info-wrapper">
                                                                    <!-- <img src="<?= $path; ?>" class="img-responsive" alt="<?= $title; ?>"> -->
                                                                <span class="thumb-info-action">
                                                                    <span class="thumb-info-action-icon"><i class="fa fa-link"></i></span>
                                                                </span>
                                                            </span>
                                                        </div>
                                                    </span>
                                                </a>
                                            </div>

                                        <?php else: ?>
                                            <div class="col-md-3">
                                                <span class="thumb-info"  style="width: 100px; height: 60px; padding: 4px">
                                                    <div style="width: 100%; height: 100%; text-align: center; background-image: url('<?= $path; ?>'); background-repeat: no-repeat; background-position: center center; background-size: contain; ">
                                                        <span class="thumb-info-wrapper">
                                                                <!-- <img src="<?= $path; ?>" class="img-responsive" alt="<?= $title; ?>"> -->
                                                            <span class="thumb-info-action">
                                                                <span class="thumb-info-action-icon"><i class="fa fa-link"></i></span>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </span>
                                            </div>

                                        <?php endif; ?>
                                    <?php endif; ?>

                                <?php endforeach; ?>
                            </div>

                        <? endif; ?>



                        <? if ($countVideos > 0): ?>
                            <hr>
                            <div class="contentents-files-documents" id="videoUpload">
                                <h3>Video allegati</h3>
                                <div class="documents-container" id="videoUploadContainer">
                                    <? foreach ($data['Upload'] as $attach): ?>
                                        <? if (in_array($attach['ext'], $ext_video)): ?>
                                            <?
                                            if ($attach['title'] != '')
                                                $title = $attach['title'];
                                            else
                                                $title = $attach['name'];
                                            //Calcolare anteprima e video full!!!!
                                            if ($attach['ext'] == 'mp3')
                                            {
                                                $path = '/img/icon_black.png';
                                                $type = 'audio';
                                                $div_title = 'Ascolta audio';
                                            }
                                            else
                                            {
                                                $path = $thumbnail->frame_link(array('path' => $attach['path'], 'w' => 150 * 2, 'h' => 110 * 2, 'q' => 100, 'zc' => 1, 'f' => 'jpg'));
                                                $type = 'video';
                                                $div_title = 'Visualizza video';
                                            }
                                            ?>
                                            <? if ($isiPad): ?>
                                                <? if ($attach['ext'] == 'mp4'): ?>
                                                    <video width="150" height="110" src="<?= $attach['path']; ?>" controls="controls" x-webkit-airplay="allow">
                                                        <source src="<?= $attach['path']; ?>"></source>
                                                    </video>
                                                <? endif; ?>
                                            <? else: ?>
                                                <div class="play-video">
                                                    <a class="is_video type_<?= $type; ?>" rel="timmygallery" title="<?= $title; ?>" href="javascript:;" link="<?= $attach['path']; ?>">
                                                        <?php echo $this->Html->image("icon-play-" . $type . ".png", array("alt" => "Play " . $type, 'class' => 'play-icon')); ?>
                                                        <img src="<?= $path; ?>" alt="<?= $title; ?>"/>
                                                        <span class="timmy_description"><?= $attach['description']; ?></span>
                                                    </a>
                                                </div>
                                            <? endif; ?>
                                        <? endif; ?>
                                    <? endforeach; ?>
                                </div><!-- close documents-container -->
                            </div> <!-- close contentents-files-documents -->



                        <? endif; ?>



                        <? if ($countMultimedia > 0): ?>
                            <?= $this->element("site/slider", array('countMultimedia' => $countMultimedia, 'data' => $data, 'ext_img' => $ext_img)); ?>
                        <? endif; ?>

                        <?php if (count($data['Block']) && $data['Page']['parent_id'] == 53): ?>


                            <div class="panel-group" id="accordion">


                                <? foreach ($data['Block'] as $z => $block): ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $z; ?>">
                                                    <?= $block['Block']['title']; ?>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse<?= $z; ?>" class="accordion-body collapse">
                                            <div class="panel-body">


                                                <?php
                                                $i = 0;
                                                foreach ($block['Upload'] as $key => $attach):
                                                    if ($attach['type'] == "image/jpeg"):
                                                        if ($i++ == 0):
                                                            $isVideo = 1;
                                                            $link = fucking16_10izer(array('path' => $attach['path'], 'w' => 212 * 2, 'h' => 80 * 2, 'q' => 100));
                                                            $path = $attach['path'];
                                                            $title = ($attach['title'] != '') ? $attach['title'] : $attach['name'];
                                                            $href = ($attach['description'] != '') ? $attach['description'] : 'javascript:;';
                                                            ?>

                                                            <?php if (substr($attach['description'], 0, 7) == 'http://' && strlen($attach['description']) > 7): ?>

                                                                <img src="<?= $link; ?>" style="padding-right: 10px;" class="pull-left" alt="">

                                                            <?php else: ?>

                                                                <img src="<?= $link; ?>"  style="padding-right: 10px;" class="pull-left" alt="">

                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>


                                                <?= $block['Block']['content']; ?>

                                                <!-- PDF -->


                                                <!-- NON STILIZZATO -->
                                                <?php
                                                if (count($block['Upload'])):
                                                    $countDocuments_blocks = 0;
                                                    $countImg_blocks = 0;
                                                    foreach ($block['Upload'] as $attach)
                                                    {
                                                        if (in_array($attach['ext'], $ext_img) && $attach['tag'] == 'link')
                                                            $countImg_blocks++;
                                                        if (in_array($attach['ext'], $ext_doc))
                                                            $countDocuments_blocks++;
                                                    }
                                                endif;
                                                ?>

                                                <? if ($countDocuments_blocks > 0): ?>
                                                    <div class="post-meta">
                                                        <? foreach ($block['Upload'] as $attach): ?>
                                                            <? if (in_array($attach['ext'], $ext_doc)): ?>
                                                                <?
                                                                if ($attach['title'] != '')
                                                                    $title = $attach['title'];
                                                                else
                                                                    $title = $attach['name'];
                                                                ?>
                                                                <span class="bottom-5">
                                                                    <a href="/files/uploads/<?= $attach['name']; ?>" title="<?= $title; ?>"><span class="icon"><img src="/img/website/<?= $mimes[$attach['ext']]; ?>" alt=""></span> <?= $title; ?></a>
                                                                </span>
                                                            <? endif; ?>
                                                        <? endforeach; ?>
                                                    </div>
                                                <? endif; ?>

                                            </div>
                                        </div>
                                    </div>
                                <? endforeach; ?>
                            </div>



                        <? endif; ?>

                        <?php if (count($data['Block']) && $data['Page']['parent_id'] != 53): ?>
                            <?php
                            if (in_array($data['Page']['id'], Configure::read('id_news')))
                            {
                                $data['Block'] = Set::Sort($data['Block'], '{n}.Block.published', 'DESC');
                            }
                            ?>
                            <?php
                            $first = 1;
                            foreach ($data['Block'] as $block):

                                //GIUSEPPE 2018-04-30 ---------------------------------------------------------------

                                if (isset($block['Block']['over']))
                                {
                                    //echo $block['Block']['over_it']."------------------------------------------------------------------<br><br><br>";  

                                    $over = $block['Block']['over'];

                                    $now = date("Y-m-d H:i:s");

                                    if ($over != "0000-00-00 00:00:00")
                                    {
                                        //echo $over."<br>";

                                        if ($now > $over)
                                        {
                                            continue;
                                        }
                                    }
                                }
                                //-----------------------------------------------------------------------------------
                                ?>
                                <?php if ($data['Page']['id'] != 56): ?>
                                    <?php if ($block['Block']['type'] == 0): //Mostra tutto   ?>

                                        <article class="post post-large" style="margin-left: 0">
                                            <table width="100%">
                                                <tr>

                                                    <!-- NON STILIZZATO -->
                                                    <?php
                                                    if (count($block['Upload'])):
                                                        $countDocuments_blocks = 0;
                                                        $countImg_blocks = 0;
                                                        foreach ($block['Upload'] as $attach)
                                                        {
                                                            if (in_array($attach['ext'], $ext_img) && $attach['tag'] == 'link')
                                                                $countImg_blocks++;
                                                            if (in_array($attach['ext'], $ext_doc))
                                                                $countDocuments_blocks++;
                                                        }
                                                        ?>
                                                        <?php if ($countImg_blocks > 0): ?>
                                                        <div class="row">
                                                            <?php
                                                            $i = 0;
                                                            foreach ($block['Upload'] as $key => $attach):
                                                                if ($attach['type'] == "image/jpeg"):
                                                                    if ($i++ == 0):
                                                                        $isVideo = 1;
                                                                        $link = fucking16_10izer(array('path' => $attach['path'], 'w' => 212 * 2, 'h' => 80 * 2, 'q' => 100));
                                                                        $path = $attach['path'];
                                                                        $title = ($attach['title'] != '') ? $attach['title'] : $attach['name'];
                                                                        $href = ($attach['description'] != '') ? $attach['description'] : 'javascript:;';
                                                                        ?>

                                                                        <?php if (substr($attach['description'], 0, 7) == 'http://' && strlen($attach['description']) > 7): ?>
                                                                            <td style="padding: 5px 10px 5px 0; vertical-align: top">
                                                                                <div class="">
                                                                                    <div class="img-thumbnail"  style="width: 100px; height: 60px; padding: 4px">
                                                                                        <a href="<?= $href; ?>" title="<?= $title; ?>">
                                                                                            <div style="width: 100%; height: 100%; text-align: center; background-image: url('<?= $link; ?>'); background-repeat: no-repeat; background-position: center center; background-size: contain;">
                                                                                            <!-- <img src="<?= $link; ?>" alt=""> -->
                                                                                            </div>
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </td>

                                                                        <?php else: ?>
                                                                            <td style="padding: 5px 10px 5px 0; vertical-align: top">
                                                                                <div class="">
                                                                                    <div class="img-thumbnail"  style="width: 100px; height: 60px; padding: 4px">
                                                                                        <div style="width: 100%; height: 100%; text-align: center; background-image: url('<?= $link; ?>'); background-repeat: no-repeat; background-position: center center; background-size: contain;">
                                                                                        <!-- <img src="<?= $link; ?>" alt=""> -->
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>


                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <? endif; ?>



                                                <?php endif; ?>
                                                <!-- FINE NON STILIZZATO -->

                                                <td style="padding: 5px; vertical-align: top; width: 100%">

                                                    <div class="post-content">

                                                        <h2><?= $block['Block']['title']; ?></h2>
                                                        <?= $block['Block']['content']; ?>
                                                        <?php
                                                        if (count($block['Upload'])):
                                                            $countDocuments_blocks = 0;
                                                            $countImg_blocks = 0;
                                                            foreach ($block['Upload'] as $attach)
                                                            {
                                                                if (in_array($attach['ext'], $ext_img) && $attach['tag'] == 'link')
                                                                    $countImg_blocks++;
                                                                if (in_array($attach['ext'], $ext_doc))
                                                                    $countDocuments_blocks++;
                                                            }
                                                            ?>
                                                            <!-- PDF -->
                                                            <? if ($countDocuments_blocks > 0): ?>
                                                                <div class="post-meta">
                                                                    <? foreach ($block['Upload'] as $attach): ?>
                                                                        <? if (in_array($attach['ext'], $ext_doc)): ?>
                                                                            <?
                                                                            if ($attach['title'] != '')
                                                                                $title = $attach['title'];
                                                                            else
                                                                                $title = $attach['name'];
                                                                            ?>
                                                                            <span class="bottom-5">
                                                                                <a href="/files/uploads/<?= $attach['name']; ?>" title="<?= $title; ?>">
                                                                                    <span class="icon"><img src="/img/website/<?= $mimes[$attach['ext']]; ?>" alt=""></span> <?= $title; ?> 
                                                                                </a> 
                                                                            </span>																							<? endif; ?>
                                                                    <? endforeach; ?>
                                                                </div>
                                                            <? endif; ?>
                                                        <?php endif ?>


                                                    </div>
                                                </td>
                                                </tr>
                                            </table>
                                        </article>
                                    <?php else: ?> <!-- block type  != 0 -->
                                        <?
                                        //Genero link
                                        if ($block['Block']['url'] != '')
                                        {
                                            $block_link = $block['Block']['url'];
                                        }
                                        elseif ($block['Block']['url_page_id'] != '')
                                        {
                                            $block_link = $this->requestAction('/pages/getPageUrl/' . $block['Block']['url_page_id']);
                                        }
                                        else
                                        {
                                            $block_link = '/blocchi/' . $block['Block']['id'] . '/' . strtolower(Inflector::Slug($block['Block']['title'], '-'));
                                        }
                                        if ($first == 1)
                                        {
                                            $first++;
                                            $class = "border: 0 !important";
                                        }
                                        else
                                            $class = "";
                                        ?>





                                        <article class="post post-large " style="margin-left: 0;<?= $class ?>">
                                            <table width="100%">
                                                <tr>
                                                    <?php if ($block['Block']['img_evidenza'] != ''): ?>
                                                        <?
                                                        $title = ($block['Block']['descrizione_evidenza'] != '') ? $block['Block']['descrizione_evidenza'] : $block['Block']['name_evidenza'];
                                                        $desc = $block['Block']['descrizione_evidenza'];
                                                        $link = fucking16_10izer(array('path' => $block['Block']['img_evidenza'], 'w' => 212 * 2, 'h' => 90 * 2, 'zc' => 0));
                                                        $path = $thumbnail->link(array('path' => $block['Block']['img_evidenza'], 'w' => 700, 'q' => 100, 'f' => 'jpg'));
                                                        ?>
                                                        <td style="padding: 5px 10px 5px 0; vertical-align: top">
                                                            <div class="">
                                                                <div class="img-thumbnail" style="width: 100px; height: 60px; padding: 4px">
                                                                    <div style="width: 100%; height: 100%; text-align: center; background-image: url('<?= $link; ?>'); background-repeat: no-repeat; background-position: center center; background-size: contain; ">
                                                                        <a href="<?= $block_link; ?>" title="<?= $title; ?>">
                                                                                <!-- <img src="<?= $link; ?>" alt=""> -->
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <?php
                                                    elseif (isset($block['Upload'][0]) && $block['Upload'][0]["group"] == "image"):
                                                        $path = fucking16_10izer(array('path' => $block['Upload'][0]["path"], 'w' => 96, 'h' => 56, 'zc' => 1, 'q' => 100, 'f' => 'jpg'));
                                                        ?>
                                                        <td style="padding: 5px 10px 5px 0; vertical-align: top">
                                                            <div class="">
                                                                <div class="img-thumbnail" style="width: 100px; height: 60px; padding: 4px">
                                                                    <div style="width: 100%; height: 100%; text-align: center; background-image: url('<?= $path; ?>'); background-repeat: no-repeat; background-position: center center; background-size: cover; ">
                                                                    <!-- <img src="<?= $path ?>" alt=""> -->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    <?php endif; ?>
                                                    <td style="padding: 5px; vertical-align: top; width: 100%">

                                                        <div class="post-content">

                                                            <h2>
                                                                <a href="<?= $block_link; ?>" title="<?= $block['Block']['title']; ?>">
                                                                    <?= $block['Block']['title']; ?>
                                                                </a>
                                                            </h2>

                                                            <!-- NON STILIZZATO -->
                                                            <!-- RIGA 226 -->
                                                            <? // if ($block['Block']['published_it'] != '' && $block['Block']['published_it'] != '00/00/0000' && ($data['Page']['title'] == "News" || $data['Page']['title'] == "News dalla redazione" || $data['Page']['title'] == "Ultim'ora")):  ?>
                                                           
                                                            <!-- //GIUSEPPE 2019-09-05 : inseriva  solo le date di pubblicazione di midland "Campionati/tornei" -->
                                                            <? if ($block['Block']['published_it'] != '' && $block['Block']['published_it'] != '00/00/0000'): ?>
                                                                <p style="margin-top: -10px"><span class="label label-info">del <?= $block['Block']['published_it'] ?></span></p>
                                                            <? endif; ?>

                                                            <p>
                                                                <?=
                                                                $this->Text->truncate(
                                                                        strip_tags($block['Block']['content']), 210, array(
                                                                    'ending' => ' ...',
                                                                    'exact' => false
                                                                        )
                                                                );
                                                                ?><a class="cyan-a" href="<?= $block_link; ?>" >Leggi tutto</a>
                                                            </p>


                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </article>





                                    <?php endif; ?> <!-- end block type -->
                                <?php else: ?> <!-- post 56 -->
                                    <?php if (0 == 0): //Mostra tutto   ?>

                                        <article class="post post-large" style="margin-left: 0;">
                                            <div class="post-content">
                                                <table>
                                                    <tr>
                                                        <!-- IMMAGINI STAFF-->
                                                        <?php
                                                        $i = 0;
                                                        foreach ($block['Upload'] as $attach):
                                                            ?>
                                                            <?php if (in_array($attach['ext'], $ext_img) && $attach['tag'] == 'link'): ?>
                                                                <?php if ($i++ == 0): ?>
                                                                    <?php
                                                                    $isVideo = 1;
                                                                    $link = fucking16_10izer(array('path' => $attach['path'], 'w' => 212 * 2, 'h' => 80 * 2, 'q' => 100));
                                                                    $path = $attach['path'];
                                                                    $title = ($attach['title'] != '') ? $attach['title'] : $attach['name'];
                                                                    $href = ($attach['description'] != '') ? $attach['description'] : 'javascript:;';
                                                                    ?>

                                                                    <td style="padding: 5px 10px 5px 0; vertical-align: top">
                                                                        <?php if (substr($attach['description'], 0, 7) == 'http://' && strlen($attach['description']) > 7): ?>
                                                                            <div class="">
                                                                                <div class="img-thumbnail" style="width: 100px; height: 60px; padding: 4px">
                                                                                    <a href="<?= $href; ?>" title="<?= $title; ?>">
                                                                                        <div style="width: 100%; height: 100%; text-align: center; background-image: url('<?= $link; ?>'); background-repeat: no-repeat; background-position: center center; background-size: contain;">
                                                                                        <!-- <img src="<?= $link; ?>" alt=""> -->
                                                                                        </div>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        <?php else: ?>
                                                                            <div class="">
                                                                                <div class="img-thumbnail" style="width: 100px; height: 60px; padding: 4px">

                                                                                    <div style="width: 100%; height: 100%; text-align: center; background-image: url('<?= $link; ?>'); background-repeat: no-repeat; background-position: center center; background-size: contain;">
                                                                                    <!-- <img src="<?= $link; ?>" alt=""> -->
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                <?php endif; ?>


                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                        <td style="padding: 5px; vertical-align: top; width: 100%">


                                                            <h2><?= $block['Block']['title']; ?></h2>
                                                            <?= $block['Block']['content']; ?>

                                                        </td>
                                                </table>
                                            </div>
                                        </article>
                                    <?php else: ?>
                                        <!-- NON STILIZZATO -->
                                        <!-- RIGA 291 -->
                                        <?
                                        //Genero link
                                        if ($block['Block']['url'] != '')
                                        {
                                            $block_link = $block['Block']['url'];
                                        }
                                        elseif ($block['Block']['url_page_id'] != '')
                                        {
                                            $block_link = $this->requestAction('/pages/getPageUrl/' . $block['Block']['url_page_id']);
                                        }
                                        else
                                        {
                                            $block_link = '/blocchi/' . $block['Block']['id'] . '/' . strtolower(Inflector::Slug($block['Block']['title'], '-'));
                                        }
                                        ?>
                                        <h1>
                                            <a href="<?= $block_link; ?>" title="<?= $block['Block']['title']; ?>">
                                                <?= $block['Block']['title']; ?>
                                            </a>
                                        </h1>

                                        <?php if ($block['Block']['published_it'] != '' && $block['Block']['published_it'] != '00/00/0000' && ($block['Page']['title'] == "News" || $block['Page']['title'] == "News dalla redazione" || $block['Page']['title'] == "Ultim'ora")): ?>
                                            <p style="margin-top: -10px"><span class="label label-info">del <?= $block['Block']['published_it'] ?></span></p><!-- solo per le news -->
                                        <?php endif; ?>

                                        <div class="block-preview<? if ($block['Block']['img_evidenza'] != ''): ?> with-preview<? endif; ?>">
                                            <?=
                                            $this->Text->truncate(
                                                    strip_tags($block['Block']['content']), 210, array(
                                                'ending' => ' ...',
                                                'exact' => false
                                                    )
                                            );
                                            ?>
                                        </div>

                                        <?php if ($block['Block']['img_evidenza'] != ''): ?>
                                            <div class="block-preview-img gruppo" id="123123123" style="clear: both;">
                                                <?php
                                                $title = ($block['Block']['descrizione_evidenza'] != '') ? $block['Block']['descrizione_evidenza'] : $block['Block']['name_evidenza'];
                                                $desc = $block['Block']['descrizione_evidenza'];
                                                $link = fucking16_10izer(array('path' => $block['Block']['img_evidenza'], 'w' => 212 * 2, 'h' => 90 * 2, 'zc' => 0));
                                                $path = $thumbnail->link(array('path' => $block['Block']['img_evidenza'], 'w' => 700, 'q' => 100, 'f' => 'jpg'));
                                                ?>
                                                <a title="<?= $title; ?>" href="<?= $block_link; ?>">
                                                    <img src="<?= $link; ?>" />
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?> <!-- fine post 56 -->			
                            <?php endforeach; ?>
                            <?= $this->element('/site/pagination', array('n' => $n_page, 'page_id' => $data['Page']['id'])); ?>
                        <?php endif; ?>




                    </div>
                </div>
            </div>
        </div>
        <!-- MENU DI DESTRA -->
        <? $esclusi = array(128, 129, 130); ?>
        <? if ($data['Page']['Genitore'] != 'Campionati/Tornei' && !in_array($data['Page']['id'], $esclusi)): ?>
            <div class="col-md-3">
                <aside class="sidebar">
                    <h4 class="heading-primary"><?= $data['Page']['Genitore']; ?></h4>
                    <?php if (count($data['Brothers'])): ?>
                        <ul class="nav nav-list narrow">
                            <?php foreach ($data['Brothers'] as $page): ?>
                                <?php $url = $this->requestAction('/pages/getPageUrl/' . $page['Page']['id']); ?>
                                <li <?php if ($page['Page']['id'] == $data['Page']['id']) echo 'class="active"'; ?>>
                                    <a href="<?= $url; ?>" title="<?= $page['Page']['title']; ?>">
                                        <?= $page['Page']['title']; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </aside>
            </div>
        <? endif; ?>


    </div> <!-- container -->
</div> <!-- MAIN -->


