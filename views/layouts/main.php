<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <style>
            body { background-color: #f8f9fa; }
            #main { padding-top: 80px; }

            .auth-container {
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 70vh;
            }
            .auth-box {
                background: #fff;
                padding: 2rem;
                border-radius: 1rem;
                box-shadow: 0 10px 25px rgba(0,0,0,0.05);
                width: 100%;
                max-width: 400px;
            }
            .navbar { box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        </style>
    </head>
    <body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header id="header">
        <?php
        NavBar::begin([
                'brandLabel' => 'StudyOrganizer',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
        ]);

        $items = [];
        if (Yii::$app->user->isGuest) {
            $items[] = ['label' => 'Login', 'url' => ['/site/login']];
            $items[] = ['label' => 'Registrieren', 'url' => ['/site/register']];
        } elseif (Yii::$app->user->identity->role === 'admin') {
            $items[] = ['label' => 'Lehrer', 'url' => ['teachers/index']];
            $items[] = ['label' => 'Fächer', 'url' => ['subjects/index']];
            $items[] = ['label' => 'Hausaufgaben', 'url' => ['/homework/index']];
            $items[] = [
                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post', 'class' => 'nav-link btn btn-link text-warning']
            ];
        } else {
            $items[] = ['label' => 'Hausaufgaben', 'url' => ['/homework/index']];
            $items[] = [
                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post', 'class' => 'nav-link btn btn-link text-warning']
            ];
        }

        echo Nav::widget([
                'options' => ['class' => 'navbar-nav ms-auto'],
                'items' => $items,
        ]);
        NavBar::end();
        ?>
    </header>

    <main id="main" class="flex-shrink-0" role="main">
        <div class="container">
            <?= Alert::widget() ?>

            <?php
            $action = Yii::$app->controller->action->id;
            if (in_array($action, ['login', 'register'])): ?>
                <div class="auth-container">
                    <div class="auth-box shadow">
                        <?= $content ?>
                    </div>
                </div>
            <?php else: ?>
                <?= $content ?>
            <?php endif; ?>
        </div>
    </main>

    <footer id="footer" class="mt-auto py-3 bg-white border-top">
        <div class="container">
            <div class="row text-muted small">
                <div class="col-md-6 text-center text-md-start">&copy; StudyOrganizer <?= date('Y') ?></div>
                <div class="col-md-6 text-center text-md-end">Effizientes Lernen leicht gemacht</div>
            </div>
        </div>
    </footer>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>