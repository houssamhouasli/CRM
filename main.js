const { app, BrowserWindow, dialog } = require('electron');
const { spawn, execSync } = require('child_process');
const path = require('path');
const fs = require('fs');

let php;

function findPhp() {
    const candidates = [
        path.join(__dirname, 'php', 'php.exe'),
        'C:\\laragon\\bin\\php\\php-8.3.26-Win32-vs16-x64\\php.exe',
        'C:\\laragon\\bin\\php\\php-8.2.12-Win32-vs16-x64\\php.exe',
        'C:\\laragon\\bin\\php\\php-8.1.10-Win32-vs16-x64\\php.exe',
        'C:\\xampp\\php\\php.exe',
        'C:\\wamp64\\bin\\php\\php8.3.0\\php.exe',
        'C:\\php\\php.exe',
    ];

    for (const phpPath of candidates) {
        if (fs.existsSync(phpPath)) {
            return phpPath;
        }
    }
    return 'php'; // Fallback direct
}

function createWindow() {
    const phpPath = findPhp();

    php = spawn(
        phpPath,
        ['artisan', 'serve', '--host=127.0.0.1', '--port=8000'],
        {
            cwd: __dirname,
            windowsHide: true
        }
    );

    php.on('error', (err) => {
        dialog.showErrorBox(
            'Erreur PHP',
            `Impossible de lancer PHP. Veuillez vérifier que PHP est installé.\n\nDétails: ${err.message}`
        );
    });

    const win = new BrowserWindow({
        width: 1200,
        height: 800,
        icon: path.join(__dirname, 'public', 'favicon.ico'),
        webPreferences: {
            webviewTag: true,
            contextIsolation: true,
            nodeIntegration: false
        }
    });

    // Attendre qu'artisan démarre, puis charger la coquille avec barre de navigation
    setTimeout(() => {
        win.loadFile(path.join(__dirname, 'electron-shell.html'));
    }, 1500);
}

app.whenReady().then(createWindow);

app.on('window-all-closed', () => {
    if (php) php.kill();
    app.quit();
});