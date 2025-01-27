import Vue from 'vue'
import VueI18n from 'vue-i18n'

Vue.use(VueI18n)
const mergeLocaleMessage = (localeMessages) => {
  const messages = {}

  localeMessages.keys().forEach(key => {
    const matched = key.match(/([A-Za-z0-9-_]+)\./i)
    if (matched && matched.length > 1) {
      const locale = matched[1]
      messages[locale] = localeMessages(key)
    }
  })

  return messages
}

function loadLocaleMessages () {
  let locales = require.context('./locales', true, /[A-Za-z0-9-_,\s]+\.json$/i)
  const defaultMessages = mergeLocaleMessage(locales)
  locales = require.context('./modules/auth-service/auth/locales', true, /[A-Za-z0-9-_,\s]+\.json$/i)
  const authServiceLocales = mergeLocaleMessage(locales)

  const dateTimeFormats = {
    en: {
      short: {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      },
      long: {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        weekday: 'long',
        hour: 'numeric',
        minute: 'numeric'
      },
      time: {
        hour: 'numeric',
        minute: 'numeric'
      }
    },
    bn: {
      short: {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      },
      long: {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        weekday: 'long',
        hour: 'numeric',
        minute: 'numeric',
        hour12: true
      },
      time: {
        hour: 'numeric',
        minute: 'numeric',
        hour12: true
      }
    }
  }
  const allMessages = {
    bn: Object.assign(
      {},
      dateTimeFormats.bn,
      defaultMessages.bn,
      authServiceLocales.bn
    ),
    en: Object.assign(
      {},
      dateTimeFormats.en,
      defaultMessages.en,
      authServiceLocales.en
    )
  }
  return allMessages
}
export default new VueI18n({
  locale: process.env.VUE_APP_I18N_LOCALE || 'en',
  fallbackLocale: process.env.VUE_APP_I18N_FALLBACK_LOCALE || 'en',
  messages: loadLocaleMessages()
})
