<?php
// src/Model/Table/ArticlesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;
// Text クラス
use Cake\Utility\Text;  // シンプルなスラグ生成の追加
use Cake\Validation\Validator;  // Articles の検証ルールの更新
class ArticlesTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }

    // シンプルなスラグ生成の追加--------------------------------start
    public function beforeSave($event, $entity, $options)
    {
        if ($entity->isNew() && !$entity->slug) {
            $sluggedTitle = Text::slug($entity->title);
            // スラグをスキーマで定義されている最大長に調整
            $entity->slug = substr($sluggedTitle, 0, 191);
        }
    }
    // ----------------------------------------------------------end

    // Articles の検証ルールの更新-------------------------------start
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmptyString('title', false)
            ->minLength('title', 10)
            ->maxLength('title', 255)

            ->allowEmptyString('body', false)
            ->minLength('body', 10);

        return $validator;
    }
    // ----------------------------------------------------------end
}
