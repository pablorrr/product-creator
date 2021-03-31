<div class="wrap">
    <?php
    use ProductCreator\controllers\ProductCreator_Controller_FlashMsg;
    use ProductCreator\libraries\ProductCreator;

    $ProductCreator = new ProductCreator();
    $FlashMsg = new ProductCreator_Controller_FlashMsg();
    ?>
    <h2>
        <a href="<?php echo $ProductCreator->getAdminPageUrl(); ?>">Product Table</a>
        <a class="add-new-h2"
           href="<?php echo $ProductCreator->getAdminPageUrl(array('view' => 'form')); ?>">Add new product</a>
    </h2>


    <?php if ($FlashMsg->hasFlashMsg()): ?>

        <div id="message"
             class="<?php echo $FlashMsg->getFlashMsgStatus(); ?>">
            <p><?php echo $FlashMsg->getFlashMsg(); ?></p>
        </div>
    <?php endif; ?>


    <?php if (isset($view)) {
        require_once $view;
    } ?>


    <br style="clear: both;">

</div>