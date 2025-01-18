
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useLoginStore = defineStore('loginData', () => {
  const loginData = ref({
    email: ''
  })

  const updateLoginData = (newData) => {
    loginData.value = newData
  }

  return { loginData, updateLoginData }
})