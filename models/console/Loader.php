<?php

namespace app\models\console;

use app\models\Vacancy;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Throwable;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class Loader Загрузчик данных из Труд Всем.
 * @package app\models\console
 */
class Loader extends BaseObject
{
    public string $baseUrl = 'http://opendata.trudvsem.ru/api/v1/vacancies/region/';
    public string $requestForId = 'https://trudvsem.ru/iblocks/job_card?';
    public string $region = '7600000000000';
    public int $startPage = 0;
    public int $pageSize = 50;
    public int $sleepTime = 2;
    public bool $updateOnChanged = false;
    public bool $clearOnStart = false;


    /**
     * Загрузка и парсинг всех вакансий.
     * @throws GuzzleException
     */
    public function loadAndParse()
    {
        if ($this->clearOnStart) {
            Vacancy::deleteAll();
            Yii::info('Clear table `vacancy` in data base.', __METHOD__);
        }

        Yii::info("Loader starting region: {$this->region}", __METHOD__);
        $currentPage = $this->startPage;
        $pages = 0;
        $count = 0;
        do {
            if ($pages > 0) {
                if ($currentPage >= $pages) {
                    if (YII_ENV_DEV) {
                        echo "Загрузка окончена!                                       \n";
                    }
                    $all = $all ?? 'error';
                    Yii::info("Loader finished. Pages: $pages,  Records in server: $all, Records loaded: $count", __METHOD__);
                    return;
                }
            }
            if (YII_ENV_DEV) {
                echo "Загрузка страницы $currentPage" . ($pages == 0 ? "\r" : " из $pages\r");
            }
            $url = "{$this->baseUrl}{$this->region}?limit={$this->pageSize}&offset=$currentPage";
            $table = $this->arrayLoader($url);
            foreach ($table['results']['vacancies'] as $item) {
                Vacancy::decode($item['vacancy'], $this->updateOnChanged);
            }

            $all = $table['meta']['total'] ?? 'no data';
            $pages = ceil($table['meta']['total'] / $this->pageSize);
            $currentPage++;
            $count += count($table);
        } while (true);
    }

    /**
     * Загружаем страницу вакансий в виде массива.
     * @param string $url
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function arrayLoader(string $url): array
    {
        $client = new Client();
        $res = $client->request('GET', $url);
        $body = $res->getBody();
        $text = $body->read($body->getSize());
        $table = json_decode($text, true);

        if (!isset(
            $table['status'],
            $table['meta'],
            $table['meta']['total'],
            $table['results'],
            $table['results']['vacancies']
        )) {
            Yii::error("Format error", __METHOD__);
            throw new Exception('Ошибка формата загрузки. Отсутствуют необходимые поля.');
        }

        if ($table['status'] != 200) {
            Yii::error("Status error {$table['status']}", __METHOD__);
            throw new Exception("Оошибка загрузки: {$table['status']}");
        }
        if (empty($table['results']['vacancies'])) {
            Yii::error("Not business data in request", __METHOD__);
            throw new Exception('Оошибка загрузки: отсутствие записей в ответе.');
        }

        return $table;
    }

    /**
     * Обновить колличество рабочих мест в скрытых вакансиях.
     * @throws Exception
     */
    public function workPlacesUpdate(): void
    {
        $this->placesLoader(function () {
            return Vacancy::find()
                ->select(['uid', 'cid' => '`company`.`companycode`'])
                ->joinWith('company')
                ->where('`work_places` = 0')
                ->asArray()
                ->all();
        });
    }

    /**
     * Загрузить колличество рабочих мест для новых вакансияй.
     * @throws Exception
     */
    public function countLoader(): void
    {
        $this->placesLoader(function () {
            return Vacancy::find()
                ->select(['uid', 'cid' => '`company`.`companycode`'])
                ->joinWith('company')
                ->where('`work_places` IS NULL')
                ->asArray()
                ->all();
        });
    }

    /**
     * @param $function
     * @throws Exception
     */
    private function placesLoader($function): void
    {
        do {
            $uidTable = $function();

            if (YII_ENV_DEV) {
                $count = count($uidTable);
                echo "Загрузка информации о рабочих местах по $count вакансиям.\n";
            }
            $noErrors = $this->workPlacesLoader($uidTable);
            if (!$noErrors) {
                sleep($this->sleepTime);
            }
        } while (!$noErrors);
    }

    /**
     * Заполнение таблицы вакансий колличеством рабочих мест.
     * @param array $uidTable
     * @return bool
     * @throws Exception
     */
    private function workPlacesLoader(array $uidTable): bool
    {
        $pauseCount = 0;
        $allCount = 0;
        $allWP = 0;
        $errors = 0;
        foreach ($uidTable as $item) {
            $count = $this->workPlaceLoader($item['uid'], $item['cid']);
            if ($count === null) {
                $errors++;
                continue;
            }
            Vacancy::updateAll(['work_places' => $count], ['uid' => $item['uid'], 'company_id' => $item['cid']]);

            // Ограничиваем нагрузку на сервер, и имитируем работу пользователя.
            if ($pauseCount++ >= 9) {
                sleep($this->sleepTime);
                $pauseCount = 0;
            }

            $allCount++;
            $allWP += $count;
        }
        if (YII_ENV_DEV) {
            Yii::info("Загружено {$allCount} вакансий {$allWP} рабочих мест. Ошибок загрузки: $errors.", __METHOD__);
            echo "\nЗагружено {$allCount} вакансий {$allWP} рабочих мест. Ошибок загрузки: $errors.\n";
        }
        return $errors == 0;
    }

    /**
     * Получение кол-ва рабочих мест с помощью не документированной функции внутреннего API.
     * @param string $uid
     * @param string $cid
     * @return int
     * @throws Exception
     */
    private function workPlaceLoader(string $uid, string $cid): ?int
    {
        $url = $this->requestForId . "companyId={$cid}&vacancyId={$uid}";

        $client = new Client();
        try {
            $res = $client->request('GET', $url);
        } catch (RequestException $e) {
            $code = $this->extractHttpCode($e);
            if ($code == 999) {
                Yii::warning("Vacancy {$cid}/{$uid} is hidden by the employer.");
                return 0;
            }
            if ($code !== null) {
                Yii::warning("Error request Http:($code} workPlaces for {$cid}/{$uid} error message: {$e->getMessage()}", __METHOD__);
            }
            return null;
        } catch (Throwable $e) {
            Yii::warning("Error " . get_class($e) . " request ({$e->getCode()}) workPlaces for {$cid}/{$uid} error message: {$e->getMessage()}", __METHOD__);
            if (YII_ENV_DEV) {
                echo "Error " . get_class($e) . " request ({$e->getCode()}) workPlaces for {$cid}/{$uid} error message: {$e->getMessage()}\n";
            }
            return null;
        }

        $body = $res->getBody();
        $text = $body->read($body->getSize());
        $table = Json::decode($text);
        $result = $table;
        if (($result['code'] ?? '') !== 'SUCCESS') {
            Yii::error("Error request no SUCCESS in result. Vacancy: $cid/$uid} Request:$text", __METHOD__);
            return null;
        }
        return ArrayHelper::getValue($result, 'data.vacancy.workPlaces', 1);
    }

    /**
     * Извлекаем HTTP код ошибки.
     * @param RequestException $e
     * @return int|null
     */
    private function extractHttpCode(RequestException $e): ?int
    {
        foreach ($e->getPrevious()->getTrace() as $item) {
            if ('GuzzleHttp\Psr7\Response' == ($item['class'] ?? null)
                && '__construct' == ($item['function'] ?? null)
                && is_array($item['args'] ?? null)
            ) {
                return $item['args'][0] ?? null;
            }
        }
        return null;
    }
}
