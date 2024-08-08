export function isValidName(name: string) {
  return /^[a-zA-ZÀ-ÖØ-öø-ÿ-]+([ '-][a-zA-ZÀ-ÖØ-öø-ÿ]+)*$/.test(name)
}

export function isValidEmail(email: string) {
  // evaluate the email with a regular expression
  return /^[^\s@]+@[^\s@][^\s.@]*\.[^\s@]+$/.test(email)
}
