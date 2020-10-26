//region Validation
validationForForm();
//endregion

//region Password Changed
$('#password_change').click(function() {
    $('#password').attr('disabled',!this.checked);
    $('#password_confirm').attr('disabled',!this.checked);

    $("#password").prop('required',this.checked);
    $("#password_confirm").prop('required', this.checked);
});
//endregion