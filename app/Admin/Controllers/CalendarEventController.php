<?php

namespace App\Admin\Controllers;

use Carbon\Carbon;
use App\Models\Calendar;
use App\Models\CalendarEvent;
use App\Models\CalendarEventLink;
use OpenAdmin\Admin\Facades\Admin;
use Illuminate\Http\Request;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

class CalendarEventController extends AdminController
{
    protected $title = 'Calendrier';

    public function links($event)
    {
        $event = CalendarEvent::with('links')->findOrFail($event);

        $backUrl = preg_replace('#/\d+/links$#', '', request()->url());

        $html = view('admin.calendar-events.links', [
            'event' => $event,
            'backUrl' => $backUrl,
            'categories' => [
                'top_ligue' => 'Top Ligue',
                'master' => 'Master',
                'mixte' => 'Mixte',
                'mixte_tableau_a' => 'Mixte Tableau A',
                'mixte_tableau_b' => 'Mixte Tableau B',
                'feminin' => 'Féminin',
                'u15' => 'U15',
                'u18' => 'U18',
                'u23' => 'U23',
                'espoir' => 'Espoir',
                'junior' => 'Junior',
                'individuel' => 'Individuel',
                'equipe' => 'Équipe',
                'doublette' => 'Doublette',
                'handi' => 'Handi',
                'handi_debout' => 'Handi Debout',
                'handi_fauteuil' => 'Handi Fauteuil',
                'veteran' => 'Vétéran',
            ],
        ])->render();

        return Admin::content(function ($content) use ($event, $html) {
            $content->title('Liens du tournoi');
            $content->description($event->titre);
            $content->body($html);
        });
    }

    public function saveLinks(Request $request, $event)
    {
        $event = CalendarEvent::findOrFail($event);

        $event->links()->delete();

        foreach ($request->input('links', []) as $index => $link) {
            if (empty($link['category']) || empty($link['url'])) {
                continue;
            }

            $event->links()->create([
                'category' => $link['category'],
                'label' => $link['label'] ?? null,
                'url' => $link['url'],
                'sort_order' => $link['sort_order'] ?? $index + 1,
            ]);
        }

        admin_success('Succès', 'Les liens ont été enregistrés.');

        $backUrl = preg_replace('#/\d+/links$#', '', request()->url());

        return redirect($backUrl);
    }

    protected function grid()
    {
        $calendar = $this->getCalendarFromUrl();
        $grid = new Grid(new CalendarEvent());

        $grid->model()
            ->where('calendar_id', $calendar->id)
            ->orderBy('date_debut', 'asc');

        $grid->column('id', 'ID')->sortable();
        $grid->column('date_debut', 'Date début')->display(function ($date) {
            return $date ? Carbon::parse($date)->format('d/m/Y') : '';
        })->sortable();

        $grid->column('date_fin', 'Date fin')->display(function ($date) {
            return $date ? Carbon::parse($date)->format('d/m/Y') : '';
        });

        $grid->column('date_limite', 'Date limite')->display(function ($date) {
            return $date ? Carbon::parse($date)->format('d/m/Y') : '';
        });
        $grid->column('titre', 'Titre');
        $grid->column('lieu', 'Lieu');
        $grid->column('club', 'Club');
        $grid->column('url', 'URL')->display(function ($url) {
            return $url ? "<a href=\"{$url}\" target=\"_blank\">Lien</a>" : '';
        });

        $grid->column('links_count', 'Liens')->display(function () {
            $count = $this->links()->count();

            $url = request()->url() . '/' . $this->id . '/links';

            return "<a class=\"btn btn-sm btn-primary\" href=\"{$url}\">{$count} lien(s)</a>";
        });

        $grid->filter(function ($filter) {
            $filter->like('titre', 'Titre');
            $filter->like('lieu', 'Lieu');
            $filter->between('date_debut', 'Date début')->date();
        });

        return $grid;
    }

    protected function form()
    {
        $calendar = $this->getCalendarFromUrl();
        $form = new Form(new CalendarEvent());

        $form->hidden('calendar_id')->default($calendar->id);

        $form->datetime('date_debut', 'Date début')->required();
        $form->datetime('date_fin', 'Date fin')->required();
        $form->datetime('date_limite', 'Date limite');

        $form->text('titre', 'Titre')->required();
        $form->text('lieu', 'Lieu')->required();
        $form->text('club', 'Club');
        $form->url('url', 'URL principale');

        $form->select('status', 'Statut')->options([
            'inscription' => 'Inscription',
            'termine' => 'Terminé',
            'annule' => 'Annulé',
        ]);

        return $form;
    }

    protected function detail($id)
    {
        $show = new Show(CalendarEvent::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('calendar.display_name', 'Calendrier');
        $show->field('date_debut', 'Date début');
        $show->field('date_fin', 'Date fin');
        $show->field('date_limite', 'Date limite');
        $show->field('titre', 'Titre');
        $show->field('lieu', 'Lieu');
        $show->field('club', 'Club');
        $show->field('url', 'URL');
        $show->field('status', 'Statut');

        return $show;
    }

    private function getCalendarFromUrl(): Calendar
    {
        $path = request()->path();

        preg_match('#admin/calendriers/([^/]+)/([^/]+)/events#', $path, $matches);

        abort_if(count($matches) < 3, 404);

        return Calendar::where('discipline', $matches[1])
            ->where('scope', $matches[2])
            ->firstOrFail();
    }

}