<?php 
defined('THINK_PATH') or die('系统异常');

use Common\Behavior\SearParamBehavior;
use Common\Behavior\UpdateLogEndBehavior;
use Common\Behavior\UpdateLogBehavior;
use Common\Behavior\CheckUJiaoMoneylMeiBehavior;
return array(
    'Search' => array(SearParamBehavior::class),
    ASDKLJHKJHJKHKUH => array('Common\Behavior\WhatAreYouDoingBehavior'),
    'UpdateLogStart' => array(UpdateLogBehavior::class),
    'check_money' => array(CheckUJiaoMoneylMeiBehavior::class),
    'UpdateLogEnd'   => array(UpdateLogEndBehavior::class)
);
