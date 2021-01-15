const baseUrl = process.env.VUE_APP_API_URL

export default {
  async search(jwt) {
    const response = await fetch(`${baseUrl}/language/search`, {
      headers: { Authorization: jwt ? `Bearer ${jwt}` : null },
    })

    const responseJson = await response.json()
    return { ok: response.ok, status: response.status, ...responseJson }
  },
}
