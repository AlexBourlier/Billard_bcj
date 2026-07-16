@php
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Str;

    $articles = $metrics['articles'];
    $documents = $metrics['documents'];
    $partenaires = $metrics['partenaires'];
    $licencies = $metrics['licencies'];
    $evenements = $metrics['evenements'];
    $technique = $metrics['technique'];

    $maxDiscipline = collect($articles['byDiscipline'])->max() ?: 1;

    $importStatus = $technique['lastImport']?->status;
    $importStatusValue = is_object($importStatus) ? ($importStatus->value ?? (string) $importStatus) : (string) ($importStatus ?? '');
@endphp

<style>
    .dashboard-bcj .card { border-radius: 10px; }
    .dashboard-bcj .stat-value { font-size: 2rem; font-weight: 700; line-height: 1; }
    .dashboard-bcj .muted-note { font-size: .8rem; color: #6c757d; }
    .dashboard-bcj .bar-row { display: flex; align-items: center; gap: .5rem; margin-bottom: .4rem; }
    .dashboard-bcj .bar-label { flex: 0 0 90px; font-size: .85rem; }
    .dashboard-bcj .bar-track { flex: 1; background: #eef1f6; border-radius: 6px; height: 14px; overflow: hidden; }
    .dashboard-bcj .bar-fill { height: 100%; background: linear-gradient(90deg, #ff65a3, #0e4daa); }
    .dashboard-bcj .bar-count { flex: 0 0 28px; text-align: right; font-size: .8rem; color: #495057; }
    .dashboard-bcj .list-line { display: flex; justify-content: space-between; gap: .5rem; padding: .3rem 0; border-bottom: 1px solid #f0f1f4; }
    .dashboard-bcj .list-line:last-child { border-bottom: 0; }
</style>

<div class="dashboard-bcj">

    {{-- Raccourcis vers les principales actions --}}
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a class="btn btn-primary btn-sm" href="{{ admin_url('posts/create') }}"><i class="icon-plus"></i> Nouvel article</a>
        <a class="btn btn-outline-primary btn-sm" href="{{ admin_url('partenaires/create') }}"><i class="icon-plus"></i> Nouveau partenaire</a>
        <a class="btn btn-outline-primary btn-sm" href="{{ admin_url('documents/create') }}"><i class="icon-plus"></i> Nouveau document</a>
        <a class="btn btn-outline-secondary btn-sm" href="{{ admin_url('license-import/batches') }}">Imports des licenciés</a>
    </div>

    {{-- Cartes chiffres cles : chaque carte mene a sa section --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-lg-3">
            <div class="card h-100 position-relative">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Articles</div>
                    <div class="stat-value">{{ $articles['total'] }}</div>
                    @if($articles['latest'])
                        <div class="muted-note">Dernier : {{ Str::limit($articles['latest']->title, 34) }}</div>
                    @endif
                    <a class="stretched-link" href="{{ admin_url('posts') }}" aria-label="Voir les articles"></a>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card h-100 position-relative">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Documents</div>
                    <div class="stat-value">{{ $documents['total'] }}</div>
                    <div class="muted-note">Fichiers mis a disposition</div>
                    <a class="stretched-link" href="{{ admin_url('documents') }}" aria-label="Voir les documents"></a>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card h-100 position-relative">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Partenaires actifs</div>
                    <div class="stat-value">{{ $partenaires['active'] }}</div>
                    @if($partenaires['expiringSoon']->isNotEmpty())
                        <div class="muted-note text-warning">
                            {{ $partenaires['expiringSoon']->count() }} arrive(nt) a expiration sous 30 j
                        </div>
                    @else
                        <div class="muted-note">Aucune expiration proche</div>
                    @endif
                    <a class="stretched-link" href="{{ admin_url('partenaires') }}" aria-label="Voir les partenaires"></a>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card h-100 position-relative">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Licencies</div>
                    <div class="stat-value">{{ $licencies['total'] }}</div>
                    <div class="muted-note">Repartitions par discipline / categorie : non encore suivies</div>
                    <a class="stretched-link" href="{{ admin_url('licencies') }}" aria-label="Voir les licencies"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Prochains evenements --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title">Prochains evenements</h6>
                    @forelse($evenements['upcoming'] as $event)
                        <div class="list-line">
                            <span>{{ Str::limit($event->titre, 28) }}</span>
                            <span class="text-muted small">{{ Carbon::parse($event->date_debut)->format('d/m/Y') }}</span>
                        </div>
                    @empty
                        <p class="muted-note mb-0">Aucun evenement a venir enregistre.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Suivi technique --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title">Suivi technique</h6>
                    <div class="list-line">
                        <span>Dernier import licencies</span>
                        <span class="text-muted small">
                            @if($technique['lastImport'])
                                {{ $importStatusValue }} · {{ Carbon::parse($technique['lastImport']->started_at)->format('d/m/Y') }}
                            @else
                                aucun
                            @endif
                        </span>
                    </div>
                    <div class="list-line">
                        <span>Imports en echec</span>
                        <span>
                            @if($technique['failedImports'] > 0)
                                <span class="badge bg-danger">{{ $technique['failedImports'] }}</span>
                            @else
                                <span class="badge bg-success">0</span>
                            @endif
                        </span>
                    </div>
                    <div class="list-line">
                        <span>Synchros CueScore en erreur</span>
                        <span>
                            @if($technique['cuescoreErrors'] > 0)
                                <span class="badge bg-danger">{{ $technique['cuescoreErrors'] }}</span>
                            @else
                                <span class="badge bg-success">0</span>
                            @endif
                        </span>
                    </div>
                    <a class="small" href="{{ admin_url('license-import/batches') }}">Voir les imports</a>
                </div>
            </div>
        </div>

        {{-- Articles par discipline (repond a : quelle discipline publie le plus) --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title">Articles par discipline</h6>
                    @forelse($articles['byDiscipline'] as $name => $count)
                        <div class="bar-row">
                            <span class="bar-label">{{ $name }}</span>
                            <span class="bar-track">
                                <span class="bar-fill" style="width: {{ round($count / $maxDiscipline * 100) }}%"></span>
                            </span>
                            <span class="bar-count">{{ $count }}</span>
                        </div>
                    @empty
                        <p class="muted-note mb-0">Aucun article.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Contenus recemment modifies --}}
    <div class="row g-3 mt-1">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title">Articles recemment modifies</h6>
                    @forelse($articles['recentlyUpdated'] as $post)
                        <div class="list-line">
                            <a href="{{ admin_url('posts/' . $post->id . '/edit') }}">{{ Str::limit($post->title, 40) }}</a>
                            <span class="text-muted small">{{ Carbon::parse($post->updated_at)->format('d/m/Y') }}</span>
                        </div>
                    @empty
                        <p class="muted-note mb-0">Aucun article.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title">Partenaires arrivant a expiration</h6>
                    @forelse($partenaires['expiringSoon'] as $partenaire)
                        <div class="list-line">
                            <a href="{{ admin_url('partenaires/' . $partenaire->id . '/edit') }}">{{ Str::limit($partenaire->titre, 34) }}</a>
                            <span class="text-muted small">{{ Carbon::parse($partenaire->date_fin)->format('d/m/Y') }}</span>
                        </div>
                    @empty
                        <p class="muted-note mb-0">Aucun partenariat n'arrive a expiration dans les 30 jours.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Indicateurs non encore suivis (honnete : pas de chiffre invente) --}}
    <p class="muted-note mt-3">
        Non encore suivis (a activer plus tard) : brouillons et publications programmees des articles,
        cotisations, repartition detaillee des licencies.
    </p>

</div>
