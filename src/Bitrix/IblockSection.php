<?php

    namespace Lacodda\BxFrame\Bitrix;

    use Bitrix\Iblock\SectionTable;
    use Bitrix\Main\Loader;
    use Lacodda\BxFrame\Helper\Helper;

    /**
     * Class IblockSection
     *
     * @package Lacodda\BxFrame\Bitrix
     * @author  Kirill Lahtachev <lahtachev@gmail.com>
     */
    class IblockSection

    {

        /**
         * @var array
         */
        public static $section = [];

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

            $rsSections = SectionTable::getList (['filter' => $filter, 'select' => $select]);

            $section = [];

            while ($arSection = $rsSections->fetch ())
            {
                $section[$arSection['ID']] = $arSection;
            }

            self::$section = $section;

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

            $rsSections = SectionTable::getList (['filter' => $filter, 'select' => $select]);

            $section = [];

            while ($arSection = $rsSections->fetch ())
            {
                $section[$arSection['ID']] = $arSection;
            }

            self::$section = $section;

            $instance = new static;

            return $instance;
        }

        /**
         * @param        $iblockId
         * @param null   $iblockSectionId
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

            $rsSections = SectionTable::getList (['filter' => $filter, 'select' => $select]);

            $section = [];

            while ($arSection = $rsSections->fetch ())
            {
                $section[$arSection['ID']] = $arSection;
            }

            self::$section = $section;

            $instance = new static;

            return $instance;
        }

        /**
         * @return $this
         */
        public function get ()
        {
            $this->filtered = self::$section;

            return $this;
        }

        /**
         * @return $this
         */
        public function id ()
        {
            $this->filtered = Helper::arrayColumn (self::$section, 'ID');

            return $this;
        }

        /**
         * @return $this
         */
        public function name ()
        {
            $this->filtered = Helper::arrayColumn (self::$section, 'NAME');

            return $this;
        }

        /**
         * @return $this
         */
        public function hierarchy ($sep = '.')
        {
            $id = Helper::arrayColumn (self::$section, 'ID');
            $depthLevel = Helper::arrayColumn (self::$section, 'DEPTH_LEVEL');
            $name = Helper::arrayColumn (self::$section, 'NAME');

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

        /**
         * @param $filter
         * @param $select
         *
         * @return mixed
         */
        public static function getSectionList ($filter, $select)
        {
            $dbSection = \CIBlockSection::GetList (
                Array (
                    'LEFT_MARGIN' => 'ASC',
                ),
                array_merge (
                    Array (
                        'ACTIVE'        => 'Y',
                        'GLOBAL_ACTIVE' => 'Y',
                    ),
                    is_array ($filter) ? $filter : Array ()
                ),
                false,
                array_merge (
                    Array (
                        'ID',
                        'IBLOCK_SECTION_ID',
                    ),
                    is_array ($select) ? $select : Array ()
                )
            );

            while ($arSection = $dbSection->GetNext (true, false))
            {

                $SID = $arSection['ID'];
                $PSID = (int) $arSection['IBLOCK_SECTION_ID'];

                $arLincs[$PSID]['CHILDS'][$SID] = $arSection;

                $arLincs[$SID] = &$arLincs[$PSID]['CHILDS'][$SID];
            }

            return array_shift ($arLincs);
        }
    }

    
