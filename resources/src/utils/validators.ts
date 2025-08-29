export function isValidName(name: string) {
  return /^[a-zA-Z\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u00FF-]+(?:[ '-][a-zA-Z\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u00FF]+)*$/.test(
    name,
  )
}

export function isValidEmail(email: string) {
  // evaluate the email with a regular expression
  return /^[^\s@]+@[^\s@][^\s.@]*\.[^\s@]+$/.test(email)
}
