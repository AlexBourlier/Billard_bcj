<form method="POST">
    @csrf

    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">{{ $event->titre }}</h3>
        </div>

        <div class="box-body">
            <table class="table table-bordered" id="links-table">
                <thead>
                    <tr>
                        <th>Catégorie</th>
                        <th>Libellé</th>
                        <th>URL</th>
                        <th>Ordre</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($event->links as $index => $link)
                        <tr>
                            <td>
                                <select name="links[{{ $index }}][category]" class="form-control category-field">
                                    @foreach($categories as $value => $label)
                                        <option value="{{ $value }}" @selected($link->category === $value)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="links[{{ $index }}][label]" class="form-control label-field" value="{{ $link->label }}">
                            </td>
                            <td>
                                <input type="url" name="links[{{ $index }}][url]" class="form-control url-field" value="{{ $link->url }}">
                            </td>
                            <td>
                                <input type="number" name="links[{{ $index }}][sort_order]" class="form-control sort-field" value="{{ $link->sort_order }}">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-link">Supprimer</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td>
                                <select name="links[0][category]" class="form-control category-field">
                                    @foreach($categories as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="links[0][label]" class="form-control label-field">
                            </td>
                            <td>
                                <input type="url" name="links[0][url]" class="form-control url-field">
                            </td>
                            <td>
                                <input type="number" name="links[0][sort_order]" class="form-control sort-field" value="1">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-link">Supprimer</button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <button type="button" class="btn btn-success" id="add-link">
                Ajouter un lien
            </button>
        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-primary">
                Enregistrer les liens
            </button>

            <a href="{{ $backUrl }}" class="btn btn-default">
                Retour
            </a>
        </div>
    </div>
</form>

<table style="display:none;">
    <tbody>
        <tr id="link-row-template">
            <td>
                <select class="form-control category-field">
                    @foreach($categories as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" class="form-control label-field">
            </td>
            <td>
                <input type="url" class="form-control url-field">
            </td>
            <td>
                <input type="number" class="form-control sort-field">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-link">Supprimer</button>
            </td>
        </tr>
    </tbody>
</table>

<script>
(function () {
    function initLinksEditor() {
        const tableBody = document.querySelector('#links-table tbody');
        const addButton = document.getElementById('add-link');
        const template = document.getElementById('link-row-template');

        if (!tableBody || !addButton || !template) {
            return;
        }

        addButton.onclick = function () {
            const index = tableBody.querySelectorAll('tr').length;
            const newRow = template.cloneNode(true);

            newRow.removeAttribute('id');
            newRow.style.display = '';

            newRow.querySelector('.category-field').name = 'links[' + index + '][category]';
            newRow.querySelector('.label-field').name = 'links[' + index + '][label]';
            newRow.querySelector('.url-field').name = 'links[' + index + '][url]';
            newRow.querySelector('.sort-field').name = 'links[' + index + '][sort_order]';

            newRow.querySelector('.sort-field').value = index + 1;

            tableBody.appendChild(newRow);
        };

        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-link')) {
                const row = event.target.closest('tr');

                if (row) {
                    row.remove();
                }
            }
        });
    }

    initLinksEditor();

    if (window.jQuery) {
        jQuery(document).on('pjax:end', initLinksEditor);
    }
})();
</script>