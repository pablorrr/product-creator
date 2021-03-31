<?php


namespace ProductCreator\libraries;


class ProductCreator_HelpTab
{
    public function woo_prod_creator_help_tab()
    {
        $screen = get_current_screen();
        $text_one = 'When entering product properties, remember that the number of product properties is the same as in the adjacent columns. ';
        $text_two = 'If the property has no equivalent in adjacent columns, enter the default value which must be unique. ';
        $text_three = 'If the product does not show up, please refresh the page.';
        //wp_kese to create safe link https://developer.wordpress.org/reference/functions/wp_kses/
        $link_content_one = sprintf(wp_kses('<p>%1$s<a href="%2$s/public/img/example.png" target="%3$s">%4$s</a></p>',
            array('p' => array(), 'a' => array('href' => array(), 'target' => array()))), __('Take a look on example picture ', 'text-domain'),
            ProductCreator::$plugin_uri, '_blank', __('check the picture ', 'text-domain'));
        $link_content_two = sprintf(wp_kses('<p><a href="%1$s" target="%2$s">%3$s</a></p>',
            array('p' => array(), 'a' => array('href' => array(), 'target' => array()))),
            'https://websitecreator.cba.pl', '_blank', __('Author website ', 'text-domain'));
        // Add my_help_tab if current screen is My Admin Page
        $screen->add_help_tab(array(
            'id' => 'help_tab',
            'title' => __('Welcome', 'geocoder-map'),
            'content' => '<p>' . __('Thx you have choosen my plugin', 'geocoder-map') . '</p>',
        ));

        $screen->add_help_tab(array(
            'id' => 'help_tab_two',
            'title' => __('How to use plugin', 'geocoder-map'),
            'content' => '<p>' . __($text_one . $text_two . $text_three, 'simple-google-map-plugin') . '</p>',

        ));
        $screen->add_help_tab(array(
            'id' => 'help_tab_three',
            'title' => __('Another clues', 'geocoder-map'),
            'content' => $link_content_one,
        ));

        $screen->set_help_sidebar(
            '<p><strong>' . __('Quick Links', 'text-domain') . '</strong></p>' . $link_content_two
        );
    }

}