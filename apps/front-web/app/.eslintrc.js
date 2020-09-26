module.exports = {
  root: true,

  env: {
    node: true,
  },

  extends: [
    'plugin:vue/essential',
    '@vue/airbnb',
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
    'max-len': ['error', 240, 2],
  },

  'extends': [
    'plugin:vue/recommended',
    '@vue/airbnb'
  ]
}
