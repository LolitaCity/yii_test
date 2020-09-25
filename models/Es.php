<?php
/**
 * Created by PhpStorm.
 * User: liyujin
 * Date: 2020-09-24
 * Time: 10:16
 */

namespace app\models;


use yii\elasticsearch\ActiveRecord;
use yii;

class Es extends ActiveRecord
{
	public static function getDb()
	{
		return yii::$app->get('elasticsearch');
	}

	public static function index()
	{
		return 'goods';
	}

	public static function type()
	{
		return 'customer';
	}

	public function attributes()
	{
//		$mapConfig = self::mapConfig();
//		return array_keys($mapConfig);
		return self::mapConfig();
	}

	public static function mapConfig()
	{
		return ['id', 'name', 'keyword', 'desc'];
	}

	public static function mapping()
	{
		return [
			static::type() => self::mapConfig(),
		];
	}

	/**
	 * Set (update) mappings for this model
	 */
	public static function updateMapping()
	{
		$db = self::getDb();
		$command = $db->createCommand();
		if (!$command->indexExists(self::index())) {
			$command->createIndex(self::index());
		}
		$command->setMapping(self::index(), self::type(), self::mapping());
	}

	public static function getMapping()
	{
		$db = self::getDb();
		$command = $db->createCommand();
		return $command->getMapping();
	}
}