import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/',
        name: 'Test',
        component: () => import('@/views/TestCustom.vue')
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router

