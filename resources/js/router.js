import { createRouter, createWebHistory } from 'vue-router';
import HomePage from './pages/HomePage.vue';
import ProjectsPage from './pages/ProjectsPage.vue';
import ProjectDetailPage from './pages/ProjectDetailPage.vue';
import CoursesPage from './pages/CoursesPage.vue';
import CourseDetailPage from './pages/CourseDetailPage.vue';
import PostsPage from './pages/PostsPage.vue';
import PostDetailPage from './pages/PostDetailPage.vue';
import ContactPage from './pages/ContactPage.vue';
import AdminPage from './pages/AdminPage.vue';

export default createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', component: HomePage },
        { path: '/projects', component: ProjectsPage },
        { path: '/projects/:slug', component: ProjectDetailPage, props: true },
        { path: '/courses', component: CoursesPage },
        { path: '/courses/:slug', component: CourseDetailPage, props: true },
        { path: '/posts', component: PostsPage },
        { path: '/posts/:slug', component: PostDetailPage, props: true },
        { path: '/contact', component: ContactPage },
        { path: '/admin', component: AdminPage },
    ],
    scrollBehavior() {
        return { top: 0, behavior: 'smooth' };
    },
});
