export type ExamCategoryTranslation = {
  language: string
  name: string
  description: string
}

export type ExamCategory = {
  id: string
  translations: ExamCategoryTranslation[]
}
