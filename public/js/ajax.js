jQuery(document).ready(function ($) {
    $('button#click').css('visibility', 'hidden');
    $('form#ajax-form').on('submit', function (e) {
        e.preventDefault();
        var prod_name = $('form#ajax-form > #prod-name').val();
        var col_name = $('form#ajax-form > #col-name').val();
        $.ajax({
            url: cpm_object_first.ajax_url,
            type: 'post',
            data: {
                action: 'set_form_one',
                nonce: cpm_object_first.nonce,
                prod_name: prod_name,
                col_name: col_name

            }, success: function (response) {

                console.log(response.data);
                $('h5#info-one').html('').append('You have choosen following product name and product property :');
                $('p#prod-name').html('').append('Product name: ' + response.data.prod_name);
                $('p#col-name').html('').append('Property name: ' + response.data.col_name);
                $('h4#info-two').html('').append('2. Step second: please select product property to match available products properties');
                $('div#form-one').html('').append(response.data.form_first);
                $('button#click').css('visibility', 'visible');
            },
            error: function (response) {
                alert('error');
            }
        });

    });//end  first ajax

    //second ajax request handle

    $('#click').on('click', function (e) {
        e.preventDefault();
        var attr_val = $('form#ajax-first-form > #attr-val').val();

        $.ajax({
            url: cpm_object_first.ajax_url,
            type: 'post',
            data: {
                action: 'set_form_two',
                nonce: cpm_object_second.nonce,
                attr_val: attr_val
            }, success: function (response) {

                console.log(response.data);
                $('h4#info-three').html('').append('3. Step third: please select product properties to create product.' );
                $('p#attr-name').html('').append('Property value is : ' + attr_val);
                $('div#form-second').html('').append(response.data.form_second);
            },
            error: function (response) {
                alert('error');
            }
        });

    });//end  second ajax

});
