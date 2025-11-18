import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useSearchStore = defineStore('search', () => {
  const searchTerm = ref('')

  function setSearchTerm(value) {
    searchTerm.value = value
  }

  return { searchTerm, setSearchTerm }
})
