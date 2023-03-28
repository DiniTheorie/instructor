import axios from 'axios'
import { displayError, displaySuccess } from './notifiers'
import type { ExamCategory } from '@/components/domain/Category'
import type { Question } from '@/components/domain/Question'

const validImageTypes = ['image/jpeg', 'image/png', 'image/gif']

if (window.location.hostname === 'localhost') {
  axios.defaults.baseURL = 'https://localhost:8000'
}

const api = {
  setup: function (translator: (label: string) => string) {
    axios.interceptors.response.use(
      (response) => {
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
          errorText = '(' + response.status + ' ' + response.statusText + ')'
          if (response.data && response.data.exception && response.data.exception[0].message) {
            errorText += ': ' + response.data.exception[0].message
          }
        }

        const errorMessage = translator('service.api.request_failed') + ' ' + errorText
        displayError(errorMessage)

        return Promise.reject(error)
      }
    )
  },
  store: function () {
    return axios.post('/api/publish')
  },
  discard: function () {
    return axios.post('/api/discard')
  },
  exam: {
    category: {
      question: {
        getIds: async function (categoryId: string) {
          const result = await axios.get('/api/exam/category/' + categoryId + '/questionIds')
          return result.data as string[]
        },
        get: async function (categoryId: string, id: string) {
          const result = await axios.get('/api/exam/category/' + categoryId + '/question/' + id)
          return result.data as Question
        },
        put: async function (categoryId: string, question: Question) {
          const result = await axios.put('/api/exam/category/' + categoryId + '/question/' + question.id, question)
          return result.data as Question
        },
        delete: async function (categoryId: string, id: string) {
          return axios.delete('/api/exam/category/' + categoryId + '/question/' + id)
        }
      },
      getIds: async function () {
        const result = await axios.get('/api/exam/categoryIds')
        return result.data as string[]
      },
      get: async function (id: string) {
        const result = await axios.get('/api/exam/category/' + id)
        return result.data as ExamCategory
      },
      post: async function (category: ExamCategory) {
        const result = await axios.post('/api/exam/category', category)
        return result.data as ExamCategory
      },
      put: async function (category: ExamCategory) {
        const result = await axios.put('/api/exam/category/' + category.id, category)
        return result.data as ExamCategory
      },
      delete: async function (id: string) {
        return axios.delete('/api/exam/category/' + id)
      }
    }
  }
}

export { api, validImageTypes }
