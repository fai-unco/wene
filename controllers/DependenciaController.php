<?php

namespace app\controllers;

use Yii;
use app\models\Dependencia;
use app\models\DependenciaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DependenciaController implements the CRUD actions for Dependencia model.
 */
class DependenciaController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'ruleConfig' => [
                    'class' => \app\models\AccessRule::className(),
                ],
                'only' => ['index', 'view', 'update', 'delete', 'create'],
                'rules' => [
                    //'class' => AccessRule::className(),
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'delete', 'create'],
                        /* No hace falta porque aplica solo matchCallback.
                         'roles' => [\app\models\Rol::ROL_ADMIN,
                                   \app\models\Rol::ROL_ADMININST],
                        */
                        'matchCallback' => function ($rule, $action) {
                            $idRol = Yii::$app->user->identity->idRol;
                            if ($idRol == \app\models\Rol::ROL_ADMIN) {
                                // A admin se permite todas las acciones.
                                return true;
                            }
                            if ($idRol != \app\models\Rol::ROL_ADMININST
                                || in_array($action->id, ['delete', 'create'])) {
                                // No se permiten otros roles que ROL_ADMIN ni ROL_ADMININST.
                                // Tampoco, acciones delete o create.
                                return false;
                            }

                            // El usuario es ROL_ADMININT (administrador de la institución)
                            if ($action->id == 'index') {
                                // Permitir el index para ROL_ADMININST. En index() se listará solo las intituciones asignadas
                                // al usuario.
                                return true;
                            }
                            
                            // Si busca un id de dependencia para el view, update debe ser uno asignado
                            // sino rechazar.
                            $id = null; // <- id dependencia
                            if (Yii::$app->request->isGet) {
                                $id = Yii::$app->request->get('id');
                            } else {                                
                                $id = Yii::$app->request->bodyParam('id');
                            }
                            
                            return Yii::$app->user->identity->inDependencia($id);
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Dependencia models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new DependenciaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (Yii::$app->user->identity->idRol == \app\models\Rol::ROL_ADMININST) {
            $dataProvider->query
                ->joinWith('usuarioDependencias')
                ->andFilterWhere(['idUsuario' => Yii::$app->user->identity->id]);
        }

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Dependencia model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {

        $searchModel = new \app\models\TemplateDependenciaSearch();
        $searchModel->idDependencia = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Dependencia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Dependencia();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->idDependecia]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Dependencia model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->idDependecia]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Dependencia model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Dependencia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Dependencia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Dependencia::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
