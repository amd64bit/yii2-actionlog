<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model atans\actionlog\models\ActionLog */

$this->title = Yii::t('actionlog', 'Log #{id}', ['id' => $model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('actionlog', 'Action Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="action-log-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            'level',
            'category',
            'message',
            'data:ntext',
            'ip',
            'created_at',
        ],
    ]) ?>

</div>
