# Starts MySQL, Laravel, and Vite together for local dev.
# Run with: npm run dev:full   (or: powershell -ExecutionPolicy Bypass -File scripts\dev-all.ps1)

$ErrorActionPreference = "Stop"
$root = Split-Path -Parent $PSScriptRoot
Set-Location $root

$mysqld = "C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqld.exe"
$php    = "C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe"

Write-Host "Starting MySQL..." -ForegroundColor Cyan
$mysqlProc = Start-Process -FilePath $mysqld -ArgumentList "--datadir=C:\laragon\data --port=3306" -PassThru -WindowStyle Hidden

# Give MySQL a moment to bind port 3306 before Laravel tries to connect.
$deadline = (Get-Date).AddSeconds(20)
do {
    Start-Sleep -Milliseconds 500
    $up = (Test-NetConnection -ComputerName 127.0.0.1 -Port 3306 -WarningAction SilentlyContinue).TcpTestSucceeded
} until ($up -or (Get-Date) -gt $deadline)

if (-not $up) {
    Write-Host "MySQL did not come up in time, continuing anyway..." -ForegroundColor Yellow
} else {
    Write-Host "MySQL is up." -ForegroundColor Green
}

$env:CACHE_DRIVER = "file"

Write-Host "Starting Laravel (8001) and Vite (5174)..." -ForegroundColor Cyan
try {
    & npx concurrently -n laravel,vite -c magenta,cyan `
        "`"$php`" artisan serve --port=8001" `
        "npm run dev"
} finally {
    Write-Host "Stopping MySQL..." -ForegroundColor Cyan
    Stop-Process -Id $mysqlProc.Id -Force -ErrorAction SilentlyContinue
}
