<?php

use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Form;
use App\Admin\Extensions\Form\Ck5Decoupled;

Form::extend('ck5', Ck5Decoupled::class);

// Charte graphique BCJ37 (chargee apres les styles OpenAdmin pour surcharger
// les variables Bootstrap). Voir public/css/admin-bcj.css.
Admin::css('/css/admin-bcj.css');

// Quill (CSS + JS)
Admin::css('https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css');
Admin::js('https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js');

// (Optionnel) si tu veux Quill v2, remplace 1.3.7 par @2.x et adapte si besoin.
