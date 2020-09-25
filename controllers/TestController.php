<?php
/**
 * Created by PhpStorm.
 * User: liyujin
 * Date: 2020-09-22
 * Time: 17:27
 */

namespace app\controllers;


use app\models\Es;
use app\server\Di;
use yii\web\Controller;
use yii;
use yii\helpers\BaseArrayHelper;

class TestController extends Controller
{
	/**
	 * 测试
	 *
	 * @return #
	 */
	public function actionIndex()
	{
		$result = self::createData();
		var_dump($result);
		exit;

	}

	public function actionGetData()
	{
//		$result = Es::find()->where(['desc' => '广州'])->asArray()->all();
//		$result = Es::find()->query(["match" => ["desc" => "广州"]])->asArray()->one();
//		var_dump($result);
//		exit;
		$queryArr = [
			"bool" => [
				"must" => [
//					['match' => ["name" => "广州"]],
//					['match' => ["name" => "深圳"]],
//					['match' => ["desc" => "深圳"]],
//					['match' => ["desc" => "广州"]]
					['terms' => ["id" => [54103, 25894]]]
				],
			],
		];
//		$result = Es::find()->query($queryArr)->asArray()->all();
		$result = Es::find()->limit(100)->asArray()->all();
		$item = [];
		if ($result) {
			foreach ($result as $k => $vo) {
				$item[$k] = $vo['_source'];
			}
		}
		var_dump($item);
		exit;
	}

	protected static function createData()
	{
		$id = rand(1000, 99999);
		$name = "深圳房价最新动态";
		$keyword = ["深圳", "房价", '地价'];   //多个字段可以使用数组
		$desc = "关注广州房价最新动态，了解楼盘最新行情。主安家做前期准备";
		$data = ['id' => $id, 'name' => $name, 'keyword' => $keyword, 'desc' => $desc];
		$customer = new Es();
		$customer->primaryKey = $id; // in this case equivalent to $customer->id = 1;
		$customer->setAttributes($data, false);
		$create = $customer->save(false);
		return $create;

	}


	protected function _getSearchQuery($field = '', $key = null)
	{
		$query = Es::find();
		if ($key == false) {
			return $query;
		}
		$term = ["term" => [$field => $key]];
		$match = [$field => $key];
		if (is_array($key)) {
			$term = ["terms" => [$field => $key]];
		}
		$filterArr = [
			'bool' => [
				'must' => [$term]
			]
		];
		// $field_1 $field_2 都是字段
		$queryArr = [
			'bool' => [
				'must' => [
					['match' => $match],

				],
				'should' => [
					// 关于wildcard查询可以参看文章：http://blog.csdn.net/dm_vincent/article/details/42024799
					['wildcard' => [$field => "W?F*HW"]]
				]
			],
		];
		$query = $query->filter($filterArr)->query($queryArr);
		return $query;
	}

	/**
	 * 更新数据
	 *
	 * @return
	 */
	public function actionUpdate()
	{
		$name = "美国楼盘";
		$keyword = ['美国'];
		$desc = '美国楼盘最新消息关注';
		$model = Es::findOne(54103);
		var_dump($model->toArray());
		exit;
		$model->name = $name;
		$model->keyword = $keyword;
		$model->desc = $desc;
		if ($model->update() == false) {
			var_dump(11);
			exit;
		}
		var_dump(22);
		exit;
	}

	/**
	 * 删除数据
	 */
	public function actionDelAll()
	{
		$model = Es::find()->all();
//		$model = Es::find()->one();
//		var_dump($model->attributes());
//		$a = $model->attributes;
//		var_dump($a['name']);
//		exit;

		foreach ($model as $vo) {
			$arr = $vo->attributes;
			if ($arr['name'] == false) {
				$vo->delete();
			}
		}
		var_dump(1);
		exit;
	}
}