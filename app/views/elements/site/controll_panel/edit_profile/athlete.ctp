<script type="text/javascript">

    $(function ()
    {

        $("body").delegate('.isNumber', 'keydown', function (e)
        {

            var code = e.keyCode;

            if (isNaN(String.fromCharCode(code)) && code != 8 && code != 40 && code != 38 && code != 37 && code != 39 && code != 116 && code != 9 && code != 46)
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
                        <li class="active">Profilo utente</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="/vendor/theme.admin.extension.css">
    <link rel="stylesheet" href="/vendor/theme.extension.css">
    <div class="container" id="main-custom"> 
        <div class="row">
            <div class="col-md-12">
                <div class="tabs tabs-bottom tabs-center tabs-simple">
                    <?$page = "utente";?>
                   <? include (APP.'views/elements/site/controll_panel/edit_profile/menu_bar.ctp');?>
                 
                    <div id="tabsNavigationSimpleIcons1" class="tab-pane">


                        <div style="padding: 20px;">
                            <?=
                            $this->Form->create('Athlete', [
                                'url' => '/gestione/profilo/' . $this->data['Athlete']['Atleta'] . '/' . 'Athlete', 'type' => 'file',
                                'id' => 'profile-form',
                                "class" => "form-horizontal form-bordered"]);
                            ?>

                            <div class="col-md-12 pinpin">
                                <div class="col-md-9">
                                    <?= $this->element('forms/athlete'); ?>
                                </div>
                                <div class="col-md-3">
                                    <div class="pin-wrapper" style="height: 223px;">
                                        <aside class="sidebar" id="sidebar" data-plugin-sticky="" data-plugin-options="{&quot;minWidth&quot;: 991, &quot;containerSelector&quot;: &quot;.pinpin&quot;, &quot;padding&quot;: {&quot;top&quot;: 110}}" style="width: 263px;">
                                            <?= $this->Form->submit('Modifica profilo', array('type' => 'submit', 'class' => 'btn btn-lg btn-info')); ?>
                                        </aside>
                                    </div>
                                </div>
                            </div>
                            <?= $this->Form->end() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


