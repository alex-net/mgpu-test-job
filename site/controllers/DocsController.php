<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use Yii;

use app\models\Document;
use app\models\User;


/**
 * Класс конроллера для работы с документами ..
 */
class DocsController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'roles' => ['@']],
                ],
            ]
        ];
    }

    /**
     * Список документов
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Добавление нового документа
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function actionAdd()
    {
        return $this->actionEdit();
    }

    /**
     * Редактирование документа по id
     *
     * @param      int  $id     Номер документа
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function actionEdit($id = null)
    {
        $doc = $id ? Document::findOne($id) : new Document();
        if (!$doc) {
            throw new NotFoundHttpException("Станица не найдена");
        }
        $isNew = $doc->isNewRecord;
        if (!$isNew && $doc->isLastStatus) {
            Yii::$app->session->addFlash('warning', "Документы со статусом '{$doc->statusDescr}' не редактируются");
            return $this->redirect(['index']);
        }
        if ($this->request->isPost && $doc->load($this->request->post()) && $doc->save()) {
            Yii::$app->session->addFlash('success', 'Документ ' . ($isNew ? 'сохранён' : 'обновлён'));
            return $this->redirect(['index']);
        }
        return $this->render('edit', ['doc' => $doc]);
    }

    /**
     * перемещение документа в следущий статус
     *
     * @param      <type>  $id     The identifier
     */
    public function actionNextSt($id)
    {
        $doc = Document::findOne($id);
        if ($doc && $doc->goToNextSt()) {
            Yii::$app->session->addFlash('success', "Документ '{$doc->title}' переведён в статус {$doc->statusDescr}");
            // отправляем почту
            if ($doc->isLastStatus) {
                $user = User::findIdentity($doc->author_id);
                $mail = Yii::$app->mailer->compose('doc-mail-notify', [
                    'user' => $user,
                    'docTitle' => $doc->title,
                ]);
                $mail->setSubject("Документ достиг конечного статуса");
                $mail->setTo('test@test.com');
                $mail->setFrom('admin@site.org');
                $mail->send();
                Yii::$app->session->addFlash('success', "Письмо отправлено");
            }
        }

        return $this->redirect($this->request->referrer);
    }
}