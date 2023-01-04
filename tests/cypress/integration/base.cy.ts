describe('Base Test', () => {
    it('shows a homepage', () => {
        cy.refreshDatabase();
        cy.seed();
        cy.visit('/');
        cy.contains('Login');
    });

    it('shows a login page', () => {
        cy.visit('/#/auth/login');
        cy.contains('Login');
    });
});
