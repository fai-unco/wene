<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use Da\QrCode\QrCode;

/* @var $this yii\web\View */
/* @var $model app\models\Certificado */
?>
<div style="text-align: center">

    <div class="row" >

        <div class="col-xs-6">
            <img  src="img/uncoma150.png" alt="UNCo"/></div>
<!--        <img class="logo" src="img/sadosky.png" alt="Sadosky"/>-->

        <div class="col-xs-5">
            <img  src="img/faif150.png" alt="Facultad de Informática"/></div>
    </div>

    <h1 >Certificado</h1>
    <!--        <h3>Facultad de Informática Universidad Nacional del Comahue</h3>-->




    <h4><?= $model->idLote0->observacion ?></h4>

    <h3 >Neuquén, <?= $model->idLote0->getFechaTexto(); ?>.</h3>
                
    <div class="row" style="padding-top: 50px">
<!--        <div class="col-xs-4" style="background-image:url('img/firmaazul.png');background-position: center; background-repeat: no-repeat; background-size: contain">-->


<!--<p>
                Se puede validar el Certificado, accediendo al link del código QR, o a <a href="<?= \yii\helpers\Url::base('https') ?>"><?= \yii\helpers\Url::base('https') ?></a> con el código <b><?= $model->hash ?>
            </p>-->
<img class="qr" src="<?= $qrCode->writeDataUri() ?>" >                        

    </div>


</div>



