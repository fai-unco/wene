<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DependenciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dependencias';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dependencia-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Dependencia', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php if (Yii::$app->user->identity->idRol == \app\models\Rol::ROL_ADMININST) { ?>
        <div class="alert alert-info">
            Este usuario/a es Administrador/a de la Institución y solo puede ver
            las dependencias que tiene asociada. Ante cualquier duda acerca de
            otras dependencias, consulte al administrador del sitio.
        </div>
    <?php } ?>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idDependecia',
            'nombre',
            'idDependenciaPadre',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
