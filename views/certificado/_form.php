<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Certificado */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="certificado-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php
    $personas = \app\models\Persona::find()->orderBy('apellidoNombre')->all();
    $personaData = [];
    $personaSeleccionada = null;
    foreach ($personas as $persona) {
        $identificador = $persona->dni ? 'DNI ' . $persona->dni : 'ID externo ' . $persona->idExtranjero;
        $personaData[] = [
            'id' => (string) $persona->idPersona,
            'nombre' => $persona->apellidoNombre,
            'identificador' => $identificador,
            'busqueda' => implode(' ', array_filter([
                $persona->apellidoNombre,
                $persona->dni,
                $persona->idExtranjero,
            ])),
        ];
        if ((string) $persona->idPersona === (string) $model->idPersona) {
            $personaSeleccionada = $personaData[count($personaData) - 1];
        }
    }
    ?>

    <div class="form-group" style="position: relative;">
        <?= Html::label('Persona destinataria', 'certificado-persona-busqueda') ?>
        <?= Html::textInput('personaBusqueda', '', [
            'id' => 'certificado-persona-busqueda',
            'class' => 'form-control',
            'placeholder' => 'Ingrese nombre, apellido, DNI o ID externo y seleccione una coincidencia',
            'autocomplete' => 'off',
            'role' => 'combobox',
            'aria-autocomplete' => 'list',
            'aria-controls' => 'certificado-persona-resultados',
            'aria-expanded' => 'false',
        ]) ?>
        <div id="certificado-persona-resultados" class="list-group" role="listbox" style="position: absolute; z-index: 1000; width: 100%; max-height: 240px; overflow-y: auto; display: none;"></div>
        <?= $form->field($model, 'idPersona')->hiddenInput()->label(false) ?>
    </div>


    <?= //$form->field($model, 'idEstado')->textInput() 
        $form->field($model, 'idEstado')->dropDownList(\app\models\Estado::find()
            ->select(['estado'])
            ->indexBy('idEstado')
            ->column())?>

    <?php // $form->field($model, 'hash')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'observacion')->textInput() ?>
    
    <?= $form->field($model, 'adjunto')->checkbox() ?>
    
    <?= $form->field($model, 'archivo')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$personaDataJson = json_encode($personaData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
$personaSeleccionadaJson = json_encode($personaSeleccionada, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
$this->registerJs(<<<JS
(function () {
    var search = document.getElementById('certificado-persona-busqueda');
    var selected = document.getElementById('certificado-idpersona');
    var results = document.getElementById('certificado-persona-resultados');
    var personas = {$personaDataJson};
    var personaSeleccionada = {$personaSeleccionadaJson};
    var activeIndex = -1;

    function normalizar(texto) {
        return texto.toLocaleLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }

    function ocultarResultados() {
        results.style.display = 'none';
        results.innerHTML = '';
        search.setAttribute('aria-expanded', 'false');
        activeIndex = -1;
    }

    function elegir(persona) {
        selected.value = persona.id;
        search.value = persona.nombre + ' (' + persona.identificador + ')';
        ocultarResultados();
    }

    function mostrarResultados(coincidencias) {
        results.innerHTML = '';
        activeIndex = -1;
        if (!coincidencias.length) {
            var vacio = document.createElement('div');
            vacio.className = 'list-group-item';
            vacio.textContent = 'No se encontraron personas';
            results.appendChild(vacio);
        } else {
            coincidencias.forEach(function (persona) {
                var opcion = document.createElement('button');
                opcion.type = 'button';
                opcion.className = 'list-group-item';
                opcion.setAttribute('role', 'option');
                opcion.textContent = persona.nombre + ' (' + persona.identificador + ')';
                opcion.addEventListener('click', function () { elegir(persona); });
                results.appendChild(opcion);
            });
        }
        results.style.display = 'block';
        search.setAttribute('aria-expanded', 'true');
    }

    search.addEventListener('input', function () {
        selected.value = '';
        var terminos = normalizar(search.value.trim()).split(/\s+/).filter(Boolean);
        if (!terminos.length) {
            ocultarResultados();
            return;
        }
        mostrarResultados(personas.filter(function (persona) {
            var busquedaPersona = normalizar(persona.busqueda);
            return terminos.every(function (termino) {
                return busquedaPersona.indexOf(termino) !== -1;
            });
        }));
    });

    search.addEventListener('keydown', function (event) {
        var opciones = results.querySelectorAll('[role="option"]');
        if (event.key === 'ArrowDown' && opciones.length) {
            event.preventDefault();
            activeIndex = (activeIndex + 1) % opciones.length;
            opciones[activeIndex].focus();
        } else if (event.key === 'Escape') {
            ocultarResultados();
        }
    });

    results.addEventListener('keydown', function (event) {
        var opciones = results.querySelectorAll('[role="option"]');
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
            event.preventDefault();
            activeIndex = (activeIndex + (event.key === 'ArrowDown' ? 1 : opciones.length - 1)) % opciones.length;
            opciones[activeIndex].focus();
        } else if (event.key === 'Escape') {
            search.focus();
            ocultarResultados();
        }
    });

    document.addEventListener('click', function (event) {
        if (!results.contains(event.target) && event.target !== search) {
            ocultarResultados();
        }
    });

    if (personaSeleccionada) {
        elegir(personaSeleccionada);
    }
})();
JS
);
?>
