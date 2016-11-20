<?php

namespace Jxc\Impl\Libs;
use Jxc\Impl\Core\Vo;
use Jxc\Impl\Vo\VoProduct;

/**
 * w2ui  tool.
 * Class W2UI
 * @package Jxc\Impl\Libs
 */
class W2UI {

    /**
     * @param $vo VoProduct|Vo
     * @return mixed
     */
    public static function objToW2ui($vo) {
        $w2Data = $vo->toArray();;
        $w2Data['recid'] = $vo->pdt_id;
        //  counts
        foreach ($vo->pdt_counts as $k => $v) {
            $f = 'pdt_count_' . $k;
            $w2Data[$f] = $v;
        }
        unset($w2Data['pdt_counts']);
        return $w2Data;
    }

    public static function w2uiToObj($obj, $data) {
        $fields = array_keys($data);
        foreach ($fields as $k => $fieldName) {
            if (strpos($fieldName, 'pdt_count_') === 0) {   //  FALSE  不同于 0
                $fields[] = "pdt_counts";
                $var = substr($fieldName, strlen('pdt_count_'));
                if (is_numeric($var))
                    $obj->pdt_counts[$var] = $data[$fieldName];
                unset($fields[$k]);
            } else if (isset($obj->$fieldName) || property_exists($obj, $fieldName)) {
                if (is_array($data[$fieldName]) || is_object($data[$fieldName])) {
                    $obj->$fieldName = $data[$fieldName]['text'];   //  w2ui-list
                } else
                    $obj->$fieldName = $data[$fieldName];
            } else {
//no-op
            }
        }
        return $obj;
    }


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