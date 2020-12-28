const baseUrl = process.env.VUE_APP_API_URL

export default {
  async search(locale = 'fr', jwt = null) {
    const response = await fetch(
      `${baseUrl}/story-theme/search?_locale=${locale}`,
      {
        headers: { Authorization: `Bearer ${jwt}` },
      }
    )

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },
}
