describe('Authentication Tests', () => {
  it('shows a homepage', () => {
    // cy.refreshDatabase();
    // cy.seed();
    cy.visit('/')
    cy.contains('Login')
  })

  it('shows a login page', () => {
    cy.visit('/#/auth/login')
    cy.contains('Login')
  })

  it('lets a valid user login', () => {
    cy.refreshDatabase()
    cy.seed()
    cy.create('App\\Models\\User', { email: 'joe@example.com' })
    cy.visit('/#/auth/login')
    cy.get('[data-cy="email"]').type('joe@example.com')
    cy.get('[data-cy="password"]').type('password')
    cy.get('[data-cy="login"]').click()
    cy.wait(2000)
    cy.contains('Dashboard')
  })

  it('lets a user reset their password', () => {
    cy.visit('/#/auth/forgot-password')
    cy.get('[data-cy="email"]').type('wrong@test.com')
    cy.get('[data-cy="reset-password"]').click()
    cy.wait(1000)
    cy.contains('If the email address is registered, you will receive an email with instructions on how to reset your password.')
    cy.reload()
    cy.get('[data-cy="email"]').clear()
    cy.get('[data-cy="email"]').type('joe@example.com')
    cy.get('[data-cy="reset-password"]').click()
    cy.wait(1000)
    cy.contains('If the email address is registered, you will receive an email with instructions on how to reset your password.')
  })

  it('lets a user register', () => {
    // randome 12 char password
    const password = Math.random().toString(36).slice(-12)
    cy.refreshDatabase()
    cy.seed()
    cy.visit('/#/auth/register')
    cy.get('[data-cy="first_name"]').type('Joe')
    cy.get('[data-cy="last_name"]').type('Test')
    cy.get('[data-cy="email"]').type('joe.test@example.com')
    cy.get('[data-cy="password"]').type(password)
    cy.get('[data-cy="password-confirm"]').type(password)
    cy.get('[data-cy="register-btn"]').click()
    cy.wait(2000)
    cy.contains('Just one last step...')
  })
})
