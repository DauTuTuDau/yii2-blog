<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog\models;

use diazoxide\blog\Module;
use diazoxide\blog\traits\ModuleTrait;
use diazoxide\blog\traits\StatusTrait;
use diazoxide\blog\widgets\Feed;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * This is the model class for table "blog_category".
 *
 * Это досталось от китайского модуля, еще не рефакторил
 *
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $title
 * @property string $slug
 * @property string $banner
 * @property integer $is_nav
 * @property integer $sort_order
 * @property integer $page_size
 * @property string $template
 * @property string $redirect_url
 * @property string icon_class
 * @property string icon
 * @property string read_more_text
 * @property string read_icon_class
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property BlogPost[] $blogPosts
 * @property string titleWithIcon
 * @property string url
 * @property BlogCategory childs
 * @property BlogWidgetType widgetType
 */
class BlogCategory extends \yii\db\ActiveRecord
{
    use StatusTrait, ModuleTrait;

    const IS_NAV_YES = 1;
    const IS_NAV_NO = 0;

    const IS_FEATURED_YES = 1;
    const IS_FEATURED_NO = 0;


    const PAGE_TYPE_LIST = 'list';
    const PAGE_TYPE_PAGE = 'page';

    private $_isNavLabel;
    private $_status;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%blog_category}}';
    }

    /**
     * @inheritdoc
     */
    public static function getOneIsNavLabel($isNav = null)
    {
        if ($isNav) {
            $arrayIsNav = self::getArrayIsNav();
            return $arrayIsNav[$isNav];
        }

        return;
    }


    /**
     * @return array
     */
    public static function getArrayIsNav()
    {
        return [
            self::IS_NAV_YES => Module::t('YES'),
            self::IS_NAV_NO => Module::t('NO'),
        ];
    }

    /**
     * @return array
     */
    public static function getArrayIsFeatured()
    {
        return [
            self::IS_FEATURED_YES => Module::t('YES'),
            self::IS_FEATURED_NO => Module::t('NO'),
        ];
    }

    /**
     * Это досталось от китайского модуля, еще не рефакторил
     *
     * @param int $parentId parent category id
     * @param array $array category array list
     * @param int $level category level, will affect $repeat
     * @param int $add times of $repeat
     * @param string $repeat symbols or spaces to be added for sub category
     * @return array  category collections
     */

    static public function get($parentId = 0, $array = array(), $level = 0, $add = 2, $repeat = '　')
    {
        $strRepeat = '';
        // add some spaces or symbols for non top level categories
        if ($level > 1) {
            for ($j = 0; $j < $level; $j++) {
                $strRepeat .= $repeat;
            }
        }

        // i feel this is useless
        if ($level > 0) {
            $strRepeat .= '';
        }

        $newArray = array();
        $tempArray = array();

        //performance is not very good here
        foreach (( array )$array as $v) {
            if ($v['parent_id'] == $parentId) {
                $newArray [] = array(
                    'id' => $v['id'],
                    'title' => $v['title'],
                    'parent_id' => $v['parent_id'],
                    'sort_order' => $v['sort_order'],
                    'banner' => $v->getThumbFileUrl('banner', 'thumb'), //'postsCount'=>$v['postsCount'],
                    'icon_class' => $v['icon_class'],
                    'is_nav' => $v['is_nav'],
                    'is_featured' => $v['is_featured'],
                    'template' => $v['template'],
                    'status' => $v->getStatus(),
                    'created_at' => $v['created_at'],
                    'updated_at' => $v['updated_at'],
                    'redirect_url' => $v['redirect_url'],
                    'str_repeat' => $strRepeat,
                    'str_label' => $strRepeat . $v['title'],
                );

                $tempArray = self::get($v['id'], $array, ($level + $add), $add, $repeat);
                if ($tempArray) {
                    $newArray = array_merge($newArray, $tempArray);
                }
            }
        }
        return $newArray;
    }

    /**
     * Это досталось от китайского модуля, еще не рефакторил
     * капец тут страшно всё непонятно
     *
     * return all sub categorys of a parent category
     * @param int $parentId
     * @param array $array
     * @return array
     */

    static public function getCategory($parentId = 0, $array = array())
    {
        $newArray = array();
        foreach ((array)$array as $v) {
            if ($v['parent_id'] == $parentId) {
                $newArray[$v['id']] = array(
                    'text' => $v['title'] . ' 导航[' . ($v['is_nav'] ? Module::t('CONSTANT_YES') : Module::t('CONSTANT_NO')) . '] 排序[' . $v['sort_order'] .
                        '] 类型[' . ($v['page_type'] == 'list' ? Module::t('PAGE_TYPE_LIST') : Module::t('PAGE_TYPE_PAGE')) . '] 状态[' .
                        F::getStatus2($v['status']) . '] [<a href="' . Yii::app()->createUrl('/category/update', array('id' => $v['id'])) . '">修改</a>][<a href="'
                        . Yii::app()->createUrl('/category/create', array('id' => $v['id'])) . '">增加子菜单</a>]&nbsp;&nbsp[<a href="' .
                        Yii::app()->createUrl('/category/delete', array('id' => $v['id'])) . '">删除</a>]',
                    //'children'=>array(),
                );

                $tempArray = self::getCategory($v['id'], $array);
                if ($tempArray) {
                    $newArray[$v['id']]['children'] = $tempArray;
                }
            }
        }
        return $newArray;
    }

    /**
     * @param int $parentId
     * @param array $array
     * @return int|string
     */
    static public function getCategoryIdStr($parentId = 0, $array = array())
    {
        $str = $parentId;
        foreach ((array)$array as $v) {
            if ($v['parent_id'] == $parentId) {

                $tempStr = self::getCategoryIdStr($v['id'], $array);
                if ($tempStr) {
                    $str .= ',' . $tempStr;
                }
            }
        }
        return $str;
    }

    /**
     * @param int $id
     * @param array $array
     * @return array|int
     */
    static public function getCategorySub2($id = 0, $array = array())
    {
        if (0 == $id) {
            return 0;
        }

        $arrayResult = array();
        $rootId = self::getRootCategoryId($id, $array);
        foreach ((array)$array as $v) {
            if ($v['parent_id'] == $rootId) {
                array_push($arrayResult, $v);
            }
        }

        return $arrayResult;
    }

    /**
     * @param int $id
     * @param array $array
     * @return int
     */
    static public function getRootCategoryId($id = 0, $array = [])
    {
        if (0 == $id) {
            return 0;
        }

        foreach ((array)$array as $v) {
            if ($v['id'] == $id) {
                $parentId = $v['parent_id'];
                if (0 == $parentId) {
                    return $id;
                } else {
                    return self::getRootCategoryId($parentId, $array);
                }
            }
        }
    }

    public function getBreadcrumbs()
    {
        $result = $this->getModule()->breadcrumbs;
        $result[] = ['label' => Module::t('Blog Categories'), 'url' => [$this->getModule()->categoriesUrl]];
        return $result;
    }

    /**
     * @param int $id
     * @param array $array
     * @return array|void
     */
    static public function getPathToRoot($id = 0, $array = array())
    {
        if (0 == $id) {
            return [];
        }

        $arrayResult = array();
        $parent_id = 0;
        foreach ((array)$array as $v) {
            if ($v['id'] == $id) {
                $parent_id = $v['parent_id'];
                if (self::PAGE_TYPE_LIST == $v['page_type']) {
                    $arrayResult = array($v['title'] => array('list', id => $v['id']));
                } elseif (self::PAGE_TYPE_PAGE == $v['page_type']) {
                    $arrayResult = array($v['title'] => array('page', id => $v['id']));
                }
            }
        }

        if (0 < $parent_id) {
            $arrayTemp = self::getPathToRoot($parent_id, $array);

            if (!empty($arrayTemp)) {
                $arrayResult += $arrayTemp;
            }
        }

        if (!empty($arrayResult)) {
            return $arrayResult;
        } else {
            return;
        }
    }

    /**
     * created_at, updated_at to now()
     * crate_user_id, update_user_id to current login user id
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'slugAttribute' => 'slug',
            ],
            [
                'class' => ImageUploadBehavior::class,
                'attribute' => 'banner',
                'thumbs' => [
                    'thumb' => ['width' => 400, 'height' => 300]
                ],
                'filePath' => $this->module->imgFilePath . '/[[model]]/[[pk]].[[extension]]',
                'fileUrl' => $this->module->getImgFullPathUrl() . '/[[model]]/[[pk]].[[extension]]',
                'thumbPath' => $this->module->imgFilePath . '/[[model]]/[[profile]]_[[pk]].[[extension]]',
                'thumbUrl' => $this->module->getImgFullPathUrl() . '/[[model]]/[[profile]]_[[pk]].[[extension]]',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'is_nav', 'is_featured', 'sort_order', 'widget_type_id', 'page_size', 'status'], 'integer'],
            [['title'], 'required'],
            [['sort_order', 'page_size'], 'default', 'value' => 0],
            [['icon_class', 'read_icon_class', 'read_more_text'], 'string', 'max' => 60],
            [['title', 'template', 'redirect_url', 'slug'], 'string', 'max' => 255],
            [['banner'], 'file', 'extensions' => 'jpg, png, webp', 'mimeTypes' => 'image/jpeg, image/png, image/webp',],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('ID'),
            'parent_id' => Module::t('Parent ID'),
            'title' => Module::t('Title'),
            'slug' => Module::t('Slug'),
            'banner' => Module::t('Banner'),
            'icon_class' => Module::t('Icon Class'),
            'read_icon_class' => Module::t('Read Icon Class'),
            'read_more_text' => Module::t('Read More Text'),
            'is_nav' => Module::t('Is Nav'),
            'is_featured' => Module::t('Is Featured'),
            'sort_order' => Module::t('Sort Order'),
            'page_size' => Module::t('Page Size'),
            'template' => Module::t('Template'),
            'redirect_url' => Module::t('Redirect Url'),
            'status' => Module::t('Status'),
            'created_at' => Module::t('Created At'),
            'updated_at' => Module::t('Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlogPosts()
    {
        return $this->hasMany(BlogPost::class, ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChilds()
    {
        return $this->hasMany(BlogCategory::class, ['parent_id' => 'id'])->orderBy(['sort_order' => SORT_ASC]);
    }

    /**
     * @return integer
     */
    public function getPostsCount()
    {
        return $this->count(BlogPost::class, ['category_id' => 'id']);
    }

    public static function getAllMenuItems($subCats = false)
    {
        $items = [];
        $model = self::find()->andWhere(['parent_id' => 0])->orderBy(['sort_order' => SORT_DESC])->andWhere(['is_nav' => true])->all();
        /** @var BlogCategory $item */
        foreach ($model as $item) {

            $items[] = $item->getMenuItem($subCats);
        }

        return $items;
    }

    public function getMenuItem($subCats = false)
    {
        $item = [
            'label' => $this->titleWithIcon,
            'url' => $this->url,
        ];
        if ($subCats) {
            foreach ($this->childs as $child) {
                $item['items'][] = $child->getMenuItem();
            }
        }
        return $item;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        $iconClass = $this->icon_class ? $this->icon_class : "fa fa-bookmark";
        return "<i class=\"$iconClass\"></i>";
    }

    public function getTitleWithIcon()
    {
        return $this->icon . " " . $this->title;
    }

    public function getUrl()
    {
        if ($this->getModule()->getIsBackend()) {
            return Yii::$app->getUrlManager()->createUrl(['blog/blog-category/update', 'id' => $this->id]);
        }
        return Yii::$app->getUrlManager()->createUrl(['blog/default/archive', 'slug' => $this->slug]);

    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(BlogCategory::class, ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWidgetType()
    {
        return $this->hasOne(BlogWidgetType::class, ['id' => 'widget_type_id']);
    }

    public function getWidget()
    {
        $config = (array) $this->widgetType->config;
        $config = reset($config);
        $config['category_id'] = $this->id;
        $config['id']=$this->formName().'_'.$this->id;

        return Feed::widget($config);
    }

    /**
     * @return mixed
     */
    public function getIsNavLabel()
    {
        if ($this->_isNavLabel === null) {
            $arrayIsNav = self::getArrayIsNav();
            $this->_isNavLabel = $arrayIsNav[$this->is_nav];
        }
        return $this->_isNavLabel;
    }
}
