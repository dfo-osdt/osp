// import fs
import * as fs from 'fs';

function activateCypressEnvFile() {
    if (fs.existsSync('.env.cypress')) {
        fs.renameSync('.env', '.env.backup');
        fs.renameSync('.env.cypress', '.env');
    }

    return null;
}

function activateLocalEnvFile() {
    if (fs.existsSync('.env.backup')) {
        fs.renameSync('.env', '.env.cypress');
        fs.renameSync('.env.backup', '.env');
    }

    return null;
}

function swapEnvFile() {
    if (fs.existsSync('.env.cypress')) {
        activateCypressEnvFile();
    } else {
        activateLocalEnvFile();
    }
}

export { activateCypressEnvFile, activateLocalEnvFile, swapEnvFile };
