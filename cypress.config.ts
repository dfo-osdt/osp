import { defineConfig } from 'cypress'
import {
  activateCypressEnvFile,
  activateLocalEnvFile,
} from './tests/cypress/plugins/swap-env'

export default defineConfig({
  projectId: '7wfgwp',
  chromeWebSecurity: false,
  experimentalModifyObstructiveThirdPartyCode: true,
  experimentalSourceRewriting: true,
  retries: 2,
  defaultCommandTimeout: 5000,
  watchForFileChanges: true,
  videosFolder: 'tests/cypress/videos',
  screenshotsFolder: 'tests/cypress/screenshots',
  fixturesFolder: 'tests/cypress/fixture',
  downloadsFolder: 'tests/cypress/downloads',
  e2e: {
    // setupNodeEvents(on, config) {
    //   on('task', { activateCypressEnvFile, activateLocalEnvFile })
    // },
    baseUrl: 'http://127.0.0.1:8000/',
    specPattern: 'tests/cypress/integration/**/*.cy.{js,jsx,ts,tsx}',
    supportFile: 'tests/cypress/support/index.ts',
  },
  viewportHeight: 1080,
  viewportWidth: 1920,
})
