import axios from 'axios'
import { displayError, displaySuccess } from './notifiers'
import type { ExamCategory } from '@/components/domain/exam/types'

const validImageTypes = ['image/jpeg', 'image/png', 'image/gif']

if (window.location.hostname === 'localhost') {
  axios.defaults.baseURL = 'https://localhost:8000'
}

const api = {
  setup: function (translator: (label: string) => string) {
    axios.interceptors.response.use(
      (response) => {
        console.log(response)
        if (response.config.url.endsWith('/publish')) {
          const message = translator('service.api.published')
          displaySuccess(message)
        } else if (response.config.method === 'post') {
          const message = translator('service.api.created')
          displaySuccess(message)
        } else if (response.config.method === 'put') {
          const message = translator('service.api.stored')
          displaySuccess(message)
        } else if (response.config.method === 'delete') {
          const message = translator('service.api.removed')
          displaySuccess(message)
        }

        return response
      },
      (error) => {
        /* eslint-disable-next-line eqeqeq */
        if (error == 'Error: Request aborted') {
          // hide aborted errors (happens when navigating rapidly in firefox)
          return
        }

        console.log(error)

        let errorText = error
        if (error.response) {
          const response = error.response
          errorText = response.status
          if (response.data && response.data.detail) {
            errorText += ': ' + response.data.detail
          } else if (response.statusText) {
            errorText += ': ' + response.statusText
          }
        }

        const errorMessage = translator('service.api.request_failed') + ' (' + errorText + ')'
        displayError(errorMessage)

        return Promise.reject(error)
      }
    )
  },
  store: function () {
    return axios.post('/api/publish')
  },
  getExamCategoryIds: async function () {
    const result = await axios.get('/api/exam/categoryIds')
    return result.data as string[]
  },
  getExamCategory: async function (id: string) {
    const result = await axios.get('/api/exam/category/' + id)
    return result.data as ExamCategory
  },
  putExamCategory: async function (category: ExamCategory) {
    const result = await axios.put('/api/exam/category/' + category.id, category)
    return result.data as ExamCategory
  }
}

export { api, validImageTypes }
