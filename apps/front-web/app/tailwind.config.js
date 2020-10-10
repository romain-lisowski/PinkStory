module.exports = {
  future: {
    // removeDeprecatedGapUtilities: true,
    // purgeLayersByDefault: true,
  },
  purge: [],
  theme: {
    colors: {},
    textColor: {
      primary: 'rgba(var(--color-text-primary), var(--text-opacity, 1))',
      accent: {
        default: 'rgba(var(--color-text-accent), var(--text-opacity, 1))',
        highlight:
          'rgba(var(--color-text-accent-highlight), var(--text-opacity, 1))',
      },
      'primary-inverse':
        'rgba(var(--color-text-primary-inverse), var(--text-opacity, 1))',
    },
    backgroundColor: {
      primary: 'rgba(var(--color-bg-primary), var(--bg-opacity, 1))',
      accent: {
        default: 'rgba(var(--color-bg-accent), var(--bg-opacity, 1))',
        highlight:
          'rgba(var(--color-bg-accent-highlight), var(--bg-opacity, 1))',
      },
      'primary-inverse':
        'rgba(var(--color-bg-primary-inverse), var(--bg-opacity, 1))',
    },
    borderColor: {
      primary: 'rgba(var(--color-bg-primary), var(--border-opacity, 1))',
      accent: {
        default: 'rgba(var(--color-bg-accent), var(--border-opacity, 1))',
        highlight:
          'rgba(var(--color-bg-accent-highlight), var(--border-opacity, 1))',
      },
      'primary-inverse':
        'rgba(var(--color-bg-primary-inverse), var(--border-opacity, 1))',
    },
    extend: {
      fontFamily: {
        sans: [
          'Poppins',
          'system-ui',
          '-apple-system',
          'BlinkMacSystemFont',
          'Segoe UI',
          'Roboto',
          'Helvetica Neue',
          'Arial',
          'Noto Sans',
          'sans-serif',
          'Apple Color Emoji',
          'Segoe UI Emoji',
          'Segoe UI Symbol',
          'Noto Color Emoji',
        ],
      },
      fontSize: {
        xxs: '.5rem',
        '7xl': '5rem',
        '8xl': '6rem',
        '9xl': '7rem',
        '10xl': '8rem',
      },
      opacity: {
        5: '0.05',
        10: '0.1',
      },
      borderRadius: {
        xl: '0.8rem',
        '2xl': '2rem',
        '3xl': '3rem',
      },
      backgroundImage: {
        'radial-gradient-left':
          'radial-gradient(ellipse farthest-side at 100% 0%, rgba(var(--color-bg-primary), 0) 0, rgba(var(--color-bg-primary), 0) 50%, rgba(var(--color-bg-primary), 1) 100%)',
        'radial-gradient-center':
          'radial-gradient(circle farthest-side at 50% 20%, rgba(var(--color-bg-primary), 0) 0, rgba(var(--color-bg-primary), 0) 50%, rgba(var(--color-bg-primary), 1) 100%)',
        'stars-gradient-to-r':
          'linear-gradient(90deg, rgba(var(--color-bg-accent), 1) 0%, rgba(var(--color-bg-accent), 1) 30%, rgba(var(--color-bg-primary-inverse), 1) 100%)',
      },
    },
    variants: {
      backgroundColor: ['responsive', 'hover', 'focus', 'group-hover'],
      borderColor: ['responsive', 'hover', 'focus', 'group-hover'],
    },
  },

  variants: {},
  plugins: [],
}
