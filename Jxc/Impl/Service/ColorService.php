<?php

namespace Jxc\Impl\Service;

use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Core\JxcService;
use Jxc\Impl\Dao\ColorDao;
use Jxc\Impl\Dao\CustomerDao;
use Jxc\Impl\Libs\DateUtil;
use Jxc\Impl\Util\GameUtil;
use Jxc\Impl\Vo\VoColor;
use Jxc\Impl\Vo\VoCustomer;

/**
 * 颜色服务
 */
final class ColorService extends JxcService {

    private $colorDao;

    public function __construct() {
        parent::__construct();
        $this->colorDao = new ColorDao(JxcConfig::$DB_Config);
    }

    /**
     * 获取popup列表的数据
     * @param $voOp
     * @param $request
     * @return array
     */
    public function w2Records($voOp, $request) {
        return array('status' => 'success', 'records' => $this->colorDao->w2gridRecords());
    }

    /**
     *
     * @param $voOp
     * @param $request
     * @return array
     */
    public function w2GetColorInfo($voOp, $request) {
        $data = $this->colorDao->select();
        $array = array();
        foreach ($data as $v) {
            $v->recid = $v->color_id;
            $array[] = $v;
        }
        return array('status' => 'success', 'records' => $array);
    }

    /**
     * w2保存更新颜色信息
     * @param $voOp
     * @param $request
     * @return array
     */
    public function saveColorInfo($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('changes'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $changes = $request['changes'];
        $aryId = array();
        foreach ($changes as $change) {
            $aryId[$change['recid']] = 1;
        }
        $ids = array_keys($aryId);
        $existMap = $this->colorDao->selectById($ids);
        $updateAry = array();
        foreach ($changes as $change) {
            $id = $change['recid'];
            if (isset($existMap[$id])) {  //  update
                $voColor = $existMap[$id];
                if ($voColor instanceof VoColor) {
                    $voColor->convert($change);
                    $this->colorDao->updateByFields($voColor, array_keys($change));
                    $voColor->recid = $voColor->color_id;
                    $updateAry[] = $voColor;
                }
            } else {    //  insert
                $voColor = new VoColor();
                $voColor->convert($change);
                $voColor = $this->colorDao->insert($voColor);
                $voColor->recid = $voColor->color_id;
                $voColor->depId = $id;
                $updateAry[] = $voColor;
            }
        }
        return array('status' => 'success', 'updates' => $updateAry);
    }

    /**
     * w2删除颜色
     * @param $voOp
     * @param $request
     * @return array
     */
    public function removeColorInfo($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('selected'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        foreach ($request['selected'] as $pdt_id) {
            $this->colorDao->delete($pdt_id);
        }
        return array('status' => 'success', 'deleted' => $request['selected']);
    }
}