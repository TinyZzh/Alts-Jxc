<?php

namespace Jxc\Impl\Service;


use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Core\JxcService;
use Jxc\Impl\Dao\ProductDao;
use Jxc\Impl\Libs\DateUtil;
use Jxc\Impl\Util\GameUtil;
use Jxc\Impl\Vo\VoProduct;

class ProductService extends JxcService {

    private $productDao;

    public function __construct() {
        parent::__construct();
        $this->productDao = new ProductDao(JxcConfig::$DB_Config);
    }

    /**
     * 获取产品信息列表
     * @param $request
     * @return array
     */
    public function getPdtList($request) {
        $list = $this->productDao->selectAll();
        $array = array();
        foreach ($list as $k => $v) {
            if ($v instanceof VoProduct) {
                $array[] = $v->voToW2ui($v);
            }
        }
        return array('status' => 'success', 'records' => $array);
    }

    /**
     * 保存产品信息
     * @param $request
     * @return array
     */
    public function savePdtInfo($request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('changes'))) {
            return array('state' => 'error', 'msg' => 'Undefined field : ' . $verify);
        }
        $changes = $request['changes'];
        $aryId = array();
        foreach ($changes as $change) {
            $aryId[$change['recid']] = 1;
        }
        $ids = array_keys($aryId);
        $existMap = $this->productDao->selectById($ids);
        $updateAry = array();
        foreach ($changes as $change) {
            $id = $change['recid'];
            if (isset($existMap[$id])) {  //  update
                $voProduct = $existMap[$id];
                if ($voProduct instanceof VoProduct) {
                    $voProduct->w2uiToVo($change);
                    $this->productDao->updateByFields($voProduct);
                    $updateAry[] = $voProduct->voToW2ui($voProduct);
                }
            } else {    //  insert
                $voProduct = new VoProduct();
                $voProduct->w2uiToVo($change);
                $voProduct->datetime = DateUtil::makeTime();
                $voProduct = $this->productDao->insert($voProduct);
                $ua = $voProduct->voToW2ui($voProduct);
                $ua->depId = $id;
                $updateAry[] = $ua;
            }
        }
        return array('status' => 'success', 'updates' => $updateAry);
    }

    public function removePdtInfo($request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('selected'))) {
            return array('state' => 'error', 'msg' => 'Undefined field : ' . $verify);
        }
        foreach ($request['selected'] as $pdt_id) {
            $this->productDao->delete($pdt_id);
        }
        return array('status' => 'success', 'deleted' => $request['selected']);
    }

    /**
     * 采购
     */
    public function procure($request) {



    }

    public function getBaseShopInfoList($request) {



    }


    public function sell($request) {

        return array('xx' => 1);
    }


}