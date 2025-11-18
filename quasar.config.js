// Configuration for your app
// https://v2.quasar.dev/quasar-cli-vite/quasar-config-file

import { defineConfig } from '#q-app/wrappers'

export default defineConfig((/* ctx */) => {
  return {
    // https://v2.quasar.dev/quasar-cli-vite/prefetch-feature
    // preFetch: true,

    // app boot file (/src/boot)
    boot: ['axios', 'pinia'],

    // https://v2.quasar.dev/quasar-cli-vite/quasar-config-file#css
    css: ['app.scss'],

    // https://github.com/quasarframework/quasar/tree/dev/extras
    extras: [
      'roboto-font',       // fuente base
      'material-icons',    // íconos Material estándar
      'mdi-v7'             // agregado: habilita Material Design Icons (mdi-whatsapp, mdi-facebook, etc.)
    ],

    build: {
      target: {
        browser: ['es2022', 'firefox115', 'chrome115', 'safari14'],
        node: 'node20',
      },

      vueRouterMode: 'hash',

      vitePlugins: [
        [
          'vite-plugin-checker',
          {
            eslint: {
              lintCommand: 'eslint -c ./eslint.config.js "./src*/**/*.{js,mjs,cjs,vue}"',
              useFlatConfig: true,
            },
          },
          { server: false },
        ],
      ],
    },

    devServer: {
      open: true, // abre el navegador automáticamente
    },

    // 👇 modificación importante aquí
    framework: {
      iconSet: 'material-icons',   // set principal de íconos de Quasar
      config: {},
      extras: ['mdi-v7'],          // asegura que mdi esté habilitado también aquí
      plugins: ['Notify']
    },

    animations: [],

    ssr: {
      prodPort: 3000,
      middlewares: ['render'],
      pwa: false,
    },

    pwa: {
      workboxMode: 'GenerateSW',
    },

    cordova: {
      // noIosLegacyBuildFlag: true,
    },

    capacitor: {
      hideSplashscreen: true,
    },

    electron: {
      preloadScripts: ['electron-preload'],
      inspectPort: 5858,
      bundler: 'packager',

      builder: {
        appId: 'dydaccesorios',
      },
    },

    bex: {
      extraScripts: [],
    },
  }
})
