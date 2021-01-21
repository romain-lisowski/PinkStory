module.exports = {
  devServer: {
    https: true,
    host: '0.0.0.0',
    port: 3000,
    watchOptions: {
      poll: true,
    },
  },
  chainWebpack: (config) => {
    config.plugin('html').tap((args) => {
      // eslint-disable-next-line no-param-reassign
      args[0].title = 'PinkStory'
      return args
    })
  },
  pluginOptions: {
    i18n: {
      locale: 'fr',
      fallbackLocale: 'fr',
      enableInSFC: true,
    },
  },
}
