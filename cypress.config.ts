import { defineConfig } from 'cypress';

export default defineConfig({
    projectId: '7wfgwp',
    chromeWebSecurity: false,
    retries: 2,
    defaultCommandTimeout: 5000,
    watchForFileChanges: false,
    videosFolder: 'tests/cypress/videos',
    screenshotsFolder: 'tests/cypress/screenshots',
    fixturesFolder: 'tests/cypress/fixture',
    downloadsFolder: 'tests/cypress/downloads',
    e2e: {
        setupNodeEvents(on, config) {
            return require('./tests/cypress/plugins/index.js')(on, config);
        },
        baseUrl: 'https://osp.test',
        specPattern: 'tests/cypress/integration/**/*.cy.{js,jsx,ts,tsx}',
        supportFile: 'tests/cypress/support/index.js',
    },
    viewportHeight: 1080,
    viewportWidth: 1920,
});
