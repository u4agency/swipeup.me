/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    'templates/**/*.html.twig',
  ],
  theme: {
    extend: {
      minHeight: {
        'hd': '720px',
      },
      maxHeight: {
        'fhd': '1920px',
      },
      fontFamily: {
        'swipe': ['"Reem Kufi"', 'sans-serif'],
      },
      colors: {
        'swipe': {
          '100': "#05fefc",
          '200': "#08e8f8",
          '300': "#0bd2f3",
          '400': "#0ebcef",
          '500': "#11a6ea",
          '600': "#1490e6",
          '700': "#177ae1",
          '800': "#1a64dd",
          '900': "#1d4ed8",
        }
      },
      transitionTimingFunction: {
        'gradient': 'cubic-bezier(.1, 0, .9, 1)',
      }
    },
  },
  plugins: [],
}
