<?php

namespace App\Admin\Extensions\Form;

use OpenAdmin\Admin\Form\Field\Textarea;

/**
 * Champ d'edition riche des articles pour OpenAdmin.
 *
 * Attention au nom : malgre « Ck5 » (herite d'une premiere version a base de
 * CKEditor 5), l'implementation actuelle repose sur l'editeur Quill (charge via
 * bootstrap.php). La barre d'outils est volontairement limitee aux formats surs.
 *
 * La securite ne repose pas sur cet editeur : le HTML produit est nettoye cote
 * serveur par liste blanche (voir App\Support\HtmlSanitizer) avant stockage et
 * avant d'etre renvoye par l'API, car le frontend React le rend en HTML brut.
 */
class Ck5Decoupled extends Textarea
{
    protected $view = 'admin.form.ck5-decoupled';

    public function render()
    {
        $id = $this->id ?: $this->formatId($this->column);
        $height = $this->rows ?: 700;

        $this->addVariables([
            'id'          => $id,
            'height'      => $height,
            'editorId'    => $id,
            'containerId' => $id . '_wrap',
            'menuId'      => $id . '_menu',
            'toolbarId'   => $id . '_toolbar',
            'outlineId'   => $id . '_outline',
            'editableId'  => $id . '_editor',
        ]);

        // Initialisation Quill (remplace CKEditor)
        $this->script = strtr(<<<'JS'
(function () {
  if (!window.Quill) {
    console.error('[Quill] librairie non chargée (CDN manquant)'); 
    return;
  }

  var ta = document.getElementById('%id%');
  if (!ta || ta.dataset.qlMounted) return;
  ta.dataset.qlMounted = '1';

  var host       = document.getElementById('%id%_editor');   // zone éditable
  var toolbarDom = document.getElementById('%id%_toolbar');  // barre d'outils
  var form       = ta.closest('form');

  // --- Toolbar HTML (style "décorrélé" comme CKEditor) ---
  // Tu peux ajuster les groupes/boutons en fonction de tes besoins.
  // Barre d'outils volontairement limitee aux formats autorises et surs :
  // pas de police, taille, couleur ni indentation arbitraires (le contenu est
  // de toute facon nettoye cote serveur par liste blanche). Titres bornes a
  // H2-H4 (le H1 est reserve au titre de l'article).
  toolbarDom.innerHTML = `
    <span class="ql-formats">
      <select class="ql-header">
        <option selected></option>
        <option value="2"></option>
        <option value="3"></option>
        <option value="4"></option>
      </select>
    </span>
    <span class="ql-formats">
      <button class="ql-bold"></button>
      <button class="ql-italic"></button>
      <button class="ql-underline"></button>
      <button class="ql-blockquote"></button>
    </span>
    <span class="ql-formats">
      <button class="ql-link"></button>
    </span>
    <span class="ql-formats">
      <button class="ql-list" value="ordered"></button>
      <button class="ql-list" value="bullet"></button>
    </span>
    <span class="ql-formats">
      <button class="ql-clean"></button>
      <button class="ql-undo" title="Annuler">↶</button>
      <button class="ql-redo" title="Rétablir">↷</button>
    </span>
  `;

  // --- Instanciation Quill ---
  var quill = new Quill(host, {
    theme: 'snow',
    modules: {
      toolbar: toolbarDom,
      history: { delay: 1000, userOnly: true }
    },
    placeholder: 'Tapez ou collez votre contenu ici…'
  });

  // --- Contenu initial (depuis le div ou le textarea) ---
  // (ta vue Blade met déjà l’HTML dans %id%_editor et dans le textarea)
  var initialHtml = host.innerHTML && host.innerHTML.trim()
                  ? host.innerHTML
                  : (ta.value && ta.value.trim() ? ta.value : '');
  if (initialHtml) {
    quill.clipboard.dangerouslyPasteHTML(initialHtml);
  }

  // --- Synchronisation vers le <textarea> pour la soumission ---
  var sync = function () { ta.value = quill.root.innerHTML; };
  quill.on('text-change', sync);
  quill.root.addEventListener('blur', sync);
  if (form) form.addEventListener('submit', sync);

  // --- Undo/Redo boutons custom ---
  var undoBtn = toolbarDom.querySelector('.ql-undo');
  var redoBtn = toolbarDom.querySelector('.ql-redo');
  if (undoBtn) undoBtn.addEventListener('click', function(){ quill.history.undo(); });
  if (redoBtn) redoBtn.addEventListener('click', function(){ quill.history.redo(); });

  // --- Nettoyage lors d’un PJAX (OpenAdmin) ---
  document.addEventListener('pjax:beforeReplace', function(){
    try {
      // On détache les écouteurs basiques
      if (undoBtn) undoBtn.replaceWith(undoBtn.cloneNode(true));
      if (redoBtn) redoBtn.replaceWith(redoBtn.cloneNode(true));
      ta.dataset.qlMounted = '';
      // Quill n’a pas d’API destroy officielle en v1/v2;
      // on s’assure surtout de ne pas ré-attacher des handlers en double au reload.
    } catch (e) {}
  });
})();
JS, [
  '%id%' => $id,
]);

        return parent::render();
    }
}
