import axios from 'axios'
import {displayError} from './notifiers'

const validImageTypes = ['image/jpeg', 'image/png', 'image/gif']

if (window.location.hostname === 'localhost') {
  axios.defaults.baseURL = 'https://localhost:8000'
}

const api = {
  setup: function (translator) {
    axios.interceptors.response.use(
      response => {
        return response
      },
      error => {
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

        const errorMessage = translator('_api.request_failed') + ' (' + errorText + ')'
        displayError(errorMessage)

        return Promise.reject(error)
      }
    )
  },
  getExamCategories: function () {
    return axios.get('/api/exam/categories')
  },
}

export {api, validImageTypes}
