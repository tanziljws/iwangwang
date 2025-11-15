@echo off
echo Cleaning up node_modules and package-lock.json...

REM Delete node_modules folder if it exists
if exist "node_modules" (
    echo Deleting node_modules folder...
    rmdir /s /q node_modules
) else (
    echo node_modules folder not found.
)

REM Delete package-lock.json if it exists
if exist "package-lock.json" (
    echo Deleting package-lock.json...
    del /f /q package-lock.json
) else (
    echo package-lock.json not found.
)

echo Cleanup complete!
pause
