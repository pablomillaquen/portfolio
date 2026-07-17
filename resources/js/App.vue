<script setup>
import { computed, onMounted, provide, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useHead } from '@vueuse/head';
import PublicShell from './components/PublicShell.vue';
import { api } from './services/api';

const route = useRoute();
const locale = ref(localStorage.getItem('portfolio-locale') || 'en');
const theme = ref(localStorage.getItem('portfolio-theme') || 'dark');
const settings = ref({});
const socialLinks = ref([]);
const authUser = ref(null);
const homePayload = ref(null);

const isAdminRoute = computed(() => route.path.startsWith('/admin'));

const siteName = 'Pablo Millaquen';
const defaultTitle = `${siteName} — Desarrollador & Investigador`;
const defaultDescription = 'Portfolio profesional de Pablo Millaquen. Desarrollador de software e investigador especializado en logística, IA y arquitectura de software.';

useHead({
    title: defaultTitle,
    htmlAttrs: { lang: locale },
    meta: [
        { name: 'description', content: defaultDescription },
        { property: 'og:title', content: defaultTitle },
        { property: 'og:description', content: defaultDescription },
        { property: 'og:image', content: 'https://pablomillaquen.com/img/og_image.png' },
        { property: 'og:url', content: 'https://pablomillaquen.com' },
        { property: 'og:type', content: 'website' },
        { property: 'og:site_name', content: siteName },
        { name: 'twitter:card', content: 'summary_large_image' },
        { name: 'twitter:title', content: defaultTitle },
        { name: 'twitter:description', content: defaultDescription },
        { name: 'twitter:image', content: 'https://pablomillaquen.com/img/og_image.png' },
    ],
    link: [
        { rel: 'canonical', href: 'https://pablomillaquen.com' },
        { rel: 'alternate', hreflang: 'es', href: 'https://pablomillaquen.com?locale=es' },
        { rel: 'alternate', hreflang: 'en', href: 'https://pablomillaquen.com?locale=en' },
    ],
});

watch(() => route.meta, (meta) => {
    if (meta?.title) {
        document.title = meta.title;
    }
}, { immediate: true });

const applyTheme = () => {
    document.documentElement.dataset.theme = theme.value;
    localStorage.setItem('portfolio-theme', theme.value);
};

const setLocale = async (value) => {
    locale.value = value;
};

const toggleTheme = () => {
    theme.value = theme.value === 'dark' ? 'light' : 'dark';
};

const refreshAuth = async () => {
    try {
        const { data } = await api.get('/api/auth/me');
        authUser.value = data.user;
    } catch {
        authUser.value = null;
    }
};

const loadShell = async () => {
    const { data } = await api.get('/api/settings', {
        params: { locale: locale.value },
    });
    settings.value = data.settings;
    socialLinks.value = data.socialLinks;
};

const loadHome = async () => {
    const { data } = await api.get('/api/home', {
        params: { locale: locale.value },
    });
    homePayload.value = data;
    settings.value = data.settings;
    socialLinks.value = data.socialLinks;
    return data;
};

watch(locale, async (value) => {
    localStorage.setItem('portfolio-locale', value);
    await loadShell();
}, { immediate: false });

watch(theme, applyTheme, { immediate: true });

onMounted(async () => {
    await Promise.all([loadShell(), refreshAuth()]);
});

provide('site', {
    locale,
    theme,
    settings,
    socialLinks,
    authUser,
    homePayload,
    setLocale,
    toggleTheme,
    refreshAuth,
    loadShell,
    loadHome,
});
</script>

<template>
    <PublicShell v-if="!isAdminRoute">
        <router-view />
    </PublicShell>
    <router-view v-else />
</template>
