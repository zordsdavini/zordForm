jQuery(document).ready(function($) {
    $('.zordform-result-button').click(function(){
        var form_id = $(this).attr('ref');
        var data = {form_id: form_id, action: 'zordform_calculate_form'};
        $('.zordform_form_' + form_id).each(function(i, e){
            data[$(e).attr('name')] = $(e).val();
        });
        $.get(ajax_object.ajax_url, data, function (resp) {
            $('.zordform_result_' + form_id).val(resp);
        });
    })
});
