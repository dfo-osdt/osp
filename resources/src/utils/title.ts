import DOMPurify from 'dompurify'

/** Tags allowed in a formatted title (see App\Services\TitleMarkup). */
export const TITLE_TAGS = ['i', 'sup', 'sub']

export function sanitizeTitle(title: string | null | undefined) {
  if (!title)
    return ''
  return DOMPurify.sanitize(title, { ALLOWED_TAGS: TITLE_TAGS, ALLOWED_ATTR: [] })
}

/** The title without formatting, for places that only take text (dialogs, tooltips). */
export function titleToPlainText(title: string | null | undefined) {
  if (!title)
    return ''
  return new DOMParser().parseFromString(title, 'text/html').body.textContent ?? ''
}

const DROPPED_ELEMENTS = ['script', 'style', 'template', 'noscript']
const BREAKING_ELEMENTS = ['br', 'p', 'div', 'li', 'tr', 'td', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6']

function escapeHtml(text: string) {
  return text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
}

/** The title tag an element stands for, e.g. <em> or a span styled italic (Google Docs) is <i>. */
function formatOf(element: Element) {
  const tag = element.tagName.toLowerCase()
  if (TITLE_TAGS.includes(tag))
    return tag
  if (tag === 'em')
    return 'i'
  const style = (element.getAttribute('style') ?? '').toLowerCase()
  if (/font-style\s*:\s*(?:italic|oblique)/.test(style))
    return 'i'
  const align = style.match(/vertical-align\s*:\s*(super|sub)\b/)
  if (align)
    return align[1] === 'super' ? 'sup' : 'sub'
  return null
}

/**
 * Reduce pasted HTML (Word, Google Docs, journal pages) to title markup,
 * keeping italics, superscript and subscript and never nesting a format inside itself.
 */
export function cleanPastedTitle(html: string) {
  const walk = (node: Node, active: string[]): string => Array.from(node.childNodes).map((child) => {
    if (child.nodeType === Node.TEXT_NODE)
      return escapeHtml(child.textContent ?? '')
    if (!(child instanceof Element))
      return ''
    const tag = child.tagName.toLowerCase()
    if (DROPPED_ELEMENTS.includes(tag))
      return ''
    const format = formatOf(child)
    const newFormat = format !== null && !active.includes(format) ? format : null
    const inner = walk(child, newFormat ? [...active, newFormat] : active)
    const wrapped = newFormat ? `<${newFormat}>${inner}</${newFormat}>` : inner
    return BREAKING_ELEMENTS.includes(tag) ? ` ${wrapped} ` : wrapped
  }).join('')

  const body = new DOMParser().parseFromString(html, 'text/html').body
  return walk(body, []).replace(/\s+/g, ' ')
}
