describe('Example Test', () => {
    it('shows a homepage', () => {
        cy.refreshDatabase();
        cy.seed();
        cy.visit('/');
        cy.contains('Login');
    });
});
