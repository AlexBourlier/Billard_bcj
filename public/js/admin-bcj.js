/*
 * Ameliorations UX de l'administration BCJ37 (vanilla JS, sans dependance).
 * Charge via Admin::js('/js/admin-bcj.js').
 *
 *  1. Avertissement avant de quitter une page d'edition/creation avec des
 *     modifications non enregistrees (fermeture d'onglet, rafraichissement,
 *     navigation interne).
 *  2. Apercu de l'article (titre + contenu) dans une fenetre modale.
 *
 * Fonctionne avec la navigation PJAX d'OpenAdmin grace a une delegation
 * d'evenements posee une seule fois sur `document` (aucune re-initialisation
 * necessaire apres un changement de page).
 */
(function () {
  'use strict';

  /* ---------------------------------------------------------------- */
  /* 1) Avertissement "modifications non enregistrees"                */
  /* ---------------------------------------------------------------- */

  var dirty = false;
  var MSG = 'Vous avez des modifications non enregistrees. Voulez-vous vraiment quitter cette page sans enregistrer ?';

  // On ne surveille que les pages de creation / edition (URL en /create ou
  // /edit) et jamais le formulaire de recherche de la barre laterale.
  function onEditPage() {
    return /\/(create|edit)\/?$/.test(window.location.pathname);
  }

  function inEditForm(el) {
    if (!onEditPage() || !el || !el.closest) return false;
    var form = el.closest('form');
    return !!form && !form.classList.contains('sidebar-form');
  }

  document.addEventListener('input', function (e) { if (inEditForm(e.target)) dirty = true; }, true);
  document.addEventListener('change', function (e) { if (inEditForm(e.target)) dirty = true; }, true);
  document.addEventListener('submit', function (e) { if (inEditForm(e.target)) dirty = false; }, true);

  window.addEventListener('beforeunload', function (e) {
    if (dirty) { e.preventDefault(); e.returnValue = ''; }
  });

  // Navigation interne (liens, menu, PJAX) : confirmer avant de perdre les modifs.
  document.addEventListener('click', function (e) {
    if (!dirty) return;
    var a = e.target.closest ? e.target.closest('a[href]') : null;
    if (!a) return;
    var href = a.getAttribute('href') || '';
    if (href === '' || href.charAt(0) === '#' || href.indexOf('javascript:') === 0) return;
    if (a.getAttribute('target') === '_blank' || a.hasAttribute('download')) return;

    if (window.confirm(MSG)) {
      dirty = false; // l'utilisateur accepte de quitter
    } else {
      e.preventDefault();
      e.stopPropagation();
      e.stopImmediatePropagation();
    }
  }, true);

  /* ---------------------------------------------------------------- */
  /* 2) Apercu de l'article                                           */
  /* ---------------------------------------------------------------- */

  function ensureModal() {
    var overlay = document.getElementById('bcj-preview-overlay');
    if (overlay) return overlay;

    overlay = document.createElement('div');
    overlay.id = 'bcj-preview-overlay';
    overlay.className = 'bcj-preview-overlay';
    overlay.hidden = true;
    overlay.innerHTML =
      '<div class="bcj-preview" role="dialog" aria-modal="true" aria-label="Apercu de l\'article">' +
        '<div class="bcj-preview__bar">' +
          '<span class="bcj-preview__tag">Apercu</span>' +
          '<button type="button" class="bcj-preview__close" aria-label="Fermer l\'apercu">&times;</button>' +
        '</div>' +
        '<article class="bcj-preview__body rich-text">' +
          '<h1 class="bcj-preview__title"></h1>' +
          '<div class="bcj-preview__content"></div>' +
        '</article>' +
      '</div>';
    document.body.appendChild(overlay);

    var close = function () { overlay.hidden = true; };
    overlay.querySelector('.bcj-preview__close').addEventListener('click', close);
    overlay.addEventListener('click', function (e) { if (e.target === overlay) close(); });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !overlay.hidden) close();
    });
    return overlay;
  }

  function openPreview() {
    var overlay = ensureModal();
    var titleInput = document.querySelector('input[name="title"]');

    // Le contenu vit dans l'editeur Quill : on lit sa zone editable a la volee
    // (le textarea n'est synchronise que sur certains evenements).
    var ta = document.querySelector('textarea[name="content"]');
    var wrap = ta ? ta.closest('.oa-ck5-wrapper') : null;
    var editor = wrap ? wrap.querySelector('.ql-editor') : null;
    var html = editor ? editor.innerHTML : (ta ? ta.value : '');

    overlay.querySelector('.bcj-preview__title').textContent =
      (titleInput && titleInput.value.trim()) ? titleInput.value.trim() : '(Titre a renseigner)';
    overlay.querySelector('.bcj-preview__content').innerHTML =
      (html && html.trim()) ? html : '<p><em>Aucun contenu pour le moment.</em></p>';

    overlay.hidden = false;
  }

  document.addEventListener('click', function (e) {
    var btn = e.target.closest ? e.target.closest('.bcj-preview-btn') : null;
    if (!btn) return;
    e.preventDefault();
    openPreview();
  });
})();
