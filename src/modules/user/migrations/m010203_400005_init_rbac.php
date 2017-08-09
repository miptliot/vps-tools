<?php
	use yii\db\Migration;

	/**
	 * Class m010101_100001_init_rbac
	 */
	class m010203_400005_init_rbac extends Migration
	{
		/** @inheritdoc */
		public function up ()
		{
			$tableOptions = null;
			if ($this->db->driverName === 'mysql')
			{
				$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
			}
			$this->createTable('auth_rule', [
				'name'       => $this->string(64)->notNull(),
				'data'       => $this->text()->null(),
				'created_at' => $this->integer()->null(),
				'updated_at' => $this->integer()->null()
			], $tableOptions);
			$this->addPrimaryKey('name', 'auth_rule', 'name');

			$this->createTable('auth_item', [
				'name'        => $this->string(64)->notNull(),
				'type'        => $this->integer()->notNull(),
				'description' => $this->text()->null(),
				'rule_name'   => $this->string(64)->null(),
				'data'        => $this->text()->null(),
				'created_at'  => $this->integer()->null(),
				'updated_at'  => $this->integer()->null()
			], $tableOptions);
			$this->addPrimaryKey('name', 'auth_item', 'name');

			$this->createIndex('type', 'auth_item', 'type');
			$this->createIndex('auth_rule', 'auth_item', 'rule_name');

			$this->addForeignKey('auth_rule', 'auth_item', 'rule_name', 'auth_rule', 'name', 'set null', 'cascade');

			$this->createTable('auth_item_child', [
				'parent' => $this->string(64)->notNull(),
				'child'  => $this->string(64)->notNull()
			], $tableOptions);
			$this->addPrimaryKey('parent', 'auth_item_child', [ 'parent', 'child' ]);

			$this->createIndex('child', 'auth_item_child', 'child');

			$this->addForeignKey('parent', 'auth_item_child', 'parent', 'auth_item', 'name', 'cascade', 'cascade');
			$this->addForeignKey('child', 'auth_item_child', 'child', 'auth_item', 'name', 'cascade', 'cascade');

			$this->createTable('auth_assignment', [
				'item_name'  => $this->string(64)->notNull(),
				'user_id'    => $this->integer()->notNull(),
				'created_at' => $this->integer()->null()
			], $tableOptions);
			$this->addPrimaryKey('item_name', 'auth_assignment', [ 'item_name', 'user_id' ]);

			$this->createIndex('user_id', 'auth_assignment', 'user_id');

			$this->addForeignKey('auth_item', 'auth_assignment', 'item_name', 'auth_item', 'name', 'cascade', 'cascade');
			$this->addForeignKey('user_id', 'auth_assignment', 'user_id', 'user', 'id', 'cascade', 'cascade');

			$auth = Yii::$app->getAuthManager();

			$viewMenu = $auth->createPermission('viewMenu');
			$viewMenu->description = 'View the menu';
			$auth->add($viewMenu);

			$viewTopBlock = $auth->createPermission('viewTopBlock');
			$viewTopBlock->description = 'View the top block';
			$auth->add($viewTopBlock);

			$admin = $auth->createRole('admin');
			$auth->add($admin);
			$auth->addChild($admin, $viewMenu);
			$auth->addChild($admin, $viewTopBlock);

			$unverified = $auth->createRole('registered');
			$auth->add($unverified);

			//$this->renameColumn("user", 'isApproved', 'active');
			$userClass = Yii::$app->getModule('users')->modelUser;
			$users = $userClass::find()->all();
			if (count($users) > 0)
				foreach ($users as $user)
				{
					if ($user->active == 1)
						$user->assignRole('admin');
					else
						$user->assignRole('registered');
				}
		}

		/** @inheritdoc */
		public function down ()
		{
			//$this->renameColumn("user", 'active', 'isApproved');

			$auth = Yii::$app->getAuthManager();
			$auth->removeAll();

			$this->dropTable('auth_assignment');
			$this->dropTable('auth_item_child');
			$this->dropTable('auth_item');
			$this->dropTable('auth_rule');
		}
	}