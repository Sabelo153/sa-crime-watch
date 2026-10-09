$ErrorActionPreference = 'Stop'
$watchProject = Split-Path -Parent $PSScriptRoot
$watchTools = Join-Path $watchProject '.tools'
foreach ($watchName in @('php','db')) {
 $watchIdentityFile = Join-Path $watchTools ($watchName + '-process.json')
 if (!(Test-Path -LiteralPath $watchIdentityFile)) { continue }
 $watchIdentity = Get-Content -LiteralPath $watchIdentityFile -Raw | ConvertFrom-Json
 $watchProcess = Get-Process -Id $watchIdentity.Id -ErrorAction SilentlyContinue
 if (!$watchProcess) { continue }
 if ($watchProcess.Path -ne $watchIdentity.Path -or $watchProcess.StartTime.Ticks -ne $watchIdentity.Started) { throw 'Refusing to stop a process whose identity has changed.' }
 Stop-Process -Id $watchProcess.Id
}
Write-Output 'Project preview processes stopped; database files preserved.'
