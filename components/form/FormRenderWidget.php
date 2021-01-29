<?php

namespace app\components\form;

use yii\base\Widget;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Html;

class FormRenderWidget extends Widget
{

    public $fieldSet;
    public $formModel;
    public $usePjax;
    public $formOptions;
    public $submitOptions;
    public $labelOptions;
    public $header;
    public $successMessage;
    public $successMessageOptions;
    public $errorMessage;
    public $errorMessageOptions;
    public $placeholder;

    public function init()
    {
        parent::init();
        $defaultOptions = [
            'class' => '',
        ];
        if($this->usePjax === null)
        {
            $this->usePjax = true;
        }
        if(!$this->formOptions)
        {
            $idForm            = 'form-' . \Yii::$app->security->generateRandomString(4);
            $this->formOptions = ['id' => $idForm, 'action' => ''];
        }
        if(!$this->submitOptions)
        {
            $this->submitOptions = [];
        }
        if(!$this->successMessage)
            $this->successMessage        = 'Ваша заявка успешно отправлена. Мы свяжемся с вами в ближайшее время.';
        if(!$this->errorMessage)
            $this->errorMessage          = 'Произошла ошибка при отправке заявки. Перезагрузите страницу и попробуйте еще раз.';
        if(!$this->successMessageOptions)
            $this->successMessageOptions = [];
        if(!$this->errorMessageOptions)
            $this->errorMessageOptions   = [];
        $this->successMessageOptions = ['class' => 'success'] + $this->successMessageOptions;
        $this->errorMessageOptions   = ['class' => 'error'] + $this->errorMessageOptions;
    }

    public function run()
    {
        if(!$this->formModel || !$this->fieldSet)
            return false;
        if($this->usePjax)
            Pjax::begin([
                'formSelector' => '#' . $this->formOptions['id'],
            ]);
        $modelClass = $this->formModel;
        $model      = new $modelClass();

        if(\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post()) && method_exists($model, 'send'))
        {
            if($model->send())
            {
                echo Html::tag('div', $this->successMessage, $this->successMessageOptions);
            }
            else
            {
                echo Html::tag('div', $this->errorMessage, $this->errorMessageOptions);
            }
        }
        else
        {
            if($this->header)
                echo Html::tag('div', $this->header, ['class' => 'header']);
            $form  = ActiveForm::begin($this->formOptions);
            $types = $model->attributeTypes();
            foreach($this->fieldSet as $field)
            {
                if($types[$field] == 'string')
                    echo $form->field($model, $field)
                            ->textInput(['placeholder' => $this->placeholder && isset($this->placeholder[$field]) ? $this->placeholder[$field] : $model->getAttributeLabel($field)])
                            ->label($this->labelOptions === null ? '' : isset($this->labelOptions[$field]) ? $this->labelOptions[$field] : '');
                if($types[$field] == 'text')
                    echo $form->field($model, $field)
                            ->textarea(['placeholder' => $this->placeholder && isset($this->placeholder[$field]) ? $this->placeholder[$field] : $model->getAttributeLabel($field)])
                            ->label($this->labelOptions === null ? '' : isset($this->labelOptions[$field]) ? $this->labelOptions[$field] : '');
                if($types[$field] == 'tel')
                    echo $form->field($model, $field)
                            ->widget(\yii\widgets\MaskedInput::className(), [
                                'mask'    => '+7 (999) 999 99 99',
                                'options' => ['class' => 'form-control', 'placeholder' => $this->placeholder && isset($this->placeholder[$field]) ? $this->placeholder[$field] : $model->getAttributeLabel($field)],
                    ]);
                if($types[$field] == 'hidden')
                {
                    echo $form->field($model, $field, ['options' => ['class' => 'hide']])->textInput()->label(false);
                }
                if($types[$field] == 'checkbox')
                {
                    $model->$field = 1;
                    echo $form->field($model, $field, ['options' => ['class' => 'checkbox'],])
                            ->checkbox(['labelOptions' => ['class' => 'checked']])
                            ->label(false);
                }
            }
            echo Html::submitButton(isset($this->submitOptions['content']) ? Html::tag('span', $this->submitOptions['content']) : Html::tag('span', 'Отправить'), $this->submitOptions);
            $form->end();
        }
        if($this->usePjax)
            Pjax::end();
    }

}
