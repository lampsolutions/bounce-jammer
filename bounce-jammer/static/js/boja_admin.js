jQuery(document).ready(function() {
    jQuery(".btn-pref .btn").click(function () {
        jQuery(".btn-pref .btn").removeClass("btn-info").addClass("btn-default");
        jQuery(this).removeClass("btn-default").addClass("btn-info");
    });


    jQuery('input[type=radio][name=boja_mode]').change(function() {
        if (this.value == "0" || this.value == "2") {
            jQuery('.boja_mode_child').attr('disabled', 'DISABLED');
        } else {
            jQuery('.boja_mode_child').removeAttr('disabled');
        }
    });
    jQuery('input[type=radio][name=boja_mode]:checked').trigger('change');
});