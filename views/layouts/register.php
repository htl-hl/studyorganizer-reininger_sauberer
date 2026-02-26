<?php

use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this \yii\web\View */
/* @var $content string */
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="de">
<header id="header">
    <?php
    NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
    ]);
    echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => [
                    Yii::$app->user->isGuest
                            ? ['label' => 'Login', 'url' => ['/site/login']]
                            : '<li class="nav-item">'
                            . Html::beginForm(['/site/logout'])
                            . Html::submitButton(
                                    'Logout (' . Yii::$app->user->identity->username . ')',
                                    ['class' => 'nav-link btn btn-link logout']
                            )
                            . Html::endForm()
                            . '</li>'
            ]
    ]);
    NavBar::end();
    ?>
</header>
    <head>
        <meta charset="UTF-8">
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f0f0f0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .register-box {
                background: #fff;
                padding: 30px 40px;
                border-radius: 8px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                width: 350px;
            }
            .register-box h2 {
                text-align: center;
                margin-bottom: 25px;
            }
            .register-box input[type="text"],
            .register-box input[type="password"] {
                width: 100%;
                padding: 10px;
                margin: 8px 0;
                border-radius: 4px;
                border: 1px solid #ccc;
            }
            .btn-register {
                width: 100%;
                padding: 10px;
                background: #007bff;
                color: #fff;
                border: none;
                border-radius: 4px;
                margin-top: 15px;
                cursor: pointer;
            }
            .btn-register:hover { background: #0056b3; }
            .register-box a {
                display: block;
                text-align: center;
                margin-top: 15px;
                text-decoration: none;
                color: #555;
            }
            .checkbox {
                display: flex;
                align-items: center;
                margin-top: 10px;
            }
            .checkbox input {
                margin-right: 8px;
            }
        </style>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <div class="register-box">
        <?= $content ?>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>