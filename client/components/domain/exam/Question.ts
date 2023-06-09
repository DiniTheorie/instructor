export type QuestionTranslation = {
  language: string
  question?: string
  answer_1: string
  answer_2: string
  answer_3: string
  explanation?: string
}

export type QuestionWithUrls = Question & {
  examImageUrl: string
  imageUrls: string[]
}

export type Question = {
  id: string
  meta: {
    exam: boolean
    important: boolean
    text_asa: boolean
    image_asa: boolean
    answer_1_correct: boolean
    answer_2_correct: boolean
    answer_3_correct: boolean
  }
  translations: QuestionTranslation[]
}
