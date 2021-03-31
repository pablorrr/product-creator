<form id="ajax-form" action="" method="post"
      enctype="multipart/form-data">
    <h4>1.Step first: please select product name and product property</h4>
    <label for="prod_name">Please choose product name</label>
    <select id="prod-name" name="prod_name">
        <?php if (!empty($model->getProducts())): ?>
            <?php foreach ($model->getProducts() as $singleProduct) : ?>
                <?php if (!empty($singleProduct->prod_name)): ?>
                    <option value="<?php echo esc_attr($singleProduct->prod_name); ?>">
                        <?php echo $singleProduct->prod_name; ?>
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
    </br>
    <label for="col_name">Please choose product property</label>
    <select id="col-name" name="col_name">
        <?php if (!empty($model->getProducts())): ?>
            <?php foreach ($model->getColName() as $singleColName): ?>
                <option value="<?php echo esc_attr($singleColName); ?>">
                    <?php echo $singleColName; ?>
                </option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
    </br>
    <input type="submit" class="button-secondary btn-info" value="click to create product property">
</form>
