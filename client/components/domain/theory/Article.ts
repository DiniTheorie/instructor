export type ArticleTranslation = {
  language: string
  markdown: string
}

export type ArticleWithUrls = Article & {
  imageUrls: string[]
}

export type Article = {
  id: string
  translations: ArticleTranslation[]
}
