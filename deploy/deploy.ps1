# =============================================================================
#  Deploiement BCJ37 (Windows / PowerShell) — o2switch
#
#  Enchaine :  1) build du frontend   2) televersement SFTP   3) commandes serveur
#
#  Lancer depuis la racine du projet :
#      powershell -ExecutionPolicy Bypass -File deploy\deploy.ps1
#
#  Prerequis :
#   - Node/npm installes (build du front).
#   - WinSCP installe (WinSCP.com) + une session SFTP enregistree "o2switch-melon"
#     (voir deploy\winscp-upload.txt).
#   - Un client SSH (OpenSSH, inclus dans Windows 10/11) avec acces au serveur,
#     idealement par cle SSH.
#   - Le fichier .env.production present a la racine (non versionne).
# =============================================================================

$ErrorActionPreference = 'Stop'

# ---------------------------- A ADAPTER --------------------------------------
$ProjectRoot = 'C:\laragon\www\BCJ'
$WinScpCom   = 'C:\Program Files (x86)\WinSCP\WinSCP.com'

# TELEVERSEMENT (etape 2) : gere par WinSCP via la session enregistree
# "o2switch-melon" (definie dans deploy\winscp-upload.txt). Rien a regler ici.

# COMMANDES SERVEUR (etape 3) : acces SSH o2switch — ATTENTION, c'est DIFFERENT
# du FTP. Le SSH utilise l'hote du serveur o2switch et le port 22 (le FTP, lui,
# c'est ftp.marmottedraw.fr sur le port 21 : ne pas confondre).
$RunRemoteSteps = $true                  # mets $false si tu n'as pas encore l'acces SSH
$SshUser        = 'boal2619'
$SshHost        = 'melon.o2switch.net'   # hote SSH o2switch (PAS ftp.marmottedraw.fr)
$SshPort        = 22                      # SSH = 22
$ApiPath        = '/home/boal2619/api.test.alexandrebourlier.fr'
# -----------------------------------------------------------------------------

Write-Host '==> 1/3  Build du frontend' -ForegroundColor Cyan
Push-Location (Join-Path $ProjectRoot 'frontend')
try {
    # Le build (rolldown/vite) se contente de LIRE node_modules : il fonctionne
    # meme si un serveur de dev Vite tourne encore. On n'installe donc les
    # dependances que si node_modules est absent, afin d'eviter l'erreur EPERM
    # (suppression d'un binaire natif verrouille par un process en cours).
    if (Test-Path 'node_modules') {
        Write-Host '    node_modules deja present -> installation ignoree.' -ForegroundColor DarkGray
        Write-Host '    (si les dependances ont change : supprime node_modules apres avoir ferme le serveur de dev, puis relance.)' -ForegroundColor DarkGray
    } else {
        npm install --no-audit --no-fund
        if ($LASTEXITCODE -ne 0) { throw "npm install a echoue" }
    }

    npm run build
    if ($LASTEXITCODE -ne 0) { throw "npm run build a echoue" }
} finally {
    Pop-Location
}

Write-Host '==> 1bis  Archive du backend (git archive HEAD)' -ForegroundColor Cyan
# Deploiement deterministe : l'archive contient EXACTEMENT les fichiers versionnes
# (dont resources/views/vendor/), et jamais vendor/node_modules/.env/images. Elle
# est decompressee sur le serveur par remote.sh. Fini les fichiers oublies.
$Archive = Join-Path $ProjectRoot 'deploy\app.tar.gz'
git -C $ProjectRoot archive --format=tar.gz -o $Archive HEAD
if ($LASTEXITCODE -ne 0) { throw "git archive a echoue (depot git ou HEAD invalide ?)" }
Write-Host ("    Archive : {0:N0} Ko" -f ((Get-Item $Archive).Length / 1KB))

Write-Host '==> 2/3  Televersement (WinSCP)' -ForegroundColor Cyan
if (-not (Test-Path $WinScpCom)) { throw "WinSCP.com introuvable : $WinScpCom" }
# Le chemin du script est construit puis passe en UN seul argument : sinon WinSCP
# prend le "C:\..." pour un nom d'hote ("L'hote C n'existe pas").
# NB : PAS de /ini=nul — sinon WinSCP demarre sans ta configuration et ne trouve
# pas la session enregistree "o2switch-melon" ("Pas de session").
$UploadScript = Join-Path $ProjectRoot 'deploy\winscp-upload.txt'
& $WinScpCom "/script=$UploadScript"
if ($LASTEXITCODE -ne 0) { throw "Le televersement WinSCP a echoue (code $LASTEXITCODE)" }

if ($RunRemoteSteps) {
    Write-Host '==> 3/3  Commandes serveur en SSH (composer, migrations, caches)' -ForegroundColor Cyan
    # Rappel : migrate --force applique uniquement les migrations en attente
    # (jamais de reset). Pense a une sauvegarde de la base avant un gros deploi.
    ssh -p $SshPort "$SshUser@$SshHost" "cd '$ApiPath' && bash remote.sh"
    if ($LASTEXITCODE -ne 0) { throw "Les commandes serveur ont echoue (code $LASTEXITCODE)" }
} else {
    Write-Host '==> 3/3  Etape SSH ignoree (RunRemoteSteps = $false).' -ForegroundColor Yellow
    Write-Host '    Lance ces commandes toi-meme dans le Terminal SSH de cPanel (o2switch) :' -ForegroundColor Yellow
    Write-Host "        cd $ApiPath && bash remote.sh" -ForegroundColor Yellow
}

Write-Host ''
Write-Host 'Deploiement termine.' -ForegroundColor Green
Write-Host "Front : https://test.alexandrebourlier.fr"
Write-Host "API   : https://api.test.alexandrebourlier.fr   (docs : /docs)"
