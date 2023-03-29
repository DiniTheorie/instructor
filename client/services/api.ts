import axios from 'axios'
import { displayError, displaySuccess } from './notifiers'
import type { ExamCategory } from '@/components/domain/Category'
import type { Question, QuestionWithUrls } from '@/components/domain/Question'

const validImageTypes = ['image/jpeg', 'image/png', 'image/gif']

let baseUrl = ''
if (window.location.hostname === 'localhost') {
  baseUrl += 'https://localhost:8000'
  axios.defaults.baseURL = baseUrl
}

const api = {
  setup: function (translator: (label: string) => string) {
    axios.interceptors.response.use(
      (response) => {
        const url = response.config.url
        const method = response.config.method
        if (url?.endsWith('/publish')) {
          const message = translator('service.api.published')
          displaySuccess(message)
        } else if (method === 'post') {
          if (url?.endsWith('/image')) {
            const message = translator('service.api.image_created')
            displaySuccess(message)
          } else {
            const message = translator('service.api.created')
            displaySuccess(message)
          }
        } else if (method === 'put') {
          const message = translator('service.api.stored')
          displaySuccess(message)
        } else if (method === 'delete') {
          if (url?.endsWith('/image')) {
            const message = translator('service.api.image_removed')
            displaySuccess(message)
          } else {
            const message = translator('service.api.removed')
            displaySuccess(message)
          }
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
          return result.data as QuestionWithUrls
        },
        post: async function (categoryId: string, question: Question) {
          const result = await axios.post('/api/exam/category/' + categoryId + '/question', question)
          return result.data as QuestionWithUrls
        },
        put: async function (categoryId: string, question: Question) {
          const result = await axios.put('/api/exam/category/' + categoryId + '/question/' + question.id, question)
          return result.data as QuestionWithUrls
        },
        delete: async function (categoryId: string, id: string) {
          return axios.delete('/api/exam/category/' + categoryId + '/question/' + id)
        },
        getImageUrl: function (categoryId: string, id: string, image: string) {
          return baseUrl + '/api/exam/category/' + categoryId + '/question/' + id + '/image/' + image
        },
        postImage: function (categoryId: string, id: string, image: File, overrideImageName?: string) {
          let imageName = image.name
          if (overrideImageName) {
            const extension = imageName.split('.').pop()
            imageName = overrideImageName + '.' + extension
          }
          const data = new FormData()
          data.append('image', image, imageName)
          return axios.post('/api/exam/category/' + categoryId + '/question/' + id + '/image', data)
        },
        deleteImage: function (categoryId: string, id: string, image: string) {
          return axios.delete('/api/exam/category/' + categoryId + '/question/' + id + '/image/' + image)
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
