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

$iconLogin = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">  <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>  <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/></svg>';
$iconLogout = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-door-open-fill" viewBox="0 0 16 16">  <path d="M1.5 15a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1H13V2.5A1.5 1.5 0 0 0 11.5 1H11V.5a.5.5 0 0 0-.57-.495l-7 1A.5.5 0 0 0 3 1.5V15zM11 2h.5a.5.5 0 0 1 .5.5V15h-1zm-2.5 8c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1"/></svg>';

?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= Html::encode($this->title) ?></title>

    <?php $this->registerCssFile('@web/css/custom-style.css'); ?>

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

        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }


        .nav-link {
            display: flex !important;
            align-items: center;
            gap: 8px;
        }

        .invalid-feedback,
        .help-block,
        .help-block-error {
            color: #dc3545 !important;
            font-weight: 500;
            margin-top: 5px;
        }

        .is-invalid,
        .has-error input,
        .has-error select,
        .has-error textarea {
            border-color: #dc3545 !important;
        }


        .has-error label {
            color: #dc3545;
        }
    </style>
</head>
    <body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header id="header">
        <?php
        NavBar::begin([
                'brandLabel' => 'Study<span class="text-primary">Organizer</span>',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
        ]);

        $items = [];
        if (Yii::$app->user->isGuest) {
            $items[] = ['label' => $iconLogin, 'url' => ['/site/login']];
        } else {
            if (Yii::$app->user->identity->role === 'admin') {
                $items[] = ['label' => 'Lehrer', 'url' => ['/teachers/index']];
                $items[] = ['label' => 'Fächer', 'url' => ['/subjects/index']];
            } else {
                $items[] = ['label' => 'Hausaufgaben', 'url' => ['/homework/index']];
            }

            $items[] = [
                    'label' => $iconLogout . ' (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => [
                            'data-method' => 'post',
                            'class' => 'nav-link btn btn-link text-warning'
                    ]
            ];
        }

        echo Nav::widget([
                'options' => ['class' => 'navbar-nav ms-auto'],
                'encodeLabels' => false,
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
        <div class="container text-muted small">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">&copy; StudyOrganizer <?= date('Y') ?></div>
                <div class="col-md-6 text-center text-md-end">Effizientes Lernen leicht gemacht</div>
            </div>
        </div>
    </footer>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>