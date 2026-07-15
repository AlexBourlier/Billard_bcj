<?php

namespace App\Admin\Controllers\Americain\Liens_cuescore;

use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use OpenAdmin\Admin\Admin;
use \App\Models\AmericainRegionalLink;
use App\Models\AmericainCalendrierRegional;
use OpenAdmin\Admin\Controllers\AdminController;

class AdminAmericainRegionalLink extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Lien CueScore américain régional';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AmericainRegionalLink());

        $grid->header(function () {
            $url = admin_url('americain/calendrier');
            return <<<HTML
                <a href="{$url}" class="btn btn-primary" style="margin: auto; margin-bottom: 10px; justify-content:center; display:flex; width:250px;">
                ↩️ Retour au calendrier Américain
                </a>
            HTML;
        });

        // Récupère le paramètre GET s'il existe
        $calendrierId = request()->get('calendrier_id');

        if ($calendrierId) {
            $grid->model()->where('calendrier_id', $calendrierId);
        }

        $grid->column('calendrier.titre', __('Tournoi'));
        $grid->column('mixte', __('Mixte'))->display(fn($v) => $v ? "<a href='{$v}' target='_blank' style='text-decoration:none; color:#000;'>{$v}</a>" : "-");
        $grid->column('feminin', __('Feminin'))->display(fn($v) => $v ? "<a href='{$v}' target='_blank' style='text-decoration:none; color:#000;'>{$v}</a>" : "-");
        $grid->column('U18', __('U18'))->display(fn($v) => $v ? "<a href='{$v}' target='_blank' style='text-decoration:none; color:#000;'>{$v}</a>" : "-");
        $grid->column('U15', __('U15'))->display(fn($v) => $v ? "<a href='{$v}' target='_blank' style='text-decoration:none; color:#000;'>{$v}</a>" : "-");
        $grid->column('U23', __('U23'))->display(fn($v) => $v ? "<a href='{$v}' target='_blank' style='text-decoration:none; color:#000;'>{$v}</a>" : "-");
        $grid->column('handi', __('Handi'))->display(fn($v) => $v ? "<a href='{$v}' target='_blank' style='text-decoration:none; color:#000;'>{$v}</a>" : "-");
        $grid->column('veteran', __('Veteran'))->display(fn($v) => $v ? "<a href='{$v}' target='_blank' style='text-decoration:none; color:#000;'>{$v}</a>" : "-");

        $grid->footer(function ($query) {
    $dateDuJour = now()->format('d/m/Y');

    return "
        <div style=\"text-align: center; padding: 10px;\">
            <button class=\"btn btn-primary\" id=\"voir-inscrits\">Voir la liste des inscrits</button>
        </div>

        <div id=\"inscritsModal\" style=\"display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%); background:white; border:1px solid #ccc; padding:20px; max-height:80%; overflow-y:auto; z-index:1000; width:80%;\">
            <h4 style=\"text-align:center;\">Liste des inscrits au {$dateDuJour}</h4>
            <div id=\"modal-titre\" style=\"text-align:center; font-weight:bold; margin-bottom:15px;\"></div>
            <div id=\"modal-content\">Chargement...</div>
            <div style=\"text-align:right; margin-top:10px;\">
                <button class=\"btn btn-secondary\" onclick=\"document.getElementById('inscritsModal').style.display='none'\">Fermer</button>
            </div>
        </div>";
});

$licencies = \App\Support\LicencieNames::fullNames();

$licenciesJson = json_encode($licencies, JSON_UNESCAPED_UNICODE);

Admin::script("window.licencies = $licenciesJson;");
// Ajout du script via Admin::script()
Admin::script(<<<'JS'
document.getElementById("voir-inscrits")?.addEventListener("click", function () {
    const lignes = [...document.querySelectorAll("tr")];
    const idsCueScore = {}; // { colonne: id }

    const colonnes = ["mixte", "feminin", "U18", "U15", "U23", "handi", "veteran"];
    const colonnesLabels = {
        mixte: "Mixte",
        feminin: "Féminin",
        U18: "U18",
        U15: "U15",
        U23: "U23",
        handi: "Handi",
        veteran: "Vétéran",
    };

    const headCols = [...document.querySelectorAll("thead th")];
    const indexColonnes = {};

    // Associer les colonnes aux index (ex: "mixte" => 2)
    colonnes.forEach(key => {
        const idx = headCols.findIndex(th => th.innerText.trim().toLowerCase() === colonnesLabels[key].toLowerCase());
        if (idx !== -1) indexColonnes[key] = idx;
    });

    lignes.forEach(row => {
        if (row.classList.contains("actions")) return;
        const cols = [...row.querySelectorAll("td")];

        Object.entries(indexColonnes).forEach(([colKey, idx]) => {
            const link = cols[idx]?.querySelector("a");
            if (link) {
                const href = link.href;
                const parts = href.split("/");
                const last = parts[parts.length - 1];
                if (!isNaN(last)) {
                    idsCueScore[colKey] = last;
                }
            }
        });
    });

    if (Object.keys(idsCueScore).length === 0) {
        document.getElementById("modal-content").innerHTML = "<em>Aucun ID CueScore trouvé</em>";
        document.getElementById("inscritsModal").style.display = "block";
        return;
    }

    document.getElementById("modal-titre").innerText = "Liste des inscrits par catégorie";
    document.getElementById("modal-content").innerHTML = "<em>Chargement des participants...</em>";
    document.getElementById("inscritsModal").style.display = "block";

    const promises = [];
    let html = "";

    Object.entries(idsCueScore).forEach(([colKey, cueId]) => {
        const label = colonnesLabels[colKey] || colKey;
        const url = "https://api.cuescore.com/tournament/?id=" + cueId + "&participants=Participants+list";

        promises.push(
            fetch(url)
                .then(response => response.json())
                .then(data => {
    html += `<h5 style="margin-top:30px;">${label}</h5>`;
    html += '<div style="display: flex; flex-wrap: wrap; gap: 15px;">';

    function removeAccents(str) {
        return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    }

    const licenciesNormalized = window.licencies.map(n => removeAccents(n.toLowerCase().trim()));


    if (Array.isArray(data)) {
        data.forEach(p => {
            const participantName = removeAccents((p.name || "").toLowerCase().trim());

            if (!licenciesNormalized.includes(participantName)) return;

            html += '<div style="width: 200px; border: 1px solid #ccc; padding: 10px; border-radius: 8px; text-align: center;">' +
                '<a href="' + p.url + '" target="_blank" style="text-decoration: none; color: black;">' +
                    '<img src="' + (p.image || 'https://via.placeholder.com/100') + '" alt="' + p.name + '" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;"><br>' +
                    '<strong>' + p.name + '</strong><br>' +
                    (p.country ? '<img src="' + p.country.image + '" alt="' + p.country.name + '" title="' + p.country.name + '" style="width: 24px; height: auto; margin-top: 5px;">' : '') +
                '</a>' +
            '</div>';
        });
    } else {
        html += "<em>Aucun participant trouvé</em>";
    }

    html += '</div>';
})
                .catch(error => {
                    console.error("Erreur fetch pour ID " + cueId + ":", error);
                })
        );
    });

    Promise.all(promises).then(() => {
        document.getElementById("modal-content").innerHTML = html;
    });
});


JS);

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(AmericainRegionalLink::findOrFail($id));

        $url = admin_url('americain/calendrier');
        $show->setResource(admin_url('americain/calendrier')); // Optionnel

        $show->panel()
            ->tools(function ($tools) use ($url) {
                $tools->prepend(<<<HTML
                    <div style="margin-bottom: 10px;">
                        <a href="{$url}" class="btn btn-primary" style="width: 250px; display: flex; justify-content: center;">
                            ↩️ Retour au calendrier Américain
                        </a>
                    </div>
                HTML);
            });

        $show->field('calendrier.titre', __('Tournoi'));
        $show->field('mixte', __('Mixte'));
        $show->field('feminin', __('Feminin'));
        $show->field('U18', __('U18'));
        $show->field('U15', __('U15'));
        $show->field('U23', __('U23'));
        $show->field('handi', __('Handi'));
        $show->field('veteran', __('Veteran'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new AmericainRegionalLink());

        $url = admin_url('americain/calendrier');
        $form->html('               
            <a href="' . $url . '" class="btn btn-primary" style="margin: auto; margin-bottom: 10px; justify-content:center; display:flex; width:250px;">
                ↩️ Retour au calendrier Américain
                </a>
            '
        );

        if ($form->isCreating()) {
            // En création : tu fies l'ID via un champ hidden + affichage lecture seule
            $form->hidden('calendrier_id')->default(request('calendrier_id'));

            $calendrier = AmericainCalendrierRegional::find(request('calendrier_id'));
            if ($calendrier) {
                $form->display('titre_calendrier', 'Tournoi')->default($calendrier->titre);
            }
        } else {
            // En édition : l-ID est déjà défini
            $form->display('calendrier.titre', 'Tournoi');
        }
        $form->url('mixte', __('Mixte'));
        $form->url('feminin', __('Feminin'));
        $form->url('U18', __('U18'));
        $form->url('U15', __('U15'));
        $form->url('U23', __('U23'));
        $form->url('handi', __('Handi'));
        $form->url('veteran', __('Veteran'));

        // redirection après sauvegarde
        $form->saved(function (Form $form) {
            return redirect('/admin/americain-calendrier-regionals');
        });

        return $form;
    }
}
