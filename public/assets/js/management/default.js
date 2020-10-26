//region Variables
var Messages = {
    DELETE_CONFIRM_MESSAGE_FOR_ROW: 'İlgili satırı (kaydı) silmek istediğinizden emin misiniz?',
    DELETE_CONFIRM_MESSAGE_FOR_IMAGE: 'İlgili resmi silmek istediğinizden emin misiniz?',
    DELETE_CONFIRM_MESSAGE_FOR_LOGO: 'Logoyu silmek istediğinizden emin misiniz?',
    CANNOT_DONE_FOR_THE_LOGGED_IN_USER: 'Giriş yapmış kullanıcı için bu işlem yapılamaz.',
    CONFIRM_CLEAR_FORM: 'Formu temizlemek istediğinizden emin misiniz?',
    CONFIRM_SMS: 'SMS\'i göndermek istediğinizden emin misiniz?'
};
//endregion

//noinspection Eslint
function validationForForm() {
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
}

//region Other Bindings
$('.row-delete').bind('click', function() {
    if (!confirm(Messages.DELETE_CONFIRM_MESSAGE_FOR_ROW)) {
        return false;
    } else {
        return true;
    }
});

//region Logout
$('.logout').bind('click', function() {
    if (!confirm('Çıkış yapmak üzeresiniz.\nOnaylıyor musunuz?')) {
        return false;
    } else {
        loadingModalShow();
    }
});
//endregion
//endregion

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
})

//region Chechk All
$(document).ready(function() {
    $("#checkedAll").change(function(){
        if(this.checked){
            $(".checkSingle").each(function(){
                this.checked=true;
                $('#btn-all-delete').prop('disabled', false);
            });
        }else{
            $(".checkSingle").each(function(){
                this.checked=false;
                $('#btn-all-delete').prop('disabled', true);
            });
        }
    });

    $(".checkSingle").click(function () {
        if ($(this).is(":checked")){

            $('#btn-all-delete').prop('disabled', false);

            var isAllChecked = 0;
            $(".checkSingle").each(function(){
                if(!this.checked){
                    isAllChecked = 1;
                }
            })
            if(isAllChecked === 0){
                $("#checkedAll").prop("checked", true);
            }
        }else {
            $("#checkedAll").prop("checked", false);
            $('#btn-all-delete').prop('disabled', true);
        }
    });
});
//endregion