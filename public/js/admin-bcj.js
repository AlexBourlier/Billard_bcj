/*
 * Ameliorations UX de l'administration BCJ37 (vanilla JS, sans dependance).
 * Charge via Admin::js('/js/admin-bcj.js').
 *
 *  1. Avertissement avant de quitter une page d'edition/creation avec des
 *     modifications non enregistrees.
 *  2. Apercu de l'article (titre + media + contenu) dans une fenetre modale.
 *
 * Fonctionne avec la navigation PJAX d'OpenAdmin grace a une delegation
 * d'evenements posee une seule fois sur `document`.
 */
(function () {
  'use strict';

  /* ---------------------------------------------------------------- */
  /* Modale de confirmation (charte du site, centree)                 */
  /* ---------------------------------------------------------------- */

  function bcjConfirm(onConfirm) {
    var el = document.getElementById('bcj-confirm-overlay');
    if (!el) {
      el = document.createElement('div');
      el.id = 'bcj-confirm-overlay';
      el.className = 'bcj-modal-overlay';
      el.hidden = true;
      el.innerHTML =
        '<div class="bcj-modal" role="alertdialog" aria-modal="true" aria-labelledby="bcj-confirm-title">' +
          '<div class="bcj-modal__bar" id="bcj-confirm-title">Modifications non enregistrees</div>' +
          '<div class="bcj-modal__body">' +
            'Vous avez des modifications non enregistrees sur cette page.<br>' +
            'Voulez-vous vraiment la quitter&nbsp;? Vos changements seront perdus.' +
          '</div>' +
          '<div class="bcj-modal__actions">' +
            '<button type="button" class="btn btn-light bcj-modal__cancel">Rester sur la page</button>' +
            '<button type="button" class="btn btn-primary bcj-modal__ok">Quitter sans enregistrer</button>' +
          '</div>' +
        '</div>';
      document.body.appendChild(el);
    }

    var okBtn = el.querySelector('.bcj-modal__ok');
    var cancelBtn = el.querySelector('.bcj-modal__cancel');

    function onKey(e) { if (e.key === 'Escape') close(); }
    function close() {
      el.hidden = true;
      okBtn.onclick = null;
      cancelBtn.onclick = null;
      el.onclick = null;
      document.removeEventListener('keydown', onKey);
    }

    okBtn.onclick = function () { close(); onConfirm(); };
    cancelBtn.onclick = close;
    el.onclick = function (e) { if (e.target === el) close(); };
    document.addEventListener('keydown', onKey);

    el.hidden = false;
    cancelBtn.focus();
  }

  /* ---------------------------------------------------------------- */
  /* 1) Avertissement "modifications non enregistrees"                */
  /* ---------------------------------------------------------------- */

  var dirty = false;

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

  // Fermeture d'onglet / rafraichissement : dialogue natif du navigateur
  // (impossible a personnaliser, impose par le navigateur pour raisons de securite).
  window.addEventListener('beforeunload', function (e) {
    if (dirty) { e.preventDefault(); e.returnValue = ''; }
  });

  // Navigation interne (liens, menu, PJAX) : modale de confirmation stylee.
  document.addEventListener('click', function (e) {
    if (!dirty) return;
    var a = e.target.closest ? e.target.closest('a[href]') : null;
    if (!a) return;
    var href = a.getAttribute('href') || '';
    if (href === '' || href.charAt(0) === '#' || href.indexOf('javascript:') === 0) return;
    if (a.getAttribute('target') === '_blank' || a.hasAttribute('download')) return;

    var dest = a.href; // URL absolue resolue
    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();

    bcjConfirm(function () {
      dirty = false;
      window.location.href = dest;
    });
  }, true);

  /* ---------------------------------------------------------------- */
  /* 2) Apercu de l'article                                           */
  /* ---------------------------------------------------------------- */

  // Convertit une URL YouTube (watch / youtu.be) en URL d'integration, comme
  // le fait le backend. Retourne l'URL telle quelle si non reconnue.
  function toEmbedUrl(url) {
    if (!url) return '';
    var m = url.match(/watch\?v=([a-zA-Z0-9_-]+)/);
    if (m) return 'https://www.youtube.com/embed/' + m[1];
    m = url.match(/youtu\.be\/([a-zA-Z0-9_-]+)/);
    if (m) return 'https://www.youtube.com/embed/' + m[1];
    return url;
  }

  // Image a previsualiser : la nouvelle image selectionnee en priorite, sinon
  // l'image deja enregistree (exposee par le formulaire).
  function currentImageUrl() {
    var fileInput = document.querySelector('input[type="file"]');
    if (fileInput && fileInput.files && fileInput.files[0]) {
      return URL.createObjectURL(fileInput.files[0]);
    }
    var existing = document.querySelector('.bcj-current-thumb');
    return existing && existing.value ? existing.value : '';
  }

  function ensurePreviewModal() {
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
          '<div class="bcj-preview__media"></div>' +
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
    var overlay = ensurePreviewModal();

    // Titre.
    var titleInput = document.querySelector('input[name="title"]');
    overlay.querySelector('.bcj-preview__title').textContent =
      (titleInput && titleInput.value.trim()) ? titleInput.value.trim() : '(Titre a renseigner)';

    // Media : la video (si renseignee) remplace l'image, comme sur le site public.
    var videoInput = document.querySelector('input[name="video"]');
    var videoUrl = videoInput ? toEmbedUrl(videoInput.value.trim()) : '';
    var media = '';
    if (videoUrl) {
      media = '<div class="bcj-preview__video"><iframe src="' + videoUrl +
              '" title="Video de l\'article" frameborder="0" ' +
              'allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" ' +
              'allowfullscreen></iframe></div>';
    } else {
      var img = currentImageUrl();
      if (img) media = '<img class="bcj-preview__image" src="' + img + '" alt="">';
    }
    overlay.querySelector('.bcj-preview__media').innerHTML = media;

    // Contenu : lu a la volee dans l'editeur Quill.
    var ta = document.querySelector('textarea[name="content"]');
    var wrap = ta ? ta.closest('.oa-ck5-wrapper') : null;
    var editor = wrap ? wrap.querySelector('.ql-editor') : null;
    var html = editor ? editor.innerHTML : (ta ? ta.value : '');
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

  /* ---------------------------------------------------------------- */
  /* 3) Liens de menu hors /admin (ex. doc API) -> nouvel onglet       */
  /* ---------------------------------------------------------------- */

  function markExternalMenuLinks() {
    var links = document.querySelectorAll('.sidebar a[href]');
    for (var i = 0; i < links.length; i++) {
      var a = links[i];
      if (a.dataset.bcjExt) continue;
      try {
        var u = new URL(a.href, window.location.origin);
        // Meme domaine mais hors du prefixe /admin : la doc API est servie a la
        // racine du domaine, on l'ouvre donc dans un nouvel onglet.
        if (u.origin === window.location.origin && u.pathname.indexOf('/admin') !== 0) {
          a.target = '_blank';
          a.rel = 'noopener noreferrer';
          a.dataset.bcjExt = '1';
        }
      } catch (e) {
        /* URL invalide : on ignore */
      }
    }
  }

  document.addEventListener('DOMContentLoaded', markExternalMenuLinks);
})();
