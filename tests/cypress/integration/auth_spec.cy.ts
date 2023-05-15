describe('Authentication Tests', () => {
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

    it('lets a valid user login', () => {
        cy.refreshDatabase();
        cy.seed();
        cy.create('App\\Models\\User', { email: 'joe@test.com' });
        cy.visit('/#/auth/login');
        cy.get('[data-cy="email"]').type('joe@test.com');
        cy.get('[data-cy="password"]').type('password');
        cy.get('[data-cy="login"]').click();
        cy.wait(2000);
        cy.contains('Dashboard');
    });
});
