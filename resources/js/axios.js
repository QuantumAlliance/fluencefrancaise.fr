import axios from 'axios'

// Enable credentials (cookies) to be sent with requests
axios.defaults.withCredentials = true
axios.defaults.baseURL = import.meta.env.VITE_API_URL

// Configure axios to automatically send XSRF token
axios.defaults.xsrfCookieName = 'XSRF-TOKEN'
axios.defaults.xsrfHeaderName = 'X-XSRF-TOKEN'

// Initialize CSRF protection by fetching the CSRF cookie
export const initCsrf = async () => {
  try {
    await axios.get('/sanctum/csrf-cookie')
  } catch (error) {
    console.error('Failed to initialize CSRF token:', error)
    throw error
  }
}

export default axios
