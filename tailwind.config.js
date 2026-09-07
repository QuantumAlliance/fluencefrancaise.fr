/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./resources/**/*.{js,ts,jsx,tsx,vue,blade.php}",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['DM Sans', 'system-ui', 'sans-serif'],
        display: ['Urbanist', 'system-ui', 'sans-serif'],
        serif: ['Playfair Display', 'Georgia', 'Times New Roman', 'serif'],
      },
      colors: {
        primary: '#0055A4',
        secondary: '#0055A4',
        accent: { DEFAULT: '#EF4135', dark: '#cf2e22', soft: '#fde8e6' },
        ink: '#16213E',
        brand: {
          50: '#eff5fb',
          100: '#dbe9f6',
          200: '#bcd5ee',
          300: '#8eb8e1',
          400: '#5993d0',
          500: '#0055A4',
          600: '#0055A4',
          700: '#00468a',
          800: '#003366',
          900: '#002654',
        },
        indigo: {
          50: '#eff5fb',
          100: '#dbe9f6',
          200: '#bcd5ee',
          300: '#8eb8e1',
          400: '#5993d0',
          500: '#0055A4',
          600: '#00468a',
          700: '#003d7a',
          800: '#003366',
          900: '#002654',
        },
        purple: {
          50: '#eff5fb',
          100: '#dbe9f6',
          200: '#bcd5ee',
          300: '#8eb8e1',
          400: '#5993d0',
          500: '#0055A4',
          600: '#00468a',
          700: '#003d7a',
          800: '#003366',
          900: '#002654',
        },
      },
    },
  },
  plugins: [],
}
