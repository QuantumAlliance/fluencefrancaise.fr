// Frontend error reporter — ships JS errors and failed axios responses
// to /api/client-error-log so they appear in storage/logs/client-errors-*.log
// on the server.

import axios from 'axios'

const ENDPOINT = '/api/client-error-log'

// Don't spam the server: dedupe identical errors fired in quick succession.
const recentSignatures = new Map() // signature -> last-sent timestamp
const DEDUPE_WINDOW_MS = 30_000

function shouldReport(signature) {
  const now = Date.now()
  const last = recentSignatures.get(signature)
  if (last && now - last < DEDUPE_WINDOW_MS) return false
  recentSignatures.set(signature, now)
  // Cap map size
  if (recentSignatures.size > 100) {
    const oldest = [...recentSignatures.entries()].sort((a, b) => a[1] - b[1])[0][0]
    recentSignatures.delete(oldest)
  }
  return true
}

async function send(payload) {
  const sig = `${payload.kind}|${payload.message}|${payload.source || ''}|${payload.lineno || ''}`
  if (!shouldReport(sig)) return
  try {
    await axios.post(ENDPOINT, {
      ...payload,
      url: window.location.href,
      user_agent: navigator.userAgent,
    }, {
      // Avoid recursive errors if axios itself is broken
      timeout: 5000,
      headers: { 'X-Skip-Error-Reporter': '1' },
    })
  } catch (e) {
    // Last resort: log to console so something exists
    console.warn('[errorReporter] Failed to send error report:', e?.message)
  }
}

// Lightweight, always-sent debug logger (no dedupe). Use to trace a specific
// flow that is failing in production — e.g. the admin exam-prep blank page.
// Lands in storage/logs/client-errors-YYYY-MM-DD.log on the server AND console.
export function reportDebug(message, extra = {}) {
  // eslint-disable-next-line no-console
  console.log('[EXAM-PREP-DEBUG]', message, extra)
  let extraStr = ''
  try { extraStr = JSON.stringify(extra) } catch (e) { extraStr = String(extra) }
  try {
    axios.post(ENDPOINT, {
      kind: 'debug',
      message: `[EXAM-PREP-DEBUG] ${String(message)}`.slice(0, 2000),
      source: (extra.source ? String(extra.source) : '').slice(0, 500),
      response_body: extraStr.slice(0, 2000),
      url: window.location.href,
      user_agent: navigator.userAgent,
    }, {
      timeout: 5000,
      headers: { 'X-Skip-Error-Reporter': '1' },
    }).catch(() => {})
  } catch (e) { /* never let logging break the app */ }
}

export function installErrorReporter() {
  // 1. Uncaught synchronous errors
  window.addEventListener('error', (event) => {
    send({
      kind: 'js-error',
      message: event.message || 'Unknown error',
      source: event.filename,
      lineno: event.lineno,
      colno: event.colno,
      stack: event.error?.stack,
    })
  })

  // 2. Unhandled promise rejections
  window.addEventListener('unhandledrejection', (event) => {
    const reason = event.reason
    send({
      kind: 'unhandled-rejection',
      message: typeof reason === 'string' ? reason : (reason?.message || String(reason)),
      stack: reason?.stack,
    })
  })

  // 3. Failed axios responses (4xx / 5xx / network errors)
  axios.interceptors.response.use(
    (response) => response,
    (error) => {
      // Skip our own reporter calls to prevent loops
      if (error?.config?.headers?.['X-Skip-Error-Reporter']) {
        return Promise.reject(error)
      }
      // Skip noisy "user not logged in" / validation errors — they're expected
      const status = error?.response?.status
      const reqUrl = error?.config?.url || ''
      const skipStatuses = [401, 422]
      if (skipStatuses.includes(status)) {
        return Promise.reject(error)
      }

      let bodySnippet = ''
      try {
        const body = error?.response?.data
        if (body) bodySnippet = JSON.stringify(body).slice(0, 500)
      } catch (e) {}

      send({
        kind: 'axios-error',
        message: error?.message || `HTTP ${status}`,
        request_url: reqUrl,
        request_method: (error?.config?.method || 'GET').toUpperCase(),
        response_status: status || 0,
        response_body: bodySnippet,
        stack: error?.stack,
      })

      return Promise.reject(error)
    }
  )

  // 4. Vue runtime errors — wired up after app is mounted (see app.js)
}

export function attachVueErrorHandler(app) {
  app.config.errorHandler = (err, instance, info) => {
    send({
      kind: 'vue-error',
      message: err?.message || String(err),
      stack: err?.stack,
      source: info, // e.g., "render function"
    })
    // Still log to console so devs see it locally
    // eslint-disable-next-line no-console
    console.error('[Vue error]', err, info)
  }
}
