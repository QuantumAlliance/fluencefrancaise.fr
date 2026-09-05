// Inline placeholders for catalogue art. Kept local so a missing image never
// depends on an external host being reachable.

const svg = (label) => {
  const markup = `<svg xmlns="http://www.w3.org/2000/svg" width="400" height="200" viewBox="0 0 400 200">
    <rect width="400" height="200" fill="#e8eef6"/>
    <path d="M0 150 L110 90 L190 140 L280 70 L400 145 L400 200 L0 200 Z" fill="#cbd9ea"/>
    <circle cx="315" cy="52" r="22" fill="#cbd9ea"/>
    <text x="200" y="185" text-anchor="middle" font-family="Segoe UI, Arial, sans-serif"
      font-size="15" font-weight="600" fill="#0055A4">${label}</text>
  </svg>`

  return `data:image/svg+xml;charset=utf-8,${encodeURIComponent(markup)}`
}

export const COURSE_PLACEHOLDER = svg('Course')
export const EXAM_PREP_PLACEHOLDER = svg('Exam Prep')

// Swap in the placeholder if the real file 404s, so no broken icon is shown.
export const makeImageErrorHandler = (placeholder) => (event) => {
  if (event.target.src !== placeholder) {
    event.target.src = placeholder
  }
}
