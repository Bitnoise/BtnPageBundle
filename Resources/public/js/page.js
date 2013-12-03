//submit form on select change
$('#pageContainer').on('change', 'select.on-template-change', function() {

    // console.log($('option:selected', this).val());
    $(this).parents('form').submit();

    return false;
});