@include('admin::form.error')

<div class="oa-ck5-wrapper" id="{{ $id }}_wrap">
  {{-- Bouton d'apercu : ouvre le rendu de l'article (titre + contenu) en modale.
       La logique est dans public/js/admin-bcj.js (classe .bcj-preview-btn). --}}
  <div class="oa-ck5-actions">
    <button type="button" class="btn btn-sm btn-outline-primary bcj-preview-btn">
      Aperçu de l'article
    </button>
  </div>

  <div class="editor-container editor-container_document-editor">
    {{-- Zone principale : toolbar + page --}}
    <div class="editor-container__main">
      {{-- (optionnel) barre de menu, laissée vide ici --}}
      <div class="editor-container__menu-bar" id="{{ $id }}_menu"></div>

      {{-- Toolbar Quill (doit avoir les classes ql-toolbar ql-snow pour le thème) --}}
      <div class="editor-container__toolbar">
        <div id="{{ $id }}_toolbar" class="ql-toolbar ql-snow"></div>
      </div>

      <div class="editor-container__editor-wrapper"
           style="max-height: {{ $height }}px; min-height: {{ $height }}px; overflow-y:auto;">
        <div class="editor-container__editor">
          {{-- Hôte de Quill : le script remplacera le contenu par .ql-container/.ql-editor --}}
          <div id="{{ $id }}_editor">{!! old($column, $value ?? '') !!}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- textarea réel (soumis au backend) --}}
  <textarea id="{{ $id }}" name="{{ $name }}" style="display:none;">{!! old($column, $value ?? '') !!}</textarea>
</div>

@include('admin::form.help-block')

<style>
/* --- Conteneur global : centré, padding latéral, largeur max --- */
.oa-ck5-wrapper{
  max-width: 1360px;
  margin: 0 auto;
  padding: 0 24px;
}

/* --- Mise en page (colonne unique ici) --- */
.editor-container{
  display: grid;
  grid-template-columns: 1fr;
  gap: 16px;
  border: 1px solid #e5e7eb;
  background: #f9fafb;
}

/* Colonne principale */
.editor-container__main{
  display: grid;
  grid-template-rows: auto auto 1fr;
}

/* Toolbar visuellement séparée (Quill) */
.editor-container__toolbar .ql-toolbar{
  border-top: 0;
  border-left: 0;
  border-right: 0;
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
  box-shadow: 0 2px 3px rgba(0,0,0,.08);
}

/* Wrapper scrollable de l’aire d’édition */
.editor-container__editor-wrapper{
  background: #f3f4f6;
}

/* “Page” (A4) appliquée à la zone éditable de Quill */
.editor-container_document-editor .editor-container__editor .ql-editor{
  box-sizing: border-box;
  min-width: calc(210mm + 2px);
  max-width: calc(210mm + 2px);
  min-height: 297mm;
  height: fit-content;
  padding: 20mm 12mm;
  border: 1px #d3d3d3 solid;
  background: #fff;
  box-shadow: 0 2px 3px rgba(0,0,0,.08);
  margin: 28px 48px;
}

/* Optionnel : si tu veux que les images ne dépassent jamais la “page” */
.editor-container_document-editor .ql-editor img{
  max-width: 100%;
  height: auto;
}

/* Optionnel : style des listes pour rester propre dans la “page” */
.editor-container_document-editor .ql-editor ol,
.editor-container_document-editor .ql-editor ul{
  padding-left: 1.5rem;
}
</style>
