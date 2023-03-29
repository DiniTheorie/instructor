export type SectionTranslation = {
  language: string
  title: string
  description: string
}

export type Section = {
  id: string
  translations: SectionTranslation[]
}
