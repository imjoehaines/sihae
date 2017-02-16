'use strict'

const csrfKey = document.getElementById('csrfKey')
const csrfValue = document.getElementById('csrfValue')

const simplemde = new window.SimpleMDE({
  autoDownloadFontAwesome: false,
  spellChecker: false,
  previewRender: function (plainText, preview) {
    window.fetch('/api/v1/render', {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        post: plainText,
        [csrfKey.name]: csrfKey.value,
        [csrfValue.name]: csrfValue.value
      })
    })
    .then(response => response.json())
    .then(response => {
      preview.innerHTML = response.html

      // update the CSRF tokens
      csrfKey.value = response.csrfKey
      csrfValue.value = response.csrfValue

      // use Prism.js to add syntax highlighting
      window.requestAnimationFrame(window.Prism.highlightAll)
    })

    return 'Loading...'
  }
})

simplemde.render()

const tagDataElement = document.getElementById('tag-data')
const tagData = JSON.parse(tagDataElement.innerText)

// tokenfield has side effects when you call it :(
new window.Tokenfield({ // eslint-disable-line no-new
  el: document.getElementById('tags'),
  items: tagData.tags,
  setItems: tagData.selected_tags,
  itemName: 'tags',
  newItemName: 'new_tags'
})
