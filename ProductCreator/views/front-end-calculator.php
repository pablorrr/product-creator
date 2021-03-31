<?php

use ProductCreator\libraries\ProductCreator;
use ProductCreator\models\ProductCreator_Model_CreateProd;


$model = new ProductCreator_Model_CreateProd();
$productCreator = new ProductCreator();


require('starter-form.php'); ?>
    </br>
    <h5 id="info-one"></h5>
    <p id="prod-name"></p><p id="col-name"></p>
    <h4 id="info-two"></h4>
    <div id="form-one"></div>
    <button id="click"class="button-secondary btn-info" >please click to display available products properties</button>
    <h4 id="info-three"></h4>
    <p id="attr-name"></p>
    <div id="form-second"></div>
<?php
if (isset($_POST['submit'])) {
    $model->prodCreatorcreateProd($_POST);
}
echo
'<script>
 //do not send form when page refreshed
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>';
?>