export type ChapterTranslation = {
  language: string
  title: string
  description: string
}

export type Chapter = {
  id: string
  translations: ChapterTranslation[]
}
