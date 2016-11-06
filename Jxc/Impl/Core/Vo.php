<?php
/**
 * Created by PhpStorm.
 * User: TinyZ
 * Date: 2016/10/27
 * Time: 0:01
 */

namespace Jxc\Impl\Core;


class Vo {

    /**
     * 当SELECT指定字段值的时候,建议使用。获取表全部字段时,推荐使用字符串'*'
     * @param null $fields
     * @return string
     */
    public function selectClos($fields = null) {
        if ($fields == null) {
            return '*';
        } else {
            $columns = '';
            foreach ((array)$fields as $field) {
                if (isset($this->$field) || property_exists($this, $field)) {
                    $columns .= '`' . $field . '`,';
                }
            }
            return substr($columns, 0, -1);
        }
    }

    /**
     * 转换VO为键值对
     * @param array $fields
     * @return array
     */
    public function toArray($fields = array()) {
        $map = array();
        if ($fields == null) {
            foreach ($this as $field => $value) {
                $map[$field] = $value;
            }
        } else {
            foreach ($fields as $field) {
                if (isset($this->$field) || property_exists($this, $field)) {
                    $map[$field] = $this->$field;
                }
            }
        }
        return $map;
    }

    /**
     * 转换VO为键值对 -   排除值为null的字段
     * @param array $fields
     * @return array
     */
    public function toArrayWithoutNull($fields = array()) {
        $map = array();
        if ($fields == null) {
            foreach ($this as $field => $value) {
                if ($value == null)
                    continue;
                $map[$field] = $value;
            }
        } else {
            foreach ($fields as $field) {
                if (isset($this->$field)) {
                    $map[$field] = $this->$field;
                }
            }
        }
        return $map;
    }

    /**
     * 赋值
     * @param $data
     */
    public function convert($data) {
        if (is_array($data)) {
            foreach ($this as $key => $value) {
                if (isset($data[$key])) {
                    $this->$key = $data[$key];
                }
            }
        } else {
            foreach ($this as $key => $value) {
                if (isset($data->$key)) {
                    $this->$key = $data->$key;
                }
            }
        }
    }

    /**
     * @param null $dataFields
     * @return array
     * @deprecated
     */
    public function dataArray($dataFields = null) {
        $map = array();
        if ($dataFields == null) {
            foreach ($this as $field => $value) {
//                if ($value === null)
//                    continue;
                $map[$field] = (is_array($value) || is_object($value)) ? json_encode($value) : $value;
            }
        } else {
            foreach ((array)$dataFields as $field) {
                if (isset($this->$field) || property_exists($this, $field)) {
                    $value = $this->$field;
                    $map[$field] = (is_array($value) || is_object($value)) ? json_encode($value) : $value;
                }
            }
        }
        return $map;
    }

    /**
     * 转换数据
     * @param $data
     * @deprecated
     */
    public function parse($data) {
        if (is_array($data)) {
            foreach ($this as $key => $value) {
                if (isset($data[$key]) && $data[$key]) {
                    $v = $data[$key];
                    if (is_numeric($v)) {
                        $this->$key = $v;
                    } else if (is_string($v)) {
                        if (strpos($v, '{') === 0 || strpos($v, '[') === 0) {
                            $this->$key = json_decode($v, true);
                        } else {
                            $this->$key = $v;
                        }
                    } else {
                        $this->$key = $v;
                    }
                }
            }
        } else {
            foreach ($this as $key => $value) {
                if (isset($data->$key) && $data->$key) {
                    $v = $data->$key;
                    if (is_numeric($v)) {
                        $this->$key = $v;
                    } else if (is_string($v)) {
                        if (strpos($v, '{') === 0 || strpos($v, '[') === 0) {
                            $this->$key = json_decode($v, true);
                        } else {
                            $this->$key = $v;
                        }
                    } else {
                        $this->$key = $v;
                    }
                }
            }
        }
    }
}