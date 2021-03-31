<?php



use ProductCreator\libraries\ProductCreator;
use ProductCreator\models\ProductCreator_Model;


$ProductCreator = new ProductCreator(); ?>
<form method="get" action="<?php echo $ProductCreator->getAdminPageUrl(); ?>" id="paste-hs-form-1">

    <input type="hidden" name="page" value="<?php echo ProductCreator::$plugin_id; ?>"/>
    <input type="hidden" name="paged" value="<?php echo $Pagination->getCurrPage(); ?>"/>

    <!-- Sortuj według-->
    <select name="orderby">
        <?php  foreach (ProductCreator_Model::getOrderByOpts() as $key => $val): ?>
        <option
            <?php  echo ($val == $Pagination->getOrderBy()) ? 'selected="selected"' : ''; ?>
                value="<?php  echo $val; ?>">
            <?php  echo $key; ?>
        </option>
        <?php   endforeach; ?>
    </select>

    <select name="orderdir">
        <?php if ($Pagination->getOrderDir() == 'asc') ://zaznaczenie atrybutu selected w zaleznosci od wybranego kier //sortowania?>
            <option selected="selected" value="asc">Rosnąco</option>
            <option value="desc">Majeląco</option>
        <?php else: ?>
            <option value="asc">Rosnąco</option>
            <option selected="selected" value="desc">Majeląco</option>
        <?php endif; ?>
    </select>

    <input type="submit" class="button-secondary" value="Sortuj"/>

</form>


<form action="<?php echo $ProductCreator->getAdminPageUrl(array('view' => 'index', 'action' => 'bulk')); ?>"
      method="post"
      id="product-hs-form-2" onsubmit="return confirm('Czy na pewno chcesz zastosować zmiany masowe?')">

    <?php wp_nonce_field($ProductCreator->action_token.'bulk');// generowanie tokena do tego formularza
    ?>

    <div class="tablenav">

        <div class="alignleft actions">

            <select name="bulkaction">
                <option value="0">Masowe działania</option>
                <option value="delete">Usuń</option>
            </select>

            <input type="submit" class="button-secondary" value="Zastosuj"/>

        </div>

        <div class="tablenav-pages">
            <span class="displaying-num"><?php echo $Pagination->getTotalCount(); ?> produkty </span>

            <?php
            $curr_page = $Pagination->getCurrPage();
            $last_page = $Pagination->getLastPage();

            $first_disabled = '';
            $last_disabled = '';

            $url_params = array(
                'orderby' => $Pagination->getOrderBy(),
                'orderdir' => $Pagination->getOrderDir()
            );


            $url_params['paged'] = 1;
            $first_page_url = $ProductCreator->getAdminPageUrl($url_params);

            $url_params['paged'] = $curr_page - 1;
            $prev_page_url = $ProductCreator->getAdminPageUrl($url_params);

            $url_params['paged'] = $last_page;
            $last_page_url = $ProductCreator->getAdminPageUrl($url_params);

            $url_params['paged'] = $curr_page + 1;
            $next_page_url = $ProductCreator->getAdminPageUrl($url_params);


            if ($curr_page == 1) {
                $first_page_url = '#';
                $prev_page_url = '#';

                $first_disabled = 'disabled';
            } else
                if ($curr_page == $last_page) {
                    $last_page_url = '#';
                    $next_page_url = '#';

                    $last_disabled = 'disabled';
                }
            ?>

            <span class="pagination-links">
                <a href="<?php echo $first_page_url; ?>" title="Idź do pierwszej strony"
                   class="first-page <?php echo $first_disabled; ?>">«</a>&nbsp;&nbsp;
                <a href="<?php echo $prev_page_url; ?>" title="Idź do poprzedniej strony"
                   class="prev-page <?php echo $first_disabled; ?>">‹</a>&nbsp;&nbsp;
                
                <span class="paging-input"><?php echo $curr_page ?> z <span
                            class="total-pages"><?php echo $last_page ?></span></span>
                
                &nbsp;&nbsp;<a href="<?php echo $next_page_url; ?>" title="Idź do następnej strony"
                               class="next-page <?php echo $last_disabled; ?>">›</a>
                &nbsp;&nbsp;<a href="<?php echo $last_page_url; ?>" title="Idź do ostatniej strony"
                               class="last-page <?php echo $last_disabled; ?>">»</a>
                
            </span>
        </div>

        <div class="clear"></div>

    </div>


    <table class="widefat">
        <thead>
        <tr><!--th - opis komorki ( header komorki)  -->
            <th class="check-column"><input type="checkbox"/></th>
            <th>ID</th>
            <th>Product Name</th>
            <th>Weight</th>
            <th>Height</th>
            <th>Color</th>
            <th>Volume(dm3)</th>
            <th>Scent</th>
            <th>Lenght(m)</th>
            <th>Width(m)</th>

        </tr>
        </thead>
        <tbody id="the-list">

        <?php if ($Pagination->hasItems()): ?>

            <?php foreach ($Pagination->getItems() as $i => $item): ?>

                <tr <?php echo ($i % 2) ? "style='background-color:lightgrey'" : " "; ?>>
                    <th class="check-column">
                        <input type="checkbox" value="<?php echo $item->id; ?>" name="bulkcheck[]"/>
                    </th>
                    <!-- DLA ID-->

                    <td><?php echo $item->id; ?></td>


                    <td><?php echo $item->prod_name; ?>

                        <div class="row-actions">
                                <span class="edit">
                                    <a class="edit"
                                       href="<?php echo $ProductCreator->getAdminPageUrl(array('view' => 'form', 'productid' => $item->id)); ?>">Edytuj</a>
                                </span> |
                            <span class="trash">
                                    <?php
                                    $token_name = $ProductCreator->action_token . $item->id;

                                    $del_url = $ProductCreator->getAdminPageUrl(array('action' => 'delete', 'productid' => $item->id));
                                    ?>
                                    <a class="delete" href="<?php echo wp_nonce_url($del_url, $token_name) ?>"
                                       onclick="return confirm('Are you sure to remove this product?')">Remove</a>
                                </span>
                        </div>
                    </td>


                    <td><?php echo $item->weight; ?></td>
                    <td><?php echo $item->height; ?></td>
                    <td><?php echo $item->color; ?></td>
                    <td><?php echo $item->volume; ?></td>
                    <td><?php echo $item->scent; ?></td>
                    <td><?php echo $item->length; ?></td>
                    <td><?php echo $item->width; ?></td>

                </tr>


            <?php endforeach; ?>


        <?php else: ?>
            <tr>
                <td colspan="8">No products in DB</td>
            </tr>
        <?php endif; ?>
        </tbody>

    </table>


    <div class="tablenav">
        <div class="tablenav-pages">
            
            <span class="pagination-links">
                Przejdź do strony
                
                <?php

                $url_params = array(
                    'orderby' => $Pagination->getOrderBy(),
                    'orderdir' => $Pagination->getOrderDir()
                );

                for ($i = 1; $i <= $Pagination->getLastPage(); $i++) {

                    $url_params['paged'] = $i;
                    $url = $ProductCreator->getAdminPageUrl($url_params);

                    if ($i == $Pagination->getCurrPage()) {
                        echo "&nbsp;<strong>{$i}</strong>";
                    } else {
                        echo '&nbsp;<a href="' . $url . '">' . $i . '</a>';
                    }

                }
                ?>
            </span>

        </div>

        <div class="clear"></div>
    </div>

</form>
