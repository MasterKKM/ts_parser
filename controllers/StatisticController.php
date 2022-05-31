<?php

namespace app\controllers;

use app\models\Employment;
use app\models\form\JobName;
use app\models\Vacancy;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

class StatisticController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'vacancies',
                            'soc-vacancies',
                            'vacancies-dump',
                            'changeInWeek',
                            'changeInMonth',
                            'vac-dump',
                            'count-places',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return string
     */
    public function actionVacancies()
    {
        $employment = $this->employmentTable();
        $dataProvider = new ActiveDataProvider([
            'query' => Vacancy::find()
                ->select([
                    'job-name', 'salary_min' => 'MAX(salary_min)',
                    'work_places' => 'SUM(work_places)',
                    'salary_max' => 'COUNT(*)'
                ])
                ->where('`social_protected_id` IS NULL')
                ->andWhere(['<>', 'work_places', 0])
                ->andWhere(['employment_id' => $employment])
                ->groupBy('job-name')
            ,
        ]);
        return $this->render('vacancies', [
            'dataProvider' => $dataProvider,
            'title' => 'Статистика по профессиям',
        ]);
    }

    /**
     * @return array
     */
    private function employmentTable(): array
    {
        return \Yii::$app->cache->getOrSet('employmentModels', function () {
            $employmentModels = Employment::findAll([
                'name' => [
                    'Полная занятость',
                    'Удаленная',
//                'Сезонная',
                ]
            ]);
            return ArrayHelper::getColumn($employmentModels, 'id');
        }, 3600);
    }

    /**
     * @return string
     */
    public function actionSocVacancies()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Vacancy::find()
                ->select(['job-name', 'salary_min' => 'MAX(salary_min)', 'work_places' => 'SUM(work_places)', 'salary_max' => 'COUNT(*)'])
                ->where('`social_protected_id` IS NOT NULL')
                ->andWhere(['<>', 'work_places', 0])
                ->groupBy('job-name')
            ,
        ]);
        return $this->render('vacancies', [
            'dataProvider' => $dataProvider,
            'title' => 'Статистика по социальному резервированию в профессиях',
        ]);
    }

    /**
     * @throws \yii\web\RangeNotSatisfiableHttpException
     */
    public function actionVacanciesDump()
    {
        $employment = $this->employmentTable();
        $models = Vacancy::find()
            ->select([
                'job-name',
                'salary_min' => 'MAX(salary_min)',
                'work_places' => 'SUM(work_places)',
                'salary_max' => 'COUNT(*)'
            ])
            ->where('`social_protected_id` IS NULL')
            ->andWhere(['employment_id' => $employment])
            ->groupBy('job-name')
            ->orderBy('`work_places` DESC')
            ->limit(100)
            ->all();

        $string = $this->renderPartial('vacancies-dump', [
            'models' => $models,
        ]);

        \Yii::$app->response->sendContentAsFile($string, 'Сто самых востребованных вакансий.txt');
    }


    public function actionVacDump()
    {
        $models = Vacancy::find()
            ->where('`social_protected_id` IS NULL')
            ->andWhere('`employment_id` IN (1,3,5,6)')
            ->orderBy('salary_min DESC')
            ->limit(100)
            ->all();

        $string = $this->renderPartial('vac-dump', [
            'models' => $models,
        ]);

        \Yii::$app->response->sendContentAsFile($string, 'Сто самых высокооплачиваемых вакансий.txt');
    }

    /**
     * @return string|null
     * @throws \yii\web\RangeNotSatisfiableHttpException
     */
    public function actionCountPlaces()
    {
        $model = new JobName();
        $table = null;

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {
                $table = $model
                    ->createRequest()
                    ->orderBy('`salary` ASC')
                    ->andWhere(['employment_id' => $this->employmentTable()])
                    ->andWhere(['<>', 'work_places', 0])
                    ->all();
                if ($model->as_texts == 1 && !empty($table)) {
                    $string = $this->renderPartial('job_counts_text', [
                        'model' => $model,
                        'table' => $table,
                    ]);
                    \Yii::$app->response->sendContentAsFile($string, 'Распределение вакансий по запрлатам.txt');
                    return null;
                }
            }
        }

        return $this->render('job_counts', [
            'model' => $model,
            'table' => $table,
        ]);
    }
}
