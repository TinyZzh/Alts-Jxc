<?php
namespace Jxc\Impl\Util;
/**
 * Class MathUtil
 */
final class MathUtil {

    /**
     * PRD算法是否命中
     * @param int $prdRatio 概率(百分比) => 36.75 [36.75%]
     * @param int $prdI 连续未命中次数
     * @return bool 返回true表示概率命中($prdI 需要清0). false表示未命中
     */
    public static function isHitByPRD($prdRatio, $prdI = 0) {
        $prdC = ($prdRatio * 0.01) / ($prdRatio * 0.01 + 1) * 100;
        $prdD = 10000 / $prdRatio;
        return (mt_rand(0, $prdD) < ($prdI * ($prdRatio + $prdC * 0.58 * (1 - $prdRatio * 0.01) * (1 + $prdRatio * 0.01 * 1.005)) * 0.97));
    }

    /**
     * PRD算法必中所需的最大次数
     * @param $ratio
     * @return int
     */
    public static function maxCountByPRD($ratio) {
        $tempRatio = $ratio * 0.01;
        $prdC = $tempRatio / ($tempRatio + 1) * 100;
        $prdD = 10000 / $ratio;
        $singleRatio = ($ratio + $prdC * 0.58 * (1 - $tempRatio) * (1 + $tempRatio * 1.005)) * 0.97;
        var_dump($prdD / $singleRatio);
        return ceil($prdD / $singleRatio);
    }

    /**
     * @param int $count 计算次数
     * @param int $ratio 命中概率(显示)(单位:百分比)
     * @param int $prdI 初始连续未命中次数
     * @return array
     */
    public static function hitByPRD($count, $ratio, $prdI = 0) {
        $tempRatio = $ratio * 0.01;
        $prdC = $tempRatio / ($tempRatio + 1) * 100;
        $prdD = 10000 / $ratio;
        $singleRatio = ($ratio + $prdC * 0.58 * (1 - $tempRatio) * (1 + $tempRatio * 1.005)) * 0.97;
        $map = array();
        for ($i = 0; $i < $count; $i++) {
            if (mt_rand(0, $prdD) < ($prdI * $singleRatio)) {
                $map[] = $i;
                $prdI = 0;
            } else {
                $prdI++;
            }
        }
        return $map;
    }

}
/* End of file MathUtil.php */