<?php

//region Namespace
namespace App\Config\Message;
//endregion

//region Using

//endregion

class General
{
    public const NEW_RECORD_SUCCESS = 'Yeni kayıt başarıyla eklenmiştir.';
    public const UPDATE_RECORD_SUCCESS = 'Kayıt başarıyla düzenlenmiştir.';
    public const DELETE_RECORD_SUCCESS = 'Kayıt başarıyla silinmiştir.';

    public const NEW_RECORD_ERROR = 'Yeni kayıt eklenirken hata oluştu.';
    public const UPDATE_RECORD_ERROR = 'Kayıt düzenlenirken hata oluştu.';
    public const DELETE_RECORD_ERROR = 'Kayıt silinirken hata oluştu.';

    public const FORM_FIELD_VALIDATION='Lütfen form alanlarını kontrol ediniz!';

    public const NOTHING_CHANGE = 'Güncellenecek yeni bir veri yok. Herhangi bir değişiklik bulunamadı.';
    public const NOTHING_DELETE = 'Silinecek bir veri yok.';

    public const PASSWORD_CHANGE_SUCCESS = 'Şifreniz başarıyla değiştirilmiştir.';

    public const NOTHING_MAIL = 'Bu e-posta adresi kayıtlı değildir.';
    public const ACCOUNT_PASSIVE = 'Hesap aktif değildir.';
    public const WRONG_PASSWORD = 'Şifrenizi kontrol ediniz.';
    public const SUCCESS_LOGIN = 'Başarıyla giriş yaptınız.';

    public const NEW_MEMBER_SUCCESS = 'Üyeliğiniz başarıyla yapılmıştır.';
    public const NEW_MEMBER_ERROR = 'Üyelik kaydınız yapılmadı! Bilgilerinizi kontrol ederek tekrar deneyiniz.';

    public const UPLOAD_IMAGE_SUCCESS = 'Yeni resim başarıyla yüklenmiştir.';
    public const UPLOAD_IMAGE_ERROR = 'Yeni resim yüklenirken hata oluştu.';

    public const OFFER_MAIL_SEND_SUCCESS = 'Teklifiniz başarıyla gönderilmiştir.';
    public const OFFER_MAIL_SEND_ERROR = 'Teklifiniz gönderilirken hata oluştu.';

    public const MAIL_SEND_SUCCESS = 'Mesajınız başarıyla gönderilmiştir.';
    public const MAIL_SEND_ERROR = 'Mesajınız gönderilirken hata oluştu.';

    public const FORM_METHOD_ERROR = 'Form methodu hatalıdır.';

    public const ROUTE_PARAM_ERROR = 'Parametre hatalıdır.';
}