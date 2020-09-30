module.exports = {
  root: true,

  env: {
    node: true,
  },

  extends: [
    'plugin:vue/recommended',
    '@vue/airbnb'
  ],

  parserOptions: {
    parser: 'babel-eslint',
  },

  rules: {
    'no-console': 'off',
    'no-debugger': 'off',
    'no-alert': 'off',
    'no-shadow': 'off',
    semi: ['error', 'never'],
    indent: ['error', 2],
    'max-len': ['error', 140, 2],
  },
}