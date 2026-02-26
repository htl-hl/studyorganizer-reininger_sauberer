<?php

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">StudyOrganizer!</h1>
    </div>

    <div class="body-content">
        <div class="row">
            <div class="col-lg-12 text-center">
                <p class="lead mb-4">The Perfect Tool to organize your Homework, Exams and School projects</p>

                <?php if (Yii::$app->user->isGuest): ?>
                    <a class="btn btn-primary btn-lg px-5" href="<?= \yii\helpers\Url::to(['/site/login']) ?>">Login</a>
                <?php else: ?>
                    <a class="btn btn-success btn-lg px-5" href="<?= \yii\helpers\Url::to(['/homework/index']) ?>">Homework</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
