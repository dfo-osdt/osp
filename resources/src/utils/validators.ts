const NAME_REGEX = /^[a-zA-Z\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u00FF-]+(?:[ '-][a-zA-Z\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u00FF]+)*$/
const EMAIL_REGEX = /^[^\s@]+@[^\s@][^\s.@]*\.[^\s@]+$/

export function isValidName(name: string) {
  return NAME_REGEX.test(name)
}

export function isValidEmail(email: string) {
  return EMAIL_REGEX.test(email)
}
