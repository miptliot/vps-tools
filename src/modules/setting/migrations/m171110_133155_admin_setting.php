<?php
	use vps\tools\db\Migration;

	class m171110_133155_admin_setting extends Migration
	{
		/** @inheritdoc */
		public function up ()
		{
			$this->insert('auth_item', [ 'name' => 'admin_setting', 'type' => 1, 'created_at' => time(), 'fixed' => 1 ]);
		}

		/** @inheritdoc */
		public function down ()
		{
			$this->delete('auth_item', [ 'name' => 'admin_setting' ]);
		}
	}