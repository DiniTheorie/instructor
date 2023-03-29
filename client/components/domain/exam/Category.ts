export type CategoryTranslation = {
  language: string
  name: string
  description: string
}

export type Category = {
  id: string
  translations: CategoryTranslation[]
}
