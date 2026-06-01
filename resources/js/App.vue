<script setup>
import { computed, onMounted, provide, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
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
