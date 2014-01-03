jQuery(document).ready(function($) {
    var icon_url = $('#icon-url').val();
    $('#icon-zordform').css('background-image', 'url("'+icon_url+'")');

    $('.row-action').css('visibility', 'hidden');
    $('.row-action-div').mouseenter(function(){
        $('.row-action').css('visibility', 'visible');
    });
    $('.row-action-div').mouseleave(function(){
        $('.row-action').css('visibility', 'hidden');
    });

    $('.zordform-delete').click(function(){
        if (confirm(ajax_object.label_are_delete_form)) {
            var form_id = $(this).find('a').attr('ref');
            $.post(ajax_object.ajax_url, {form_id: form_id, action: 'zordform_delete_form'}, function(resp){
                if (resp == 'OK') {
                    window.location.reload();
                } else {
                    alert(ajax_object.label_smth_wrong);
                }
            })
        }
    });

    // Zord form edit form
    $('#add-field').live('click', function () {
        $.get(ajax_object.ajax_url, {action: 'zordform_add_field'}, function (resp) {
            $('#add-field').before(resp);
        });
    });

    $('.remove-field').live('click', function () {
        if (confirm(ajax_object.label_are_delete_field)) {
            $('div[ref='+$(this).attr('ref')+']').remove();
        }
    });
});
