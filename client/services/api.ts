import axios from 'axios'
import { displayError, displaySuccess } from './notifiers'
import type { Category } from '@/components/domain/Category'
import type { Question, QuestionWithUrls } from '@/components/domain/Question'
import type { Chapter } from '@/components/domain/Chapter'
import type { Section } from '@/components/domain/Section'
import type { Article, ArticleWithUrls } from '@/components/domain/Article'

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
        return result.data as Category
      },
      post: async function (category: Category) {
        const result = await axios.post('/api/exam/category', category)
        return result.data as Category
      },
      put: async function (category: Category) {
        const result = await axios.put('/api/exam/category/' + category.id, category)
        return result.data as Category
      },
      delete: async function (id: string) {
        return axios.delete('/api/exam/category/' + id)
      }
    }
  },
  chapter: {
    section: {
      article: {
        getIds: async function (chapterId: string, sectionId: string) {
          const result = await axios.get('/api/theory/chapter/' + chapterId + '/section/' + sectionId + '/articleIds')
          return result.data as string[]
        },
        get: async function (chapterId: string, sectionId: string, id: string) {
          const result = await axios.get('/api/theory/chapter/' + chapterId + '/section/' + sectionId + '/article/' + id)
          return result.data as ArticleWithUrls
        },
        post: async function (chapterId: string, sectionId: string, article: Article) {
          const result = await axios.post('/api/theory/chapter/' + chapterId + '/section/' + sectionId + '/article', article)
          return result.data as ArticleWithUrls
        },
        put: async function (chapterId: string, sectionId: string, article: Article) {
          const result = await axios.put('/api/theory/chapter/' + chapterId + '/section/' + sectionId + '/article/' + article.id, article)
          return result.data as ArticleWithUrls
        },
        delete: async function (chapterId: string, sectionId: string, id: string) {
          return axios.delete('/api/theory/chapter/' + chapterId + '/section/' + sectionId + '/article/' + id)
        },
        getImageUrl: function (chapterId: string, sectionId: string, id: string, image: string) {
          return baseUrl + '/api/theory/chapter/' + chapterId + '/section/' + sectionId + '/article/' + id + '/image/' + image
        },
        postImage: function (chapterId: string, sectionId: string, id: string, image: File) {
          const data = new FormData()
          data.append('image', image)
          return axios.post('/api/theory/chapter/' + chapterId + '/section/' + sectionId + '/article/' + id + '/image', data)
        },
        deleteImage: function (chapterId: string, sectionId: string, id: string, image: string) {
          return axios.delete('/api/theory/chapter/' + chapterId + '/section/' + sectionId + '/article/' + id + '/image/' + image)
        }
      },
      getIds: async function (chapterId: string) {
        const result = await axios.get('/api/theory/chapter/' + chapterId + '/sectionIds')
        return result.data as string[]
      },
      get: async function (chapterId: string, id: string) {
        const result = await axios.get('/api/theory/chapter/' + chapterId + '/section/' + id)
        return result.data as Section
      },
      post: async function (chapterId: string, section: Section) {
        const result = await axios.post('/api/theory/chapter/' + chapterId + '/section', section)
        return result.data as Section
      },
      put: async function (chapterId: string, section: Section) {
        const result = await axios.put('/api/theory/chapter/' + chapterId + '/section/' + section.id, section)
        return result.data as Section
      },
      delete: async function (chapterId: string, id: string) {
        return axios.delete('/api/theory/chapter/' + chapterId + '/section/' + id)
      }
    },
    getIds: async function () {
      const result = await axios.get('/api/theory/chapterIds')
      return result.data as string[]
    },
    get: async function (id: string) {
      const result = await axios.get('/api/theory/chapter/' + id)
      return result.data as Chapter
    },
    post: async function (chapter: Chapter) {
      const result = await axios.post('/api/theory/chapter', chapter)
      return result.data as Chapter
    },
    put: async function (chapter: Chapter) {
      const result = await axios.put('/api/theory/chapter/' + chapter.id, chapter)
      return result.data as Chapter
    },
    delete: async function (id: string) {
      return axios.delete('/api/theory/chapter/' + id)
    }
  }
}

export { api, validImageTypes }
