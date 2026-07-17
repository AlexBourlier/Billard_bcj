{{--
    Surcharge de la vue d'aide d'OpenAdmin (admin::form.help-block).

    Au lieu d'un texte d'aide toujours visible sous le champ, on affiche une
    icone info discrete ; le conseil apparait en bulle flottante au survol de la
    souris ou au focus clavier (accessible), pour garder le formulaire epure.

    Le fichier du package n'est pas modifie : Laravel resout d'abord cette vue
    depuis resources/views/vendor/admin/.
--}}
@if($help)
<span class="help-block bcj-help" tabindex="0" role="note" aria-label="Aide sur ce champ">
    <i class="{{ \Illuminate\Support\Arr::get($help, 'icon') ?: 'icon-info-circle' }}" aria-hidden="true"></i>
    <span class="bcj-help__bubble" role="tooltip">{!! \Illuminate\Support\Arr::get($help, 'text') !!}</span>
</span>
@endif
