<?php

use yii\db\Migration;

class m170728_064500_alter_user_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('user','last_login_time','integer');
        $this->addColumn('user','last_login_ip','integer');

    }

    public function safeDown()
    {
        $this->dropColumn('user','last_login_time');
        $this->dropColumn('user','last_login_ip');
    }

}
