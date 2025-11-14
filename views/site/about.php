<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Acerca de';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Sistema desarrollado por la Facultad de Informática de la Universidad Nacional del Comahue.
        Bajo licencia GNU GPL.  
    </p>

    <p>
      Fuentes disponibles en: <a href="https://github.com/fai-unco/wene">https://github.com/fai-unco/wene</a>
    </p>

    <?= $this->render('_convenios', []) ?>
</div>
