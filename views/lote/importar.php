<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Lote */
/* @var $provider yii\data\ArrayDataProvider*/

$this->title = 'Importar';
$actividad = $model->idActividad0;
$this->params['breadcrumbs'][] = ['label' => 'Actividades', 'url' => ['/actividad/index']];
$this->params['breadcrumbs'][] = ['label' => $actividad->idTipoActividad0->tipo . ': ' . $actividad->nombre,
    'url' => ['/actividad/view', 'id' => $actividad->idActividad]];
$this->params['breadcrumbs'][] = ['label' => 'Lote #' . $model->idLote,
    'url' => ['view', 'id' => $model->idLote]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="lote-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if($provider->count==0){?>
    <p>Solo se aceptan archivos CSV (separados por comas) de extensión ".csv". Debe contener al menos cuatro columnas, sin las cabeceras y con los textos (strings) entre comillas dobles.</p>

    <p>El formato completo es el siguiente:</p>
    
    <table class="table">
        <tr>
            <th>Número de columna:</th>
            <td>Columna 1</td>
            <td>Columna 2</td>
            <td>Columna 3</td>
            <td>Columna 4</td>
            <td>Columna 5</td>
            <td>Columna 6</td>
        </tr>
        <tr>
            <th>Dato de la columna:</th>
            <td>DNI</td>
            <td>Observación</td>
            <td>Apellido y nombre</td>
            <td>Correo electrónico</td>
            <td>Legajo</td>
            <td>ID_Externo</td>
        </tr>
        <tr>
            <th>Tipo de dato:</th>
            <td>numérico</td>
            <td>texto</td>
            <td>texto</td>
            <td>texto</td>
            <td>texto</td>
            <td>texto</td>
        </tr>
        <tr>
            <th>Obligatorio:</th>
            <td>No</td>
            <td>No</td>
            <td>Sí</td>
            <td>No</td>
            <td>No</td>
            <td>No</td>
        </tr>
    </table>

    <p>Ejemplo:</p>
    <pre>12345678,"","Perez Pedro","pedro.perez@fi.uncoma.edu.ar","FAI-123","ID-53498"
12345679,"","Perez Juana","juana.perez@fi.uncoma.edu.ar","FAI-124","ID-53499"</pre>

    <!-- 
         <p>Columna 1 **(numérico)-> dni,
         Columna 2-> observación,
         Columna 3 *-> Apellido y Nombre,
         Columna 4 ->mail,
         Columna 5 -> legajo,
         Columna 6 **-> ID_Externo</p>

         
         <p>* Columnas Obligatorias</p>
         <p>** Los identificadores de la persona son DNI o ID_Externo.  En el caso de tener DNI la Columna 6 (ID_Extranjero) es opcional. En el caso de no contar con DNI hay que dejar la columna 1 (dni) sin datos y la columna 6 (ID__Externo) con datos como el ejermplo que sigue:</p>
         <p>,"","Perez Juana","juana.perez@fi.uncoma.edu.ar","FAI-123","ID-53498"</p>
    -->

    <p>Los identificadores de la persona son DNI o ID_Externo. En el caso de tener DNI la Columna 6 (ID_Extranjero) es opcional. En el caso de no contar con DNI hay que dejar la columna 1 (DNI) sin datos y la columna 6 (ID_Externo) con datos como el ejermplo que sigue:</p>
    
    <pre>,"","Perez Juana","juana.perez@fi.uncoma.edu.ar","FAI-124","ID-53499"</pre>

    <p>Para la elaboración y edición se puede utilizar el editor de hojas de cálculo de LibreOffice, el cual es Software Libre y descargable gratuitamente desde <a href="https://www.libreoffice.org">https://www.libreoffice.org</a>. Asegúrese de guardar como CSV desde Archivo > Guardar Como... y en el cuadro de diálogo, debajo del nombre (donde indica "Tipo:") seleccionar "Archivo CSV".</p> 

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($modelform, 'archivo')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Importar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php } else{?>
    <h3>Resumen</h3>
    <div class="alert alert-success">
        
        <?php        foreach ($contadores as $contador=>$cantidad){
        echo '<h4  >'.$cantidad.' '.$contador.'</h4>';
    }?>
        </div>
<h3>Certificados Importados</h3>
    <?=
    yii\grid\GridView::widget([
        'dataProvider' => $provider,
        'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
            'dni',
            'obs',
            'msj'],
    ]);
    ?>
    <?php }?>
</div>
