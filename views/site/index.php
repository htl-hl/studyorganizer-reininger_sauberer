<?php
use yii\helpers\Url;
$this->title = 'StudyOrganizer';
?>

<div class="site-index">
    <div class="hero-section text-center">
        <h1 class="display-3 main-title">Study<span class="text-primary">Organizer</span></h1>

        <div class="quote-wrapper mb-5 mt-4">
            <?php if (Yii::$app->user->isGuest): ?>
                <p class="lead">Chaos im Kopf? <br> <span class="text-muted">Strukturiere deinen Erfolg mit einem Klick.</span></p>
            <?php else: ?>
                <p class="lead">Willkommen zurück! <br> <span class="text-muted">Bereit für die nächste produktive Session?</span></p>
            <?php endif; ?>
        </div>

        <div class="d-flex justify-content-center flex-wrap gap-3 mt-4">
            <?php if (Yii::$app->user->isGuest): ?>
                <a class="btn-custom btn-primary-gradient" href="<?= Url::to(['/site/login']) ?>">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Jetzt starten</span>
                </a>
            <?php elseif (Yii::$app->user->identity->role === 'admin'): ?>
                <a class="btn-custom btn-dark-flat" href="<?= Url::to(['/teachers/index']) ?>">
                    <i class="bi bi-people-fill"></i>
                    <span>Lehrer</span>
                </a>
                <a class="btn-custom btn-dark-outline" href="<?= Url::to(['/subjects/index']) ?>">
                    <i class="bi bi-book-half"></i>
                    <span>Fächer</span>
                </a>
            <?php else: ?>
                <a class="btn-custom btn-primary-gradient" href="<?= Url::to(['/homework/index']) ?>">
                    <i class="bi bi-rocket-takeoff-fill"></i>
                    <span>Meine Aufgaben</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>