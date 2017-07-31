<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170731_095323_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            //收货人
            'name'=>$this->string(100)->comment('收货人'),
            'member_id'=>$this->integer(10)->comment('用户ID'),
            'province'=>$this->string(10)->comment('省'),
            'city'=>$this->string(10)->comment('市'),
            'area'=>$this->string(20)->comment('区/县'),
            'detailed_address'=>$this->string(100)->comment('详细地址'),
            'tel'=>$this->char(11)->comment('手机号'),
            'status'=>$this->integer(1)->comment('状态（1默认地址，0非默认）'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
