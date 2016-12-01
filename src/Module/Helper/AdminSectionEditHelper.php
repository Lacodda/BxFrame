<?php

    namespace Lacodda\BxFrame\Module\Helper;

        /**
         * Класс-обертка для хелпера редактирования разделов.
         *
         * Все хелперы отвечающие за редактирование разделов должны наследовать от этого класса. Название класса используется
         * для определения к какому типу принадлежит хелпер:
         * - список элементов,
         * - редактирования элементов,
         * - список разделов,
         * - редактирование раздела.
         *
         * @see    AdminBaseHelper::getHelperClass
         *
         * @author Kirill Lahtachev <lahtachev@gmail.com>
         */
    /**
     * Class AdminSectionEditHelper
     *
     * @package Lacodda\BxFrame\Module\Helper
     */
    class AdminSectionEditHelper
        extends AdminEditHelper
    {
    }