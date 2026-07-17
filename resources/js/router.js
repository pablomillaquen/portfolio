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
        {
            path: '/',
            component: HomePage,
            meta: {
                title: 'Pablo Millaquen — Desarrollador & Investigador',
                description: 'Portfolio profesional de Pablo Millaquen. Desarrollador de software e investigador especializado en logística, IA y arquitectura de software.',
            },
        },
        {
            path: '/projects',
            component: ProjectsPage,
            meta: {
                title: 'Proyectos | Pablo Millaquen',
                description: 'Explora los proyectos de desarrollo de software y investigación de Pablo Millaquen.',
            },
        },
        {
            path: '/projects/:slug',
            component: ProjectDetailPage,
            props: true,
            meta: { type: 'project' },
        },
        {
            path: '/courses',
            component: CoursesPage,
            meta: {
                title: 'Cursos y Certificaciones | Pablo Millaquen',
                description: 'Cursos y certificaciones completados por Pablo Millaquen.',
            },
        },
        {
            path: '/courses/:slug',
            component: CourseDetailPage,
            props: true,
            meta: { type: 'course' },
        },
        {
            path: '/posts',
            component: PostsPage,
            meta: {
                title: 'Publicaciones | Pablo Millaquen',
                description: 'Artículos y publicaciones sobre desarrollo de software, investigación y tecnología.',
            },
        },
        {
            path: '/posts/:slug',
            component: PostDetailPage,
            props: true,
            meta: { type: 'post' },
        },
        {
            path: '/contact',
            component: ContactPage,
            meta: {
                title: 'Contacto | Pablo Millaquen',
                description: 'Contacta con Pablo Millaquen para oportunidades de colaboración.',
            },
        },
        {
            path: '/admin',
            component: AdminPage,
            meta: { title: 'Admin' },
        },
    ],
    scrollBehavior() {
        return { top: 0, behavior: 'smooth' };
    },
});
