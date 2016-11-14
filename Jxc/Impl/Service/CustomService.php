<?php

namespace Jxc\Impl\Service;

use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Core\JxcService;
use Jxc\Impl\Dao\CustomerDao;

class CustomService extends JxcService {

    private $customDao;

    public function __construct() {
        parent::__construct();
        $this->customDao = new CustomerDao(JxcConfig::$DB_Config);
    }

    /**
     * 获取顾客列表
     * @param $request
     * @return array
     */
    public function getCustomList($request) {
        return array('state' => 1, 'data' => $this->customDao->w2uiSelect());
    }








}