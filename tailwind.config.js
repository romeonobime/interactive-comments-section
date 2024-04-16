/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    colors: {
      'primary': '#5357B6',
      'primary-light': '#C5C6EF',
      'danger': '#ED6368',
      'danger-light': '#FFB8BB',
      'neutral-1': '#E9EBF0',
      'neutral-1-very-light': '#F5F6FA',
      'neutral-2': '#67727E',
      'neutral-2-dark': '#334253',
      'white': '#ffffff',
    },
    extend: {},
  },
  plugins: [],
}
