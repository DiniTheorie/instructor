import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

export const routes = {
  home: 'home',
  category: 'category',
  categoryQuestion: 'question'
}

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: routes.home,
      component: HomeView
    },
    {
      path: '/category/:id',
      name: routes.category,
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/CategoryView.vue')
    },
    {
      path: '/category/:categoryId/question/:id',
      name: routes.categoryQuestion,
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/category/QuestionView.vue')
    }
  ]
})

export default router
