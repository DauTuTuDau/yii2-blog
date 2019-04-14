<?php
/**
 * Created by PhpStorm.
 * User: Yordanyan
 * Date: 11.04
 * Time: 21:26
 */

namespace diazoxide\blog\models\importer;


use diazoxide\blog\Module;
use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\httpclient\Client;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;

class Wordpress extends Model
{
    public $url;

    public $per_page = 10;
    public $page = 1;

    public $total;
    public $total_pages;

    protected $rest_path = '/wp-json/wp/v2/';


    public function rules()
    {
        return [
            [['url'], 'required'],
            [['url'], 'url'],
            [['url'], 'wordpress_rest'],
            [['page', 'per_page'], 'integer'],
        ];
    }

    /**
     * @param $attribute_name
     * @param $params
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function wordpress_rest($attribute_name, $params)
    {
        if (!$this->initWordpressJsonApi()) {
            $this->addError($attribute_name, Module::t('', 'The wordpress rest api url not working.'));
            return false;
        }
        return true;
    }

    /**
     * @param $url
     * @return bool|array
     * @throws \yii\base\InvalidConfigException
     */
    private function sendGet($url)
    {
        $client = new Client();
        /** @var Response $response */
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl($url)
            ->setData([])
            ->send();
        if ($response->isOk) {
            return ['data'=>$response->data,'headers'=>$response->getHeaders()];
        }
        return false;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    private function initWordpressJsonApi()
    {
        $data = $this->sendGet($this->getRestURL())['data'];

        if ($data) {
            if ($data['namespace'] != 'wp/v2') {
                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * @param array $params
     * @return array|bool
     * @throws \yii\base\InvalidConfigException
     */
    public function getEndpoint($params)
    {
        $name = is_array($params[0]) ? implode($params[0], '/') : $params[0];

        unset($params[0]);

        $args = empty($params) ? '' : '?' . http_build_query($params, false);

        $url = $this->getRestURL() . $name . $args;

        return $this->sendGet($url);

    }

    /**
     * @return array|bool
     * @throws \yii\base\InvalidConfigException
     */
    public function getPosts()
    {
        $endpoint = $this->getEndpoint(['posts', '_embed' => true, 'per_page' => $this->per_page, 'page' => $this->page]);

        $this->total = $endpoint['headers']['X-WP-Total'];
        $this->total_pages = $endpoint['headers']['X-WP-TotalPages'];

        return $endpoint['data'];
    }

    /**
     * @return array|bool
     * @throws \yii\base\InvalidConfigException
     */
    public function getCategories()
    {
        $endpoint = $this->getEndpoint(['categories', '_embed' => true, 'per_page' => $this->per_page, 'page' => $this->page]);
        return $endpoint['data'];
    }

    /**
     * @return $this
     */
    public function nextPage()
    {
        $this->page++;
        return $this;
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'url' => 'Website URL',
        ];
    }

    public function getRestURL()
    {
        return $this->url . $this->rest_path;
    }
}