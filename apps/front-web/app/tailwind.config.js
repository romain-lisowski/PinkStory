module.exports = {
  future: {
    // removeDeprecatedGapUtilities: true,
    // purgeLayersByDefault: true,
  },
  purge: [],
  theme: {
    extend: {
      textColor: {
        primary: 'var(--color-text-primary)',
        secondary: {
          extralighter: 'var(--color-text-secondary-extralighter)',
          lighter: 'var(--color-text-secondary-lighter)',
          default: 'var(--color-text-secondary)',
        },
        default: 'var(--color-text-default)',
      },
      backgroundColor: {
        primary: {
          default: 'var(--color-bg-primary)',
          lighter: 'var(--color-bg-primary-lighter)',
        },
        secondary: {
          extralighter: 'var(--color-bg-secondary-extralighter)',
          lighter: 'var(--color-bg-secondary-lighter)',
          default: 'var(--color-bg-secondary)',
        },
        default: 'var(--color-bg-default)',
      },
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
      fill: (theme) => ({
        psblack: theme('colors.psblack'),
        white: theme('colors.white'),
      }),
    },
  },

  variants: {},
  plugins: [],
}
