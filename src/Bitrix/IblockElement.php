<?php

    namespace Lacodda\BxFrame\Bitrix;

    use Bitrix\Iblock\ElementTable;
    use Bitrix\Main\Loader;
    use Lacodda\BxFrame\Helper\Helper;

    /**
     * Class IblockElement
     *
     * @package Lacodda\BxFrame\Bitrix
     * @author  Kirill Lahtachev <lahtachev@gmail.com>
     */
    class IblockElement

    {

        /**
         * @var array
         */
        public static $element = [];

        /**
         * @var array
         */
        public $filtered = [];

        /**
         * @param $id
         *
         * @return static
         */
        public static function find ($id)
        {
            $filter = ['ID' => $id];
            $select = [
                'ID',
                'NAME',
                'DEPTH_LEVEL',
            ];

            $rsElements = ElementTable::getList (['filter' => $filter, 'select' => $select]);

            $element = [];

            while ($arElement = $rsElements->fetch ())
            {
                $element[$arElement['ID']] = $arElement;
            }

            self::$element = $element;

            $instance = new static;

            return $instance;
        }

        /**
         * @param $name
         *
         * @return static
         */
        public static function findByName ($name)
        {
            $filter = ['%NAME' => $name];
            $select = [
                'ID',
                'NAME',
                'DEPTH_LEVEL',
            ];

            $rsElements = ElementTable::getList (['filter' => $filter, 'select' => $select]);

            $element = [];

            while ($arElement = $rsElements->fetch ())
            {
                $element[$arElement['ID']] = $arElement;
            }

            self::$element = $element;

            $instance = new static;

            return $instance;
        }

        /**
         * @param        $iblockId
         * @param null   $iblockElementId
         * @param string $active
         *
         * @return static
         */
        public static function where ($iblockId, $iblockSectionId = null, $active = 'Y')
        {
            $filter = [];
            $filter = array_merge ($filter, ['IBLOCK_ID' => $iblockId]);
            $filter = array_merge ($filter, ['ACTIVE' => $active]);
            $filter = $iblockSectionId ? array_merge ($filter, ['IBLOCK_SECTION_ID' => $iblockSectionId]) : $filter;
            $select = [
                'ID',
                'NAME',
                'DEPTH_LEVEL',
            ];

            $rsElements = ElementTable::getList (['filter' => $filter, 'select' => $select]);

            $element = [];

            while ($arElement = $rsElements->fetch ())
            {
                $element[$arElement['ID']] = $arElement;
            }

            self::$element = $element;

            $instance = new static;

            return $instance;
        }

        /**
         * @return $this
         */
        public function get ()
        {
            $this->filtered = self::$element;

            return $this;
        }

        /**
         * @return $this
         */
        public function id ()
        {
            $this->filtered = Helper::arrayColumn (self::$element, 'ID');

            return $this;
        }

        /**
         * @return $this
         */
        public function name ()
        {
            $this->filtered = Helper::arrayColumn (self::$element, 'NAME');

            return $this;
        }

        /**
         * @return $this
         */
        public function hierarchy ($sep = '.')
        {
            $id = Helper::arrayColumn (self::$element, 'ID');
            $depthLevel = Helper::arrayColumn (self::$element, 'DEPTH_LEVEL');
            $name = Helper::arrayColumn (self::$element, 'NAME');

            $hierarchy = array_map (
                function ($depthLevel, $name) use (&$sep)
                {
                    return sprintf ('%s %s', str_repeat ($sep, $depthLevel), $name);
                },
                $depthLevel,
                $name
            );

            $this->filtered = array_combine ($id, $hierarchy);

            return $this;
        }

        /**
         * @return array
         */
        public function all ()
        {
            return $this->filtered;
        }

        /**
         * @return mixed
         */
        public function first ()
        {
            return array_shift ($this->filtered);
        }
    }

    
