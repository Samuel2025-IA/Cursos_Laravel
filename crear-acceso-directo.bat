@echo off
echo Creando acceso directo en el escritorio...

powershell "$WshShell = New-Object -comObject WScript.Shell; $Shortcut = $WshShell.CreateShortcut('%USERPROFILE%\Desktop\Cursos.lnk'); $Shortcut.TargetPath = 'powershell.exe'; $Shortcut.Arguments = '-NoExit -Command \"cd C:\xampp\htdocs\cursos; npm run serve\"'; $Shortcut.WorkingDirectory = 'C:\xampp\htdocs\cursos'; $Shortcut.Description = 'Iniciar Proyecto Cursos'; $Shortcut.Save()"

echo.
echo ✓ Acceso directo creado en el escritorio
echo ✓ Nombre: "Cursos"
echo.
pause


