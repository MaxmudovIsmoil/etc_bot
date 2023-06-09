<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class Helper
{
    public static function getName($bot = null)
    {
        return ($bot == null) ?: $bot->user()->first_name ?: ($bot->user()->last_name ?: $bot->user()->username);
    }

    public static function checkPhoneNumber($phone)
    {
        $phone_length = Str::length($phone);

        if (($phone_length == 9))
            return self::phone_preg_much($phone);

        $phone_code = Str::substr($phone, 0, 4);
        $phone = Str::substr($phone, 4, 13);

        if(($phone_length == 13) && ($phone_code == '+998'))
            return self::phone_preg_much($phone);

        return false;
    }

    public static function phone_preg_much($phone)
    {
        return preg_match("/^[0-9]{9}$/", $phone) ? true : false;
    }


    public static function getText($bot = null)
    {
        return (object)[
            'start' => "<b>" . self::getName($bot) . "</b>. Добро пожаловать в бот ИСТ Телеком",
            'tariff' => "Введите логин и пароль!",

            'order' => "<b>Пожалуйста вышлите запрос, в формате:</b>\n\n1. Ф.И.О. заявителя:\n2. Адрес подключения:\n3. Контактный номер:\n4. Прикрепите местоположение:",
            'order_fio' => "1. Ф.И.О. заявителя:\n<i>(1 \ 3)</i>",
            'order_address' => "2. Адрес подключения:\n<i>(2 \ 3)</i>",
            'order_phone' => "3. Контактный номер:\nнапример: <i>901234567</i>\n<i>(3 \ 3)</i>",
            'order_location' => "4. Прикрепите местоположение:\n<i>(4 \ 4)</i>",
            'back' => "Возвращение",
            'tariff_login_error' => "Ошибка входа или пароля,\nпожалуйста, введите еще раз",
            'new_order_error' => "форма введена ошибка пожалуйста,\nвведите снова",

            "order_list_show_title" => 'Подтвердить',
            'order_fio_key' => "1.  Ф.И.О. заявителя: \t",
            'order_address_key' => "2. Адрес подключения: \t",
            'order_phone_key' => "3. Контактный номер: \t",
            'order_location_key' => "4. Прикрепите местоположение: \t",
            "new_order_saved" => "Ваш заказ принят.\nНаши сотрудники свяжутся с вами.\nБлагодарим Вас за Ваше внимание!.",

            'start_to_again' => "<b>Начать заново</b>",
            'order_location_warning' => 'Пожалуйста, введите свое местоположение',
            'order_phone_warning' => 'Пожалуйста, введите свой номер телефона',
            'order_no_btn_text' => "Вы отменили свой заказ",

            'tariff_login' => "Введите Ваш логин",
            'tariff_password' => "Введите пароль",
            'show_balance' => "<b>Баланс:</b> ",
            "login_password_error" => "Login yoki parolda xatolik bor boshidan kiriting\nLogin!",

            "tariff_info_title" => "<b>Информация по тарифу</b>",
            "tariff_full_name" => "<b>Наименование:</b> \t",
            "tariff_address" => "<b>Адрес подключения:</b> \t",
            "tariff_code" => "<b>Тариф:</b> \t",
            "tariff_status" => "<b>Статус:</b> \t",
            "tariff_done_text" => "За дополнительной информации обратитесь на номер: <b>+998 78 150 00 16</b>\nСпасибо что Вы с нами!",

            "tariff_done_close_text" => "Для возврата в главное меню нажмите - Назад"
        ];
    }


}
