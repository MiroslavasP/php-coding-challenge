<?php

require_once("header.php");

?>
<div class="calendar">
    <p>The next delivery for <?= $address ?>:</p>
    <p class="first-delivery-date"><?= $next_delivery ?></p>
    <form class="scedule" method="post" action="<?= URL . 'modify' ?>">
        <?php foreach ($scedule as $date => $status) : ?>
            <div>
                <label for="<?= $date ?>"><?= $date ?></label>
                <input type="checkbox" id="<?= $date ?>" name="<?php echo ($date) ?>" <?php if (1 === $status) {
                                                                                            echo ('checked');
                                                                                        } ?>>
            </div>

        <?php endforeach ?>

        <p class="button"><input type="submit" value="Save changes" name="modify"></p>
    </form>
</div>
<?php

require_once("footer.php");

?>