<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel atans\actionlog\models\ActionLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $usernameAttribute string */

$this->title = Yii::t('actionlog', 'Action Logs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="action-log-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="box">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'summaryOptions' => [
                'class' => 'box-header',
            ],
            'columns' => [
                'id',
                [
                    'attribute' => 'user_id',
                    'value' => function($model) use ($usernameAttribute) {
                        /* @var $user yii\db\ActiveRecord|yii\web\IdentityInterface|null */
                        $user = $model->user;
                        if ($user instanceof yii\web\IdentityInterface) {
                            return $user->hasAttribute($usernameAttribute) ? $user->$usernameAttribute . ' #'. $user->getId() : $user->getId();
                        }

                        return $model->user_id;
                    }
                ],
                'level',
                'category',
                'message',
                 'data:ntext',
                 'ip',
                 'created_at',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
