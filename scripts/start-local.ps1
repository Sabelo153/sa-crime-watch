$ErrorActionPreference = 'Stop'
$watchProject = Split-Path -Parent $PSScriptRoot
$watchTools = Join-Path $watchProject '.tools'
$watchServer = [IO.Path]::GetFullPath((Join-Path $watchTools 'mariadb/mariadb-11.4.10-winx64/bin/mariadbd.exe'))
$watchConfig = Join-Path $watchTools 'db-data/my.ini'
if ((Get-Content -LiteralPath (Join-Path $watchProject '.env')) -contains 'DB_CONNECTION=mariadb') {
if (!(Test-Path -LiteralPath $watchConfig)) { throw 'Portable database is not initialized. See docs/LOCAL-TOOLS.md.' }
if (!(Test-Path -LiteralPath $watchServer)) { throw 'Portable MariaDB executable is missing.' }
$watchExisting = Get-Process mariadbd -ErrorAction SilentlyContinue | Where-Object { $_.Path -eq $watchServer }
if (!$watchExisting) {
 $watchProcess = Start-Process -FilePath $watchServer -ArgumentList ('--defaults-file="' + $watchConfig + '"') -WindowStyle Hidden -PassThru
 [IO.File]::WriteAllText((Join-Path $watchTools 'db.pid'), [string]$watchProcess.Id)
 @{Id=$watchProcess.Id;Started=$watchProcess.StartTime.Ticks;Path=$watchServer} | ConvertTo-Json | Set-Content -LiteralPath (Join-Path $watchTools 'db-process.json')
}
}
$watchPhpPidFile = Join-Path $watchTools 'php.pid'
$watchPhpIdentityFile = Join-Path $watchTools 'php-process.json'
if (Test-Path -LiteralPath $watchPhpIdentityFile) {
 $watchIdentity = Get-Content -LiteralPath $watchPhpIdentityFile -Raw | ConvertFrom-Json
 $watchOld = Get-Process -Id $watchIdentity.Id -ErrorAction SilentlyContinue
 if ($watchOld -and $watchOld.Path -eq $watchIdentity.Path -and $watchOld.StartTime.Ticks -eq $watchIdentity.Started) { Write-Output 'Preview process already exists. Open http://localhost:8000'; exit }
}
$watchPhp = (Get-Command php -ErrorAction Stop).Source
$watchPhpArgs = @('-d','extension=gd','-d','upload_max_filesize=5M','-d','post_max_size=24M','-d','memory_limit=256M','-S','127.0.0.1:8000','-t','public','server.php')
$watchPreview = Start-Process -FilePath $watchPhp -ArgumentList $watchPhpArgs -WorkingDirectory $watchProject -WindowStyle Hidden -RedirectStandardOutput (Join-Path $watchTools 'php.stdout.log') -RedirectStandardError (Join-Path $watchTools 'php.stderr.log') -PassThru
[IO.File]::WriteAllText($watchPhpPidFile, [string]$watchPreview.Id)
@{Id=$watchPreview.Id;Started=$watchPreview.StartTime.Ticks;Path=$watchPhp} | ConvertTo-Json | Set-Content -LiteralPath (Join-Path $watchTools 'php-process.json')
Write-Output 'Local preview started: http://localhost:8000'
Write-Output 'Run scripts/stop-local.ps1 to stop project processes.'
