<?php

namespace Jxc\Impl\Libs;

/**
 * w2ui  tool.
 * Class W2UI
 * @package Jxc\Impl\Libs
 */
class W2UI {

    public static function w2uiColumn($field, $caption, $size = '5%', $editable = array(), $render = '') {
        $column = array(
            'field' => $field,
            'caption' => $caption,
            'size' => $size,
        );
        if ($editable)
            $column['editable'] = $editable;
        if ($render)
            $column['render'] = $render;
        return $column;
    }

    public static function w2uiEditable($type = 'text', $items = null, $showAll = false) {
        $editable = array(
            'type' => $type,
        );
        if ($items)
            $editable['items'] = $items;
        if ($showAll)
            $editable['showAll'] = $showAll;
        return $editable;
    }

    public static function w2uiItems($map = array()) {
        $items = array();
        foreach ($map as $k => $v) {
            $items['id'] = $k;
            $items['text'] = $v;
        }
        return $items;
    }


}