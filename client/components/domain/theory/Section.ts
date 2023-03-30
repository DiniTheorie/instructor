export type SectionConfig = {
  view: 'list' | 'gallery'
}

export const sectionViews = ['list', 'gallery']

export type SectionTranslation = {
  language: string
  title: string
  description?: string
}

export type Section = {
  id: string
  config: SectionConfig
  translations: SectionTranslation[]
}
