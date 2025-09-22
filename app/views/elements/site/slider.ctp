
<?php
$images = [];
$videos = [];

foreach ($data['Upload'] as $attach)
{
    if (in_array($attach['ext'], $ext_img) && $attach['tag'] != 'link')
    {
        if ($attach['group'] == "youtube" || $attach['group'] == "vimeo")
        {

            if ($attach['group'] == "youtube")
            {
                $video_echo = "data-ytid=\"" . substr($attach['name'], strpos($attach['name'], "watch?v=") + strlen("watch?v=")) . "\" ";
                //GIUSEPPE 2017-06-23 -----------------------------
                $page = explode('=', $attach['name']);
                $path = '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $page[1] . '" frameborder="0" allowfullscreen></iframe>';
            }
            else
            {
                $video_echo = "data-ytid=";
                //GIUSEPPE 2017-06-23 -----------------------------
                $page = explode('/', $attach['name']);
                $num = count($page);
                $path = '<iframe src="https://player.vimeo.com/video/' . $page[$num - 1] . '" width="640" height="274" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
            }

            $isVideo = 2;

            //GIUSEPPE 2017-06-23 - - COMMENTATI PERCHE' DAVANO RISULTATO VUOTO
            //$path = json_decode(file_get_contents("http://api.embed.ly/1/oembed?width=650&url=" . $attach['name'] . "&format=json&key=4e2c4fced87511e0b7154040d3dc5c07"), 1);
            //$path = $path['html'];




            $description = $attach['description'];

            $videos[] = [
                "path" => $path,
                "description" => $description,
                "group" => $attach["group"]
            ];
        }
        else
        {
            $isVideo = 0;
            $path = $thumbnail->link(array('path' => $attach['path'], 'w' => 650 * 2, 'h' => 365 * 2, 'q' => 100, 'f' => 'jpg'));
            $link = $thumbnail->link(array('path' => $attach['path'], 'w' => 150, 'h' => 93.75, 'q' => 100, "zc" => 1, 'f' => 'jpg'));
            $description = (empty($attach['description'])) ? $attach['title'] : $attach['description'];

            $images[] = [
                "path" => $path,
                "link" => $link,
                "description" => $description,
                "isEvidenza" => $attach["isEvidenza"],
                'title' => $attach['title'],
                'name' => $attach['name']
            ];
        }
    }
}
?>


<?php if (count($images) == 1): ?>

<?php elseif (count($images)): ?>


    <div class="row">
        <div class="col-md-12">
            <hr class="tall">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="lightbox" data-plugin-options='{"delegate": "a", "type": "image", "gallery": {"enabled": true}, "mainClass": "mfp-with-zoom", "zoom": {"enabled": true, "duration": 300}}'>
                <div class="owl-carousel owl-theme stage-margin" data-plugin-options='{"items": 6, "margin": 10, "loop": false, "nav": true, "dots": false, "stagePadding": 40}'>

                    <?php foreach ($images as $image): ?>
                        <?php if ($image["isEvidenza"] != 1): ?>
                            <div>

                                <!-- Visualizzazione descrizione e titolo didascalie fotogallery -->
                                <?
                                $title = "";

                                if ($image['title'] != "")
                                {
                                    $title = $image['title'] . "<br><small>" . $image['description'] . "</small>";
                                }
                                else
                                {
                                    $title = $image['description'];
                                }
                                ?>

                                <!-- <a <? if (isset($tooltip)): ?> title="<?= (($image['title']) ? $image['title'] : $image['name']); ?>"  <? endif; ?> class="img-thumbnail img-thumbnail-hover-icon mb-xs mr-xs" style="width: 150px; background-image: url(/img/img-loader.gif); background-position: center center; background-repeat: no-repeat;" href="<?= $image['path'] ?>"> -->
                                <a <? if (isset($tooltip)): ?> title="<?= (($image['title']) ? $image['title'] : $image['name']); ?>"  <? endif; ?> class="img-thumbnail img-thumbnail-hover-icon mb-xs mr-xs" style="width: 150px; background-image: url(/img/img-loader.gif); background-position: center center; background-repeat: no-repeat;" href="<?= $image['path'] ?>"  title="<?= $title ?>">
                                    <img class="img-responsive lazy" data-original="<?= $image['link']; ?>"  src="" alt="">
                                </a>
                                <!-- //////////////////////////////////////////////// -->
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>


<?php endif; ?>


<?php foreach ($videos as $video): ?>
    <div class="row">
        <div class="col-md-12">
            <hr class="tall">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="embed-responsive embed-responsive-16by9">
                <?= $video['path']; ?>
            </div>
        </div>
    </div>


<?php endforeach ?>



<script type="text/javascript">

    $(window).load(function ()
    {
        $("img.lazy").lazyload({
            threshold: 1500
        });
    });
</script>







