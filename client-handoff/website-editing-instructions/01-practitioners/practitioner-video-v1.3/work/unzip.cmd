@echo off
if "%~1"=="-Z1" powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0unzip.ps1" Z1 "%~2"
if "%~1"=="-p" powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0unzip.ps1" p "%~2" "%~3"
