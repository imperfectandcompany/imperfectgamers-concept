<?php

$disable_sorting = getSetting('disable_sorting', 'value2');

$store = new store('raffle');

$sortArray = [
    "sortby" => "id",
    "search" => "%"
];

$store->setSortOptions($sortArray);

?>

<script type="text/javascript">
    $(document).ready(function (e) {
        $("#storeSidebar").on('submit', (function (e) {
            e.preventDefault();

            sideBar(this);

        }));

        function sideBar(form) {
            var sortby = $(form).find('#sortby').val();
            var search = $(form).find('input[type=text][name=search]').val();

            $('#raffles').html('Loading ...');

            $.ajax({
                url: "inc/ajax/store.php",
                type: "POST",
                data: "action=get&type=raffle&sortby=" + sortby + "&search=" + search,
                cache: false,
                success: function (data) {
                    $('#raffles').html(data);
                }
            });
        }
    });
</script>

<div class="row">
    <div class="col-12">
        <div class="header">
            <?= lang('select_raffle'); ?>
        </div>
    </div>
</div>

<?php if ($disable_sorting == 0) { ?>
    <div class="darker-box">
        <?= $store->getSidebar(); ?>
    </div>
<?php } ?>

<div class="row">
    <div class="col-12">
        <?php if (tos::getLast() < getSetting('tos_lastedited', 'value3') && prometheus::loggedIn()) { ?>
            <div class="info-box">
                <form method="POST" style="width: 40%;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                    <h2><?= lang('tos'); ?></h2>
                    <?= lang('tos_edited'); ?><br>
                    <input type="submit" class="btn btn-success" value="<?= lang('tos_accept'); ?>" name="tos_submit"
                           style="margin-top: 5px;">
                </form>
            </div>
        <?php } ?>

        <br>
        <?php $message->display(); ?>

        <div id="raffles">
            <?php
            echo $store->display();
            ?>
        </div>
    </div>
</div>