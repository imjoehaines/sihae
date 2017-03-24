'use strict'

const tagDataElement = document.getElementById('tags')
const tagData = JSON.parse(tagDataElement.dataset.tagData)

// tokenfield has side effects when you call it :(
new window.Tokenfield({ // eslint-disable-line no-new
  el: document.getElementById('tags'),
  items: tagData.tags,
  setItems: tagData.selected_tags,
  itemName: 'tags',
  newItemName: 'new_tags'
})
