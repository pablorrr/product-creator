<?php

use ProductCreator\controllers\ProductCreator_Controller_ErrorValidate;
use ProductCreator\libraries\ProductCreator;

$ProductCreator = new ProductCreator();
$action_params = array('view' => 'form', 'action' => 'save');//wykoorzytsnaie do atrybutu action formularza, dziekie temeu ///aktualna strona bedzie formularzem a akcja bedzie zchowywwyala dane zapisane w formularzu i przesylac je do bazy danych
$ProductCreatorErrorVal = new ProductCreator_Controller_ErrorValidate();

if ($Product->hasId()) {//jesli wpis ma swoje id , slide obiektem klasy slideproductentry
    $action_params['productid'] = $Product->getField('id');//nadanie w tablicy asocjacyjnej nowej pary productid=>id, dzieki temu //bedzie wyswietlany aktualny wpis z bazy danych w formularzu
}


?>
<form action="<?php echo $ProductCreator->getAdminPageUrl($action_params); ?>" method="post" id="product-hs-slide-form">

    <?php wp_nonce_field($ProductCreator->action_token); ?>

    <table class="form-table">

        <tbody>

        <tr class="form-field">
            <th>
                <label for="product-hs-prod-name">Nazwa produktu:</label>
            </th>
            <td>
                <input type="text" name="product[prod_name]" id="product-hs-prod-name"
                       value="<?php echo $Product->getField('prod_name'); ?>"/>

                <?php if ($ProductCreatorErrorVal->hasError('prod_name'))://jesli w slide jest error ?>
                    <p class="description error"><?php echo $ProductCreatorErrorVal->getError('prod_name'); ?></p>
                <?php else: ?>
                    <p class="description">To pole jest wymagane</p>
                <?php endif; ?>
            </td>
        </tr>


        <tr class="form-field">
            <th>
                <label for="product-hs-weight">Waga(kg):</label>
            </th>
            <td>
                <input type="text" name="product[weight]" id="product-hs-weight"
                       value="<?php echo $Product->getField('weight'); ?>"/>

                <?php if ($ProductCreatorErrorVal->hasError('weight')): ?>
                    <p class="description error"><?php echo $ProductCreatorErrorVal->getError('weight'); ?></p>
                <?php else: ?>
                    <p class="description">To pole jest opcjonalne</p>
                <?php endif; ?>

            </td>
        </tr>

        <tr class="form-field">
            <th>
                <label for="product-hs-weight">Height(m):</label>
            </th>
            <td>
                <input type="text" name="product[height]" id="product-hs-height"
                       value="<?php echo $Product->getField('height'); ?>"/>

                <?php if ($ProductCreatorErrorVal->hasError('height')): ?>
                    <p class="description error"><?php echo $ProductCreatorErrorVal->getError('height'); ?></p>
                <?php else: ?>
                    <p class="description">To pole jest opcjonalne</p>
                <?php endif; ?>

            </td>
        </tr>



        <tr class="form-field">
            <th>
                <label for="product-hs-color">Kolor</label>
            </th>
            <td>
                <input type="text" name="product[color]" id="product-hs-color"
                       value="<?php echo $Product->getField('color'); ?>"/>

                <?php if ($ProductCreatorErrorVal->hasError('color')): ?>
                    <p class="description error"><?php echo $ProductCreatorErrorVal->getError('color'); ?></p>
                <?php else: ?>
                    <p class="description">To pole jest wymagane</p>
                <?php endif; ?>
            </td>
        </tr>


        <tr class="form-field">
            <th>
                <label for="product-hs-volume">Objętość(dm3)</label>
            </th>
            <td>
                <input type="text" name="product[volume]" id="product-hs-volume"
                       value="<?php echo $Product->getField('volume'); ?>"/>

                <?php if ($ProductCreatorErrorVal->hasError('volume')): ?>
                    <p class="description error"><?php echo $ProductCreatorErrorVal->getError('volume'); ?></p>
                <?php else: ?>
                    <p class="description">To pole jest wymagane</p>
                <?php endif; ?>
            </td>
        </tr>


        <tr class="form-field">
            <th>
                <label for="product-hs-scent">Zapach</label>
            </th>
            <td>
                <input type="text" name="product[scent]" id="product-hs-scent"
                       value="<?php echo $Product->getField('scent'); ?>"/>

                <?php if ($ProductCreatorErrorVal->hasError('scent')): ?>
                    <p class="description error"><?php echo $ProductCreatorErrorVal->getError('scent'); ?></p>
                <?php else: ?>
                    <p class="description">To pole jest wymagane</p>
                <?php endif; ?>
            </td>
        </tr>


        <tr class="form-field">
            <th>
                <label for="product-hs-length">Długość(m)</label>
            </th>
            <td>
                <input type="text" name="product[length]" id="product-hs-length"
                       value="<?php echo $Product->getField('length'); ?>"/>

                <?php if ($ProductCreatorErrorVal->hasError('length')): ?>
                    <p class="description error"><?php echo $ProductCreatorErrorVal->getError('length'); ?></p>
                <?php else: ?>
                    <p class="description">To pole jest wymagane</p>
                <?php endif; ?>
            </td>
        </tr>


        <tr class="form-field">
            <th>
                <label for="product-hs-widthh">Szerokość(m)</label>
            </th>
            <td>
                <input type="text" name="product[width]" id="product-hs-width"
                       value="<?php  echo $Product->getField('width'); ?>"/>

                <?php if ($ProductCreatorErrorVal->hasError('width')): ?>
                    <p class="description error"><?php echo $ProductCreatorErrorVal->getError('width'); ?></p>
                <?php else: ?>
                    <p class="description">To pole jest wymagane</p>
                <?php endif; ?>
            </td>
        </tr>

        </tbody>

    </table>

    <p class="submit">
        <a href="#" class="button-secondary">Wstecz</a>
        &nbsp;<input type="submit" class="button-primary" value="Zapisz zmiany"/>
    </p>

</form>