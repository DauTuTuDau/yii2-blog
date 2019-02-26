<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use yii\db\Migration;
use yii\db\Schema;

/**
 * CLass m141208_201480_blog_init
 * @package diazoxide\blog\migrations
 *
 * Create blog tables.
 *
 * Will be created 4 tables:
 * - `{{%blog_category}}` - Blog category
 * - `{{%blog_post}}` -
 * - `{{%blog_comment}}` -
 * - `{{%blog_tag}}` -
 */
class m180408_201482_blog_init extends Migration
{
    use \diazoxide\blog\traits\ModuleTrait;

    public $rules = [
        'diazoxide\blog\rbac\BlogAuthorRule',
    ];
    public $permissions = [
        ['BLOG_BULK_COMMENTS', 'Bulk blog comments'],
        ['BLOG_CONFIRM_ALL_COMMENTS', 'Confirm all blog comments'],
        ['BLOG_CREATE_CATEGORY', 'Create blog category'],
        ['BLOG_CREATE_COMMENT', 'Create blog comment'],
        ['BLOG_CREATE_POST', 'Create blog post'],
        ['BLOG_CREATE_TAG', 'Create blog tag'],
        ['BLOG_DELETE_ALL_COMMENTS', 'Delete all blog comments'],
        ['BLOG_DELETE_CATEGORY', 'Delete blog category'],
        ['BLOG_DELETE_COMMENT', 'Delete blog comment'],
        ['BLOG_DELETE_POST', 'Delete blog post'],
        ['BLOG_DELETE_TAG', 'Delete blog tag'],
        ['BLOG_UPDATE_CATEGORY', 'Update blog category'],
        ['BLOG_UPDATE_COMMENT', 'Update comment'],
        ['BLOG_UPDATE_OWN_POST', 'Update own blog post', ['blogIsAuthor']],
        ['BLOG_UPDATE_POST', 'Update blog post'],
        ['BLOG_UPDATE_TAG', 'Update blog tag'],
        ['BLOG_VIEW_CATEGORIES', 'View blog categories'],
        ['BLOG_VIEW_CATEGORY', 'View blog category'],
        ['BLOG_VIEW_COMMENT', 'View blog comment'],
        ['BLOG_VIEW_COMMENTS', 'View blog comments'],
        ['BLOG_VIEW_POST', 'View blog post'],
        ['BLOG_VIEW_POSTS', 'View blog posts'],
        ['BLOG_VIEW_TAG', 'View blog tag'],
        ['BLOG_VIEW_TAGS', 'View blog tags'],
    ];

    public $roles = [
        ['BLOG_ADMIN', 'Blog Administrator', [
            'BLOG_BULK_COMMENTS',
            'BLOG_CONFIRM_ALL_COMMENTS',
            'BLOG_CREATE_CATEGORY',
            'BLOG_CREATE_COMMENT',
            'BLOG_CREATE_POST',
            'BLOG_CREATE_TAG',
            'BLOG_DELETE_ALL_COMMENTS',
            'BLOG_DELETE_CATEGORY',
            'BLOG_DELETE_COMMENT',
            'BLOG_DELETE_POST',
            'BLOG_DELETE_TAG',
            'BLOG_UPDATE_CATEGORY',
            'BLOG_UPDATE_COMMENT',
            'BLOG_UPDATE_OWN_POST',
            'BLOG_UPDATE_POST',
            'BLOG_UPDATE_TAG',
            'BLOG_VIEW_CATEGORIES',
            'BLOG_VIEW_CATEGORY',
            'BLOG_VIEW_COMMENT',
            'BLOG_VIEW_COMMENTS',
            'BLOG_VIEW_POST',
            'BLOG_VIEW_POSTS',
            'BLOG_VIEW_TAG',
            'BLOG_VIEW_TAGS',
        ]],
        ['BLOG_MANAGER', 'Blog Manager', [
            'BLOG_BULK_COMMENTS',
            'BLOG_CONFIRM_ALL_COMMENTS',
            'BLOG_CREATE_CATEGORY',
            'BLOG_CREATE_COMMENT',
            'BLOG_CREATE_POST',
            'BLOG_CREATE_TAG',
            'BLOG_DELETE_ALL_COMMENTS',
            'BLOG_DELETE_CATEGORY',
            'BLOG_DELETE_COMMENT',
            'BLOG_DELETE_POST',
            'BLOG_DELETE_TAG',
            'BLOG_UPDATE_CATEGORY',
            'BLOG_UPDATE_COMMENT',
            'BLOG_UPDATE_OWN_POST',
            'BLOG_UPDATE_POST',
            'BLOG_UPDATE_TAG',
            'BLOG_VIEW_CATEGORIES',
            'BLOG_VIEW_CATEGORY',
            'BLOG_VIEW_COMMENT',
            'BLOG_VIEW_COMMENTS',
            'BLOG_VIEW_POST',
            'BLOG_VIEW_POSTS',
            'BLOG_VIEW_TAG',
            'BLOG_VIEW_TAGS',
        ]],
        ['BLOG_EDITOR', 'Blog Editor', [
            'BLOG_CREATE_POST',
            'BLOG_UPDATE_OWN_POST',
            'BLOG_VIEW_CATEGORIES',
            'BLOG_VIEW_CATEGORY',
            'BLOG_VIEW_COMMENT',
            'BLOG_VIEW_COMMENTS',
            'BLOG_VIEW_POST',
            'BLOG_VIEW_POSTS',
            'BLOG_VIEW_TAG',
            'BLOG_VIEW_TAGS',
        ]],
    ];

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        if ($this->db->getTableSchema('{{%blog_category}}', true) === null) {
            // MySql table options

            // table blog_category
            $this->createTable(
                '{{%blog_category}}',
                [
                    'id' => Schema::TYPE_PK,
                    'parent_id' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
                    'title' => Schema::TYPE_STRING . '(255) NOT NULL',
                    'slug' => Schema::TYPE_STRING . '(128) NOT NULL',
                    'icon_class' => Schema::TYPE_STRING . '(255) DEFAULT NULL',
                    'banner' => Schema::TYPE_STRING . '(255) ',
                    'is_nav' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1',
                    'is_featured' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1',
                    'sort_order' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 50',
                    'page_size' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 10',
                    'template' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT "post"',
                    'redirect_url' => Schema::TYPE_STRING . '(255) DEFAULT NULL',
                    'status' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1',
                    'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                    'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL'
                ],
                $tableOptions
            );

            // Indexes
            $this->createIndex('is_nav', '{{%blog_category}}', 'is_nav');
            $this->createIndex('sort_order', '{{%blog_category}}', 'sort_order');
            $this->createIndex('status', '{{%blog_category}}', 'status');
            $this->createIndex('created_at', '{{%blog_category}}', 'created_at');

        }

        if ($this->db->getTableSchema('{{%blog_post}}', true) === null) {

            // table blog_post
            $this->createTable(
                '{{%blog_post}}',
                [
                    'id' => Schema::TYPE_PK,
                    'category_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                    'title' => Schema::TYPE_STRING . '(255) NOT NULL',
                    'brief' => Schema::TYPE_TEXT,
                    'content' => Schema::TYPE_TEXT . ' NOT NULL',
                    'tags' => Schema::TYPE_STRING . '(255) NOT NULL',
                    'slug' => Schema::TYPE_STRING . '(128) NOT NULL',
                    'banner' => Schema::TYPE_STRING . '(255) ',
                    'is_slide' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1',
                    'click' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
                    'user_id' => Schema::TYPE_INTEGER . '',
                    'status' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1',
                    'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                    'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL'
                ],
                $tableOptions
            );

            // Indexes
            $this->createIndex('category_id', '{{%blog_post}}', 'category_id');
            $this->createIndex('is_nav', '{{%blog_post}}', 'is_slide');
            $this->createIndex('status', '{{%blog_post}}', 'status');
            $this->createIndex('created_at', '{{%blog_post}}', 'created_at');

            // Foreign Keys
            $this->addForeignKey('{{%FK_post_category}}', '{{%blog_post}}', 'category_id', '{{%blog_category}}', 'id', 'CASCADE', 'CASCADE');
            $userClass = \dektrium\user\models\User;
            $userPK = 'id';
            $this->addForeignKey('{{%FK_post_user}}', '{{%blog_post}}', 'user_id', $userClass::tableName(), $userPK, 'CASCADE', 'CASCADE');

        }

        if ($this->db->getTableSchema('{{%blog_comment}}', true) === null) {

            // table blog_comment
            $this->createTable(
                '{{%blog_comment}}',
                [
                    'id' => Schema::TYPE_PK,
                    'post_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                    'content' => Schema::TYPE_TEXT . ' NOT NULL',
                    'author' => Schema::TYPE_STRING . '(128) NOT NULL',
                    'email' => Schema::TYPE_STRING . '(128) NOT NULL',
                    'url' => Schema::TYPE_STRING . '(128) NULL',
                    'status' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1',
                    'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                    'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL'
                ],
                $tableOptions
            );

            // Indexes
            $this->createIndex('post_id', '{{%blog_comment}}', 'post_id');
            $this->createIndex('status', '{{%blog_comment}}', 'status');
            $this->createIndex('created_at', '{{%blog_comment}}', 'created_at');

            // Foreign Keys
            $this->addForeignKey('{{%FK_comment_post}}', '{{%blog_comment}}', 'post_id', '{{%blog_post}}', 'id', 'CASCADE', 'CASCADE');
        }

        if ($this->db->getTableSchema('{{%blog_tag}}', true) === null) {

            // table blog_tag
            $this->createTable(
                '{{%blog_tag}}',
                [
                    'id' => Schema::TYPE_PK,
                    'name' => Schema::TYPE_STRING . '(128) NOT NULL',
                    'frequency' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1',
                ],
                $tableOptions
            );

            // Indexes
            $this->createIndex('frequency', '{{%blog_tag}}', 'frequency');
        }

        //$this->removeAllAuthItems();

        $this->registerRules();

        $this->registerPermissions();

        $this->registerRoles();

    }

    /**
     * @throws Exception
     */
    public function registerPermissions()
    {
        $auth = Yii::$app->authManager;

        foreach ($this->permissions as $permission) {

            $p = $auth->createPermission($permission[0]);
            $p->description = $permission[1];
            if (isset($permission[2])) {
                foreach ($permission[2] as $ruleName) {
                    $p->ruleName = $ruleName;
                }
            }
            $auth->remove($p);
            $auth->add($p);
        }


    }

    /**
     * @throws Exception
     */
    public function registerRoles()
    {
        $auth = Yii::$app->authManager;

        foreach ($this->roles as $role) {
            $r = $auth->createRole($role[0]);
            $r->description = $role[1];
            $auth->remove($r);
            $auth->add($r);
            if (isset($role[2])) {
                foreach ($role[2] as $permissionName) {
                    $permission = $auth->getPermission($permissionName);
                    $auth->addChild($r, $permission);
                }
            }

        }
    }


    /**
     * @throws Exception
     */
    public function registerRules()
    {
        $auth = Yii::$app->authManager;

        foreach ($this->rules as $key => $rule) {
            $r = new $rule();
            $auth->remove($r);
            $auth->add($r);
        }
    }

    /**
     * @throws Exception
     */
    public function removeAllAuthItems()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%blog_tag}}');
        $this->dropTable('{{%blog_comment}}');
        $this->dropTable('{{%blog_post}}');
        $this->dropTable('{{%blog_category}}');
    }
}
