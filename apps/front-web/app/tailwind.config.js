module.exports = {
  purge: [],
  theme: {
    extend: {
      colors: {
        psred: {
          extralighter: '#f6dadc',
          lighter: '#e59197',
          default: '#dd6d74',
        },
        psgray: '#f7f7f7',
        psblack: {
          lighter: '#282828',
          default: '#222323',
        },
        psblackop90: 'rgba(34, 35, 35, 0.9)',
        pswhite: '#e5e2e2',
      },
      fontSize: {
        '7xl': '5rem',
        '8xl': '6rem',
        '9xl': '7rem',
        '10xl': '8rem',
      },
      fontFamily: {
        ps: ['Poppins', 'sans-serif'],
        psbold: ['Poppins-bold', 'sans-serif'],
        pssemibold: ['Poppins-semibold'],
      },
      opacity: {
        90: '0.90',
      },
      borderRadius: {
        xl: '0.8rem',
        '2xl': '2rem',
        '3xl': '3rem',
      },
      screens: {
        dark: { raw: '(prefers-color-scheme: dark)' },
      },
      fill: (theme) => ({
        psblack: theme('colors.psblack'),
        white: theme('colors.white'),
      }),
    },
  },

  variants: {},
  plugins: [],
}
